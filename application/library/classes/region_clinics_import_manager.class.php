<?php

	/**
	 * Класс служит для импорта клиник в БД
	 * Задание  https://trello.com/c/sXOaiode/85--
	 */
	class RegionClinicsImportManager
	{
		private $base_file_name;
		private $data_file_name;

		private $key_data;

		private $stop_words = array();
		private $number_of_clinics = 0;

		private $features_lookup = array();

		public function __construct($base_file_name, $data_file_name)
		{
			$this->base_file_name = $base_file_name;
			$this->data_file_name = $data_file_name;

			$this->features_lookup = array(
				'комната для детей' => FeatureModel::CHILDREN_ROOM,
				'детская комната' => FeatureModel::CHILDREN_ROOM,
				'комнату для детей' => FeatureModel::CHILDREN_ROOM,
				'детскую комнату' => FeatureModel::CHILDREN_ROOM,
				'комнаты для детей' => FeatureModel::CHILDREN_ROOM,
				'детской комнаты' => FeatureModel::CHILDREN_ROOM,
				'комнате для детей' => FeatureModel::CHILDREN_ROOM,
				'детской комнате' => FeatureModel::CHILDREN_ROOM,
				'пеленальный столик' => FeatureModel::CHANGING_TABLE,
				'столик для пеленания' => FeatureModel::CHANGING_TABLE,
				'пеленальный стол' => FeatureModel::CHANGING_TABLE,
				'стол для пеленания' => FeatureModel::CHANGING_TABLE,
				'бесплатные бахилы' => FeatureModel::FREE_SHOE_COVERS,
				'бахилы бесплатно' => FeatureModel::FREE_SHOE_COVERS,
				'телевизор' => FeatureModel::TV,
				'wifi' => FeatureModel::FREE_WIFI,
				'wi-fi' => FeatureModel::FREE_WIFI,
				'беспроводной интернет' => FeatureModel::FREE_WIFI,
				'пандус' => FeatureModel::RAMPANT,
				'пандуса' => FeatureModel::RAMPANT,
				'чай' => FeatureModel::TEA_COFFEE,
				'чая' => FeatureModel::TEA_COFFEE,
				'чаю' => FeatureModel::TEA_COFFEE,
				'кофе' => FeatureModel::TEA_COFFEE,
			);
		}

		public function setStopWords(array $stop_words)
		{
			$this->stop_words = $stop_words;
		}

		public function setNumberOfClinics($number_of_clinics)
		{
			$this->number_of_clinics = $number_of_clinics;
		}

		public function import($city_name)
		{
			ini_set('memory_limit', '800M');

			$this->getKeyData();

			$data = json_decode(file_get_contents($this->data_file_name), true);

			$clinic_manager = new ClinicManager();
			$clinic_phone_manager = new ClinicPhoneManager();
			$specialization_to_clinic_manager = new SpecializationToClinicManager();
			$specialization_manager = new SpecializationManager();
			$specialty_to_clinic_manager = new SpecialtyToClinicManager();
			$specialty_manager = new SpecialtyManager();
			$feature_to_clinic_manager = new FeatureToClinicManager();

			$city_manager = new CityManager();
			$city = $city_manager->getOneByName($city_name);

			if(!$city)
			{
				throw new Exception('Не найден город ' . $data['city']['name']);
			}

			foreach($data['organizations'] as $organization_info)
			{
				$url = $organization_info['url'];

				if(!isset($this->key_data[$url]))
				{
					continue;
				}

				$main_data = $this->key_data[$url];


				$clinic = $clinic_manager->getOneByYandexUrl($main_data[7]);

				if(!$clinic)
				{
					$clinic = new ClinicModel();
				}


				if($main_data[1] == 'стоматология')
				{
					$clinic->clinic_type_id = ClinicTypeModel::STOMATOLOGY;
				}
				else
				{
					$clinic->clinic_type_id = ClinicTypeModel::MULTIDISCIPLINARY;
				}


				$clinic->name = $organization_info['name'];
				$clinic->city_id = $city->getId();
				$clinic->latitude = $organization_info['coordinates'][0];
				$clinic->clinic_status_id = ClinicStatusModel::RAW;
				$clinic->longitude = $organization_info['coordinates'][1];
				$address = str_replace($city->name . ',', '', $main_data[2]);
				$address = preg_replace('/[0-9]{6},/', '', $address);

				$clinic->address = trim($address);

				if(isset($organization_info['sites']) && $organization_info['sites'])
				{
					$clinic->site = $organization_info['sites'][0];
				}

				if(isset($organization_info['working_modes']) && $organization_info['working_modes'])
				{
					$schedule = $this->getSchedule($organization_info['working_modes']);
					foreach($schedule as $field_name => $field_value)
						$clinic->{$field_name} = $field_value;
				}

				$clinic->postcode = $organization_info['postal_code'];
				$clinic->email = $organization_info['email'];
				$clinic->data_source_id = 1;

				$clinic->is_active = 0;
				$clinic->is_children = 1;
				$clinic->yandex_url = $main_data[7];
				$clinic->is_used = 0;

				$clinic->is_region = 1;

				try
				{
					$clinic->save();
				} catch(Exception $e)
				{
					continue;
				}

				$clinic_phone_manager->deleteByClinicId($clinic->getId());
				if($organization_info['phones'])
				{
					foreach($organization_info['phones'] as $phone_number)
					{
						$clinic_phone = new ClinicPhoneModel();
						$clinic_phone->clinic_id = $clinic->getId();
						$clinic_phone->phone_number = $phone_number;
						$clinic_phone->save();
					}
				}

				if($organization_info['descriptions'])
				{
					foreach($organization_info['descriptions'] as $description)
					{
						if(mb_strtolower($description['name'], 'utf-8') == 'врачи-специалисты')
						{
							$specialty_to_clinic_manager->deleteByClinicId($clinic->getId());

							$specialties = explode(', ', $description['description']);

							foreach($specialties as $specialty_name)
							{
								$specialty_name = trim(mb_strtolower($specialty_name, 'utf-8'));

								$specialty = $specialty_manager->getOneByNameForm($specialty_name);

								if($specialty)
								{
									$specialty_to_clinic = new SpecialtyToClinicModel();
									$specialty_to_clinic->clinic_id = $clinic->getId();
									$specialty_to_clinic->specialty_id = $specialty->getId();
									$specialty_to_clinic->save();
								}
							}
						}

						if(mb_strtolower($description['name'], 'utf-8') == 'услуги')
						{
							$specialization_to_clinic_manager->deleteByClinicId($clinic->getId());

							$specializations = explode(', ', $description['description']);

							if($specializations)
							{
								foreach($specializations as $specialization_name)
								{
									$specialization_name = trim(mb_strtolower($specialization_name, 'utf-8'));
									$specialization = $specialization_manager->getOneByName($specialization_name);

									if($specialization)
									{
										$specialization_to_clinic = new SpecializationToClinicModel();
										$specialization_to_clinic->specialization_id = $specialization->getId();
										$specialization_to_clinic->clinic_id = $clinic->getId();
										$specialization_to_clinic->save();
									}
								}
							}
						}
					}
				}

				$feature_to_clinic_manager->deleteByClinicId($clinic->getId());
				if($organization_info['features'])
				{
					foreach($organization_info['features'] as $feature)
					{
						$feature = mb_strtolower($feature, 'utf-8');

						if(isset($this->features_lookup[$feature]))
						{
							$feature_to_clinic = new FeatureToClinicModel();
							$feature_to_clinic->clinic_id = $clinic->getId();
							$feature_to_clinic->feature = $this->features_lookup[$feature];
							$feature_to_clinic->save();
						}
					}
				}
			}
		}

		private function getSchedule($working_modes)
		{
			$schedule = array();

			foreach($working_modes as $working_mode)
			{
				$time = $this->parseTime($working_mode['time']);

				if(!isset($working_mode['day']))
				{
					$working_mode['day'] = 'ежедн.';
				}

				if($working_mode['day'] == 'ежедн.')
				{

					for($i = 0; $i < 7; $i++)
					{
						$schedule[$i] = $time;
					}
				}

				if(preg_match('/^([а-я ]+)(?:\-|\–)([а-я ]+)$/imsu', $working_mode['day'], $matches))
				{
					$start_day = DateHelper::getDayNumberByShortRuName(trim($matches[1]));
					$end_day = DateHelper::getDayNumberByShortRuName(trim($matches[2]));

					for($i = $start_day; $i <= $end_day; $i++)
					{
						$schedule[$i] = $time;
					}
				}

				if(preg_match('/^([а-я ]+),([а-я ]+)$/imsu', $working_mode['day'], $matches))
				{
					$schedule[DateHelper::getDayNumberByShortRuName(trim($matches[1]))] = $time;
					$schedule[DateHelper::getDayNumberByShortRuName(trim($matches[2]))] = $time;
				}

				if(preg_match('/^[а-я]+$/imsu', $working_mode['day'], $matches))
				{
					$schedule[DateHelper::getDayNumberByShortRuName(trim($working_mode['day']))] = $time;
				}
			}

			$result = array();
			foreach($schedule as $day_number => $data)
			{
				$day_name = DateHelper::getWeekDayName($day_number);
				$result['start_time_' . $day_name] = trim($data['start_time']);
				$result['end_time_' . $day_name] = trim($data['end_time']);
			}

			return $result;
		}

		public function getKeyData()
		{
			$reader = new ExcelReader($this->base_file_name);
			$data = $reader->readToArray();

			unset($data[0]);

			$key_data = array();

			$i = 1;
			foreach($data as $row)
			{
				if($this->stop_words)
				{
					foreach($this->stop_words as $stop_word)
					{
						if(mb_strpos($row[0], $stop_word, null, 'utf-8') !== false)
						{
							continue(2);
						}
					}
				}

				$key_data[$row[7]] = $row;

				if($this->number_of_clinics && ($i >= $this->number_of_clinics))
				{
					break;
				}

				$i++;
			}

			$this->key_data = $key_data;
		}

		private function parseTime($time_str)
		{
			$result = array('start_time' => null, 'end_time' => null);

			if($time_str == 'круглосуточно')
			{
				$result['start_time'] = '00:00';
				$result['end_time'] = '00:00';
			}

			if(preg_match('/^(.+)\–(.+)$/', $time_str, $matches))
			{
				$result['start_time'] = trim($matches[1], ' ,');
				$result['end_time'] = trim($matches[2], ' ,');
			}

			return $result;
		}
	}