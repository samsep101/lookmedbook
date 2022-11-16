<?php
class SiteTaskManager
	{
		public static function updateDoctorsRate()
		{
            /**
             * @var DoctorManager $doctor_manager
             */
            $doctor_manager = ModelManagerFactory::getByName('doctor');

            foreach($doctor_manager->getIterator() as $doctor)
            {
                self::updateDoctorRate($doctor->getId());
            }
		}

		public static function updateDoctorRate($doctor_id)
		{
			$doctor = ModelManagerFactory::getByName('doctor')->getOneById($doctor_id);

			$doctor_rate = self::calculateDoctorRate($doctor);

			if(!$doctor_rate)
			{
				return;
			}

			$doctor->cabinet_rate = $doctor_rate->cabinet;
			$doctor->waiting_time_rate = $doctor_rate->waiting_time;
			$doctor->relationship_rate = $doctor_rate->relationship;
			$doctor->value_for_money_rate = $doctor_rate->value_for_money;
			$doctor->diagnosis_is_clear_rate = $doctor_rate->diagnosis_is_clear;
			$doctor->advice_rate = $doctor_rate->advice;
			$doctor->rate = $doctor_rate->total;

			$doctor->save();
		}

		public static function calculateDoctorRate($doctor)
		{
			return DoctorRateAlgorithm::calculate($doctor);
		}

		public static function updateClinicsRate()
		{
            /**
             * @var ClinicManager $clinic_manager
             */
            $clinic_manager = ModelManagerFactory::getByName('clinic');

            foreach($clinic_manager->getIterator() as $clinic)
            {
                self::updateClinicRate($clinic);
            }
		}

		public static function updateClinicRate(ClinicModel $clinic)
		{
			$rating_data = ClinicRateAlgorithm::calculate($clinic);

			if(!$rating_data)
			{
				return false;
			}

			$clinic_manager = new ClinicManager();
			$clinic_manager->setRateAndAdviceRateById($clinic->getId(), $rating_data['rate'], $rating_data['advice_rate']);
		}


		public static function setDoctorsAvailability()
		{
			$doctors = ModelManagerFactory::getByName('doctor')->getList();

			$doctor_manager = ModelManagerFactory::getByName('doctor');
			foreach($doctors as $doctor)
			{
				$availability = ModelManagerFactory::getByName('schedule')->getAvailabilityByDoctorId($doctor->getId());
				$doctor->availability = (int)($availability * 10);
				$doctor_manager->save($doctor);
			}
		}

		/**
		 *
		 */
		public static function processDoctorSearchQueryTasks()
		{
			$manager = new DoctorSearchQueryTaskManager();

			$tasks = $manager->getListByTaskStatusId(TaskStatusModel::IN_QUEUE);

			$doctor_manager = new DoctorManager();

			foreach($tasks as $task)
			{
				$manager->setTaskStatusIdById($task->getId(), TaskStatusModel::PROCCESS);

				$doctor_search_params = unserialize($task->sql);

				$doctor_search_params->page = null;
				$doctor_search_params->by_page = null;

				$doctors = $doctor_manager->getListByDoctorSearchParams($doctor_search_params);

				if($doctors)
				{
					foreach($doctors as $doctor)
					{
						$doctor_search_show_model = new DoctorSearchShowModel();
						$doctor_search_show_model->hash = $task->hash;
						$doctor_search_show_model->doctor_id = $doctor->getId();
						$doctor_search_show_model->balls = $doctor_search_show_model->getManager()->getMaxBallsByHash($task->hash);

						$doctor_search_show_model->getManager()->save($doctor_search_show_model);
					}
				}

				$manager->setTaskStatusIdById($task->getId(), TaskStatusModel::DONE);
			}
		}

		public static function resetDoctorSearchQueryTasks()
		{
			$task_manager = new DoctorSearchQueryTaskManager();
			$task_manager->resetAllTasks();
		}

		public static function processClinicSearchQueryTasks()
		{
			$manager = new ClinicSearchQueryTaskManager();

			$tasks = $manager->getListByTaskStatusId(TaskStatusModel::IN_QUEUE);

			foreach($tasks as $task)
			{
				$manager->setTaskStatusIdById($task->getId(), TaskStatusModel::PROCCESS);
				$db = Register::get('db');

				$clinics_info = $db->query($task->sql);

				if($clinics_info)
				{
					foreach($clinics_info as $clinic_info)
					{
						$clinic_search_show_model = new ClinicSearchShowModel();
						$clinic_search_show_model->hash = $task->hash;
						$clinic_search_show_model->clinic_id = $clinic_info['id'];
						$clinic_search_show_model->balls = $clinic_search_show_model->getManager()->getMaxBallsByHash($task->hash);

						$clinic_search_show_model->getManager()->save($clinic_search_show_model);
					}
				}

				$manager->setTaskStatusIdById($task->getId(), TaskStatusModel::DONE);
			}
		}

		public static function resetClinicSearchQueryTasks()
		{
			$task_manager = new ClinicSearchQueryTaskManager();
			$task_manager->resetAllTasks();
		}

		public static function parseDiseasesAndDiseaseBlocks()
		{
			set_time_limit(0);
//          $localPath = dirname(dirname(dirname(dirname(__FILE__)))) . '/Test.xml'; /* Путь до xml файла расположенного в корне сайта */
//			$xml_data = simplexml_load_file($localPath);
			$xml_data = simplexml_load_file('http://admin:21506@content.lookmedbook.ru/media/xml/Test.xml');

			if($xml_data)
			{
				self::clearDiseaseAltNamesAndDiseaseTagsAndAndDiseaseBlocksAndSpecialtyToDisease();
				ModelManagerFactory::getByName('disease')->setIsActive(0);

				$disease_manager = ModelManagerFactory::getByName('disease');
				$disease_alt_name_manager = ModelManagerFactory::getByName('disease_alt_name');
				$disease_tag_manager = ModelManagerFactory::getByName('disease_tag');
				$disease_to_disease_tag_manager = ModelManagerFactory::getByName('disease_to_disease_tag');
				$disease_block_manager = ModelManagerFactory::getByName('disease_block');
				$specialty_to_disease_manager = ModelManagerFactory::getByName('specialty_to_disease');
				$specialty_manager = ModelManagerFactory::getByName('specialty');

				foreach($xml_data->disease as $disease_data)
				{
					$disease = ModelManagerFactory::getByName('disease')->getOneByContentProjectId($disease_data->info->project_id);

					if($disease)
					{
						$disease->is_active = 1;

						if($disease->title != $disease_data->info->title)
						{
							$disease->title = $disease_data->info->title;
							$disease->genitive_name = null;
							$disease->prepositional_name = null;
						}

						if($disease->date_update != $disease_data->info->dt_edit)
						{
							$disease->date_update = $disease_data->info->dt_edit;
						}

						if($disease->content != $disease_data->info->project_desc)
						{
							$disease->content = $disease_data->info->project_desc;
						}

						if($disease_data->info->extended_desc == "&lt;br /&gt;<br />\r\n")
						{
							$disease->extended_content = null;
						}
						else if($disease->extended_content != $disease_data->info->extended_desc)
						{
							$disease->extended_content = $disease_data->info->extended_desc;
						}

						if($disease_data->info->sources == "&lt;br /&gt;<br />\r\n")
						{
							$disease->sources = null;
						}
						else if($disease->sources != $disease_data->info->sources)
						{
							$disease->sources = $disease_data->info->sources;
						}

						$disease->save();
					}
					else
					{
						$disease = new DiseaseModel();

						$disease->title = $disease_data->info->title;
						$disease->content = $disease_data->info->project_desc;
						$disease->extended_content = $disease_data->info->extended_desc;
						$disease->sources = $disease_data->info->sources;
						$disease->is_active = 1;
						$disease->content_project_id = $disease_data->info->project_id;

						if($disease_data->info->dt_edit)
						{
							$disease->date_update = $disease_data->info->dt_edit;
						}

						$disease->save();
					}

					$disease_id = $disease->getId();

					if($disease_id)
					{

						if($disease_data->info->alt_name)
						{

							$alt_names_string = str_replace('.', '', $disease_data->info->alt_name);
							$alt_names = explode(",", $alt_names_string);
							foreach($alt_names as $alt_name)
							{
								$disease_alt_name = new DiseaseAltNameModel();

								$disease_alt_name->alt_name = trim($alt_name);
								$disease_alt_name->disease_id = $disease_id;
								$disease_alt_name_manager->save($disease_alt_name);
							}
						}

						foreach($disease_data->specialty as $specialty)
						{

							$first_doctor = true;
							$doctors_string = str_replace('.', '', $specialty->name);
							$doctors = explode(",", $doctors_string);
							foreach($doctors as $doctor)
							{
								$specialty_to_disease = new SpecialtyToDiseaseModel();
								$specialty_id = $specialty_manager->getIdByName(trim($doctor));

								if($specialty_id)
								{
									$specialty_to_disease->specialty_id = $specialty_id;
									$specialty_to_disease->disease_id = $disease_id;
									$specialty_to_disease->is_adult = $specialty->is_adult;
									$specialty_to_disease->is_male = $specialty->is_male;
									$specialty_to_disease->is_female = $specialty->is_female;
									$specialty_to_disease->is_children = $specialty->is_children;
									$specialty_to_disease->is_newborn = $specialty->is_newborn;
									$specialty_to_disease->is_pregnant = $specialty->is_pregnant;
									if($first_doctor)
									{
										$specialty_to_disease->main_flag = 1;
									}
									$specialty_to_disease_manager->save($specialty_to_disease);

									$first_doctor = false;
								}
							}
						}

						if($disease_data->info->tags)
						{
							$tags = explode(",", $disease_data->info->tags);
							foreach($tags as $tag)
							{

								$tag_id = ModelManagerFactory::getByName('disease_tag')->getIdByTag(trim($tag));

								if(!$tag_id)
								{
									$disease_tag = new DiseaseTagModel();

									$disease_tag->tag = trim($tag);
									$disease_tag_manager->save($disease_tag);
								}

								$tag_id = ModelManagerFactory::getByName('disease_tag')->getIdByTag(trim($tag));

								if($tag_id)
								{

									$disease_to_disease_tag = new DiseaseToDiseaseTagModel();

									$disease_to_disease_tag->disease_id = $disease_id;
									$disease_to_disease_tag->disease_tag_id = $tag_id;

									$disease_to_disease_tag_manager->save($disease_to_disease_tag);
								}
							}
						}

						foreach($disease_data->block as $block)
						{

							$disease_block = new DiseaseBlockModel();

							$disease_block->disease_id = $disease_id;

							if($block->name == 'Симптомы')
							{
								$disease_block_type_id = 1;
							}
							else if($block->name == 'Инкубационный период')
							{
								$disease_block_type_id = 2;
							}
							else if($block->name == 'Формы')
							{
								$disease_block_type_id = 3;
							}
							else if($block->name == 'Причины')
							{
								$disease_block_type_id = 4;
							}
							else if($block->name == 'Диагностика')
							{
								$disease_block_type_id = 5;
							}
							else if($block->name == 'Лечение')
							{
								$disease_block_type_id = 6;
							}
							else if($block->name == 'Осложнения и последствия')
							{
								$disease_block_type_id = 7;
							}
							else if($block->name == 'Профилактика')
							{
								$disease_block_type_id = 8;
							}
							else if($block->name == 'Дополнительно')
							{
								$disease_block_type_id = 9;
							}

							$disease_block->disease_block_type_id = $disease_block_type_id;
							$disease_block->content = $block->content;
							$disease_block->is_active = $block->is_active;
							$disease_block->male_flag = $block->is_male;
							$disease_block->female_flag = $block->is_female;
							$disease_block->adult_flag = $block->is_adult;
							$disease_block->children_flag = $block->is_children;
							$disease_block->newborn_flag = $block->is_newborn;
							$disease_block->pregnant_flag = $block->is_pregnant;

							$disease_block_manager->save($disease_block);
						}
					}
				}
			}
		}

		public function clearDiseaseAltNamesAndDiseaseTagsAndAndDiseaseBlocksAndSpecialtyToDisease()
		{
			$disease_block_manager = new DiseaseBlockManager();
			$disease_block_manager->deleteAll();
			$disease_block_manager->resetAutoIncrement();

			$disease_to_disease_tag_manager = new DiseaseToDiseaseTagManager();
			$disease_to_disease_tag_manager->deleteAll();
			$disease_to_disease_tag_manager->resetAutoIncrement();

			$disease_tag_manager = new DiseaseTagManager();
			$disease_tag_manager->deleteAll();
			$disease_tag_manager->resetAutoIncrement();

			$disease_alt_name_manager = new DiseaseAltNameManager();
			$disease_alt_name_manager->deleteAll();
			$disease_alt_name_manager->resetAutoIncrement();

			$specialty_to_disease_manager = new SpecialtyToDiseaseManager();
			$specialty_to_disease_manager->deleteAll();
			$specialty_to_disease_manager->resetAutoIncrement();
			/*
$disease_manager = new DiseaseManager();
$disease_manager->deleteAll();
$disease_manager->resetAutoIncrement();*/
			//$this->redirectUrl('/admin/disease');
			//exit();
		}


		public static function updateSubscribes()
		{
			$account_manager = new AccountManager();

			$accounts = $account_manager->getList();

			$email_api = new EmailApi();

			foreach($accounts as $account)
			{
				if(!$account->is_confirm_email || !$account->email)
				{
					continue;
				}

				if($account->notification_settings && $account->notification_settings->email_notify_bonus)
				{
					$email_api->subscribe($account->email, 'Акции и бонусы');
				}
				else
				{
					$email_api->unsubscribe($account->email);
				}
			}
		}

		public static function synchSubscribes()
		{
			$email_api = new EmailApi();
			$contacts = $email_api->export_contacts();

			$account_manager = new AccountManager();

			if(count($contacts))
			{
				foreach($contacts as $contact)
				{
					$account = $account_manager->getOneByEmail($contact[0]);

					if($account)
					{
						if($contact[1] == 'unsubscribed')
						{
							$account->notification_settings->email_notify_bonus = 0;
							$account->notification_settings->save();
						}
						elseif($contact[1] == 'active')
						{
							$account->notification_settings->email_notify_bonus = 1;
							$account->notification_settings->save();
						}
					}
				}
			}
		}

		public static function processGeoIpData()
		{
			set_time_limit(0);
			ignore_user_abort(1);

			if(file_exists(ABS_ROOT . '/media/upload/geo/cities.txt'))
			{
				$geo_city_manager = new GeoCityManager();
				$geo_city_manager->truncateTable();
				$city_manager = new CityManager();
				//$city_manager->truncateTable();

				$file = file(ABS_ROOT . '/media/upload/geo/cities.txt');
				$pattern = '#(\d+)\s+(.*?)\t+(.*?)\t+(.*?)\t+(.*?)\s+(.*)#';
				foreach($file as $row)
				{
					$row = iconv('windows-1251', 'utf-8', $row);
					if(preg_match($pattern, $row, $out))
					{
						$geo_city_model = new GeoCityModel();
						//$geo_city_model->city_id = $out[1];
						$geo_city_model->city = $out[2];
						$geo_city_model->region = $out[3];
						$geo_city_model->district = $out[4];
						$geo_city_model->lat = $out[5];
						$geo_city_model->lng = $out[6];

						$geo_city_manager->save($geo_city_model);

						if(!$city_manager->getOneByNameAndRegion($out[2], $out[3]))
						{
							$city_model = new CityModel();
							$city_model->name = $out[2];
							$city_model->region = $out[3];
							$city_model->lat = $out[5];
							$city_model->lng = $out[6];

							$city_manager->save($city_model);
						}
					}
				}
			}
			else
			{
				echo 'Ошибка! Нет файла cities.txt!';
			}

			if(file_exists(ABS_ROOT . '/media/upload/geo/cidr_optim.txt'))
			{
				$geo_base_manager = new GeoBaseManager();
				$geo_base_manager->truncateTable();
				$file = file(ABS_ROOT . '/media/upload/geo/cidr_optim.txt');
				$pattern = '#(\d+)\s+(\d+)\s+(\d+\.\d+\.\d+\.\d+)\s+-\s+(\d+\.\d+\.\d+\.\d+)\s+(\w+)\s+(\d+|-)#';
				foreach($file as $row)
				{
					if(preg_match($pattern, $row, $out))
					{

						$geo_base_model = new GeoBaseModel();
						$geo_base_model->long_ip1 = $out[1];
						$geo_base_model->long_ip2 = $out[2];
						$geo_base_model->ip1 = $out[3];
						$geo_base_model->ip2 = $out[4];
						$geo_base_model->country = $out[5];
						$geo_base_model->geo_city_id = (int)$out[6];

						$geo_base_manager->save($geo_base_model);
					}
				}
			}
			else
			{
				echo 'Ошибка! Нет файла cidr_optim.txt!';
			}
		}

		public static function processSpecialtiesToClinic()
		{
			$specialty_to_clinic_manager = new SpecialtyToClinicManager();
			$doctor_to_clinic_manager = new DoctorToClinicManager();

			$specialty_to_clinic_manager->truncateTable();
			$doctor_specialties = $doctor_to_clinic_manager->getList();

			foreach($doctor_specialties as $doctor_specialty)
			{
				if($doctor_specialty->specialty_id && $doctor_specialty->clinic_id)
				{
					$existing_specialty_to_clinic = $specialty_to_clinic_manager->checkExistsBySpecialtyIdAndClinicId($doctor_specialty->specialty_id, $doctor_specialty->clinic_id);

					if(!$existing_specialty_to_clinic)
					{
						$specialty_to_clinic_model = new SpecialtyToClinicModel();
						$specialty_to_clinic_model->specialty_id = $doctor_specialty->specialty_id;
						$specialty_to_clinic_model->clinic_id = $doctor_specialty->clinic_id;

						$specialty_to_clinic_manager->save($specialty_to_clinic_model);
					}
				}
			}
		}

		public static function checkClinicsInCities()
		{
			/**
			 * @var CityManager $city_manager
			 */
			$city_manager = ModelManagerFactory::getByName('city');
			$city_manager->setIsHasClinicsFlag();
			$city_manager->setIsHasDoctorsFlag();
			$city_manager->setIsHasLaboratoriesFlag();
		}

		public static function createDistributionsTasks()
		{
			$dt = date('Y-m-d H:i:00');

			$distribution_manager = new DistributionManager();
			$distributions = $distribution_manager->getListByStartDateAndStatus($dt, 1);

			foreach($distributions as $distribution)
			{

				if($distribution->all_accounts_flag)
				{
					//Рассылка для всех аккаунтов
					$accounts = ModelManagerFactory::getByName('account')->getList();

					foreach($accounts as $account)
					{
						if($account->is_confirm_email)
						{
							$distribution_task = new DistributionTaskModel();
							$distribution_task_manager = new DistributionTaskManager();

							if(!$distribution_task_manager->checkExistsByDistributionIdAndAccountIdAndStatusId($distribution->id, $account->id, 1))
							{
								$distribution_task->distribution_id = $distribution->id;
								$distribution_task->task_status_id = 1;
								$distribution_task->account_id = $account->id;
								$distribution_task_manager->save($distribution_task);

							}
						}
					}

				}
				elseif($distribution->disease_id)
				{
					//Рассылка для групп аккаунтов, привязанных к заболеванию
					$accounts = ModelManagerFactory::getByName('account')->getByDeseaseId($distribution->disease_id);
					if($accounts)
					{
						foreach($accounts as $account)
						{
							if($account->is_confirm_email)
							{
								$distribution_task = new DistributionTaskModel();
								$distribution_task_manager = new DistributionTaskManager();

								if(!$distribution_task_manager->checkExistsByDistributionIdAndAccountIdAndStatusId($distribution->id, $account->id, 1))
								{
									$distribution_task->distribution_id = $distribution->id;
									$distribution_task->task_status_id = 1;
									$distribution_task->account_id = $account->id;
									$distribution_task_manager->save($distribution_task);
								}
							}
						}
					}

				}
				elseif($distribution->account_id)
				{
					//Персональная рассылка
					$distribution_task = new DistributionTaskModel();
					$distribution_task_manager = new DistributionTaskManager();

					$distribution_task->distribution_id = $distribution->id;
					$distribution_task->account_id = $distribution->account_id;
					$distribution_task->task_status_id = 1;

					$distribution_task_manager->save($distribution_task);
				}

				//3 - готово
				$distribution_manager->updateStatus($distribution->id, 3);
			}
		}

		public static function processDistributionsTasks()
		{
			//1 - В очереди
			$distribution_task_manager = new DistributionTaskManager();
			$tasks = $distribution_task_manager->getListByTaskStatusId(1);
			$sender = new Sender();

			if($tasks)
			{
				foreach($tasks as $task)
				{
					$notification_settings_manager = new NotificationSettingsManager();
					$settings = $notification_settings_manager->getOneByAccountId($task->account_id);
					if(count($settings))
					{
						if($settings->sms_notify && $settings->sms_notify_news)
						{
							//Отправка пользователю SMS
							$sms_api = new SmsSender();
							if($settings->sms_notify_phone)
							{
								$sms_api->send($settings->sms_notify_phone->number, $task->distribution->text);
							}
						}

						if($settings->email_notify_bonus)
						{
							//Отправка Email
							$sender->mail($settings->account->email, $task->distribution->name, $task->distribution->text);
						}
						$sender->message($settings->account_id, $task->distribution->name, $task->distribution->text);
					}
					$distribution_task_manager->updateStatus($task->id, 3);
				}
			}
		}

		public static function processVisits()
		{
			$dt = date('Y-m-d H:i:00');
			$visit_manager = new VisitManager();
			$visits = $visit_manager->getListByNotificationDt($dt);
			$sender = new Sender();

			if($visits)
			{
				$email_message = SettingsManager::get('notification_visit_email');
				$sms_message = SettingsManager::get('notification_visit_sms');
				$title = SettingsManager::get('notification_visit_title');

				foreach($visits as $visit)
				{
					$notification_settings_manager = new NotificationSettingsManager();
					$settings = $notification_settings_manager->getOneByAccountId($visit->account_id);
					if(count($settings))
					{

						$email_message_send = str_replace('{visit_user}', $visit->account->full_name, $email_message);
						$email_message_send = str_replace('{visit_time}', $visit->schedule->dt_start, $email_message_send);
						$sms_message_send = str_replace('{visit_user}', $visit->account->full_name, $sms_message);
						$sms_message_send = str_replace('{visit_time}', $visit->schedule->dt_start, $sms_message_send);

						if($settings->sms_notify && $settings->sms_notify_news)
						{
							//Отправка пользователю SMS
							$sms_api = new SmsSender();
							if($settings->sms_notify_phone)
							{
								//$sms_api->send($settings->sms_notify_phone->number, $sms_message_send);
								$sms_api->sendMessage($settings->sms_notify_phone->number, 'Напоминаем, что вы записаны на прием к врачу');
							}
						}

						if($settings->email_notify_visit)
						{
							//Отправка сообщения на почту
							$sender->mail($visit->account->email, 'Напоминание о приеме к врачу', 'Напоминаем, что вы записаны на прием к врачу');
						}

						//$sender->message($visit->account_id, $title, $email_message_send);
					}
				}
			}
		}

		public static function replaceVisits()
		{
            VisitManager::setOverdueStatus();
			/*$visit_manager = new VisitManager();
			$visits = $visit_manager->getListByCurrentDate();

			if($visits)
			{
				foreach($visits as $visit)
				{
					$visit_manager->setStatusIdById(VisitModel::FEDDBACK, $visit->id);
				}
			}*/
		}

		public static function sendEmailForLate()
		{
			$visit_manager = new VisitManager();

			$statusId = VisitModel::FEDDBACK;
			$visits = $visit_manager->getListByStatusId($statusId);
			if($visits)
			{
				$email_message_send = '<table style="border-collapse: collapse;">
                                        <tr>
                                            <td style="border: 1px solid black; padding: 5px;">
                                                № заявки
                                            </td>
                                            <td style="border: 1px solid black; padding: 5px;">
                                                ФИО пациента
                                            </td>
                                            <td style="border: 1px solid black; padding: 5px;">
                                                Редактирование
                                            </td>
                                        </tr>';
				foreach($visits as $visit)
				{
					$email_message_send .= '<tr>
                    <td style="width: 100px; padding: 5px; border: 1px solid #000000;">' . $visit->id . '</td>
                    <td style="width: 200px;  padding: 5px; border: 1px solid #000000;">' . $visit->full_name . '</td>
                    <td style="width: 400px;  padding: 5px; border: 1px solid #000000;">
                        <a href="' . SITE_URL . '/admin/visit/edit/?id=' . $visit->getId() . '">' . SITE_URL . '/admin/visit/edit/?id=' . $visit->getId() . '</a>
                    </td>
                </tr>';
				}
				$email_message_send .= '</table>';
				ServiceNotificationHelper::emailNotification($email_message_send, 'Получить обратную связь от ' . count($visits) . ' клиентов', 'is_callback');
			}
		}


		public static function saveSocialNetworkInfo()
		{
			$token_manager = new SnTokensManager();

			$tokens = $token_manager->getListByTaskStatusIdWithLimit(TaskStatusModel::IN_QUEUE, 2);

			if(count($tokens))
			{
				foreach($tokens as $token)
				{
					$token->task_status_id = TaskStatusModel::PROCCESS;
					$token->dt_start = date('Y-m-d H:i:s');
					$token->save();

					$saver = null;
					switch($token->sn_name)
					{
						case SnTokensModel::OK_NAME:
							$saver = new OkUserInfoSaver();
							break;
						case SnTokensModel::VK_NAME:
							$saver = new VkUserInfoSaver();
							break;
						case SnTokensModel::FB_NAME:
							$saver = new FbUserInfoSaver();
							break;
						case SnTokensModel::MAILRU_NAME:
							$saver = new MailruUserInfoSaver();
							break;
					}

					if(!$saver)
					{
						continue;
					}

					$saver->save($token->token, $token->account_id, $token->uid);

					$token->task_status_id = TaskStatusModel::DONE;
					$token->save();
				}
			}
		}

		public static function fillDoctorsVisitsSlots()
		{
			$doctor_manager = new DoctorManager();
			$doctors = $doctor_manager->getList();

			foreach($doctors as $doctor)
			{
				self::fillDoctorVisitsSlots($doctor->getId());
			}
		}

		public static function fillDoctorVisitsSlots($doctor_id)
		{
			$doctor_manager = new DoctorManager();
			$doctor = $doctor_manager->getOneById($doctor_id);

			$schedule_manager = new ScheduleManager();

			if(!$doctor->clinic)
			{
				return;
			}

			$clinic = $doctor->clinic;

			$start_time = time();
			if($doctor)
			{
				$time_model = $doctor;
				for($i = 0; $i <= 31; $i++)
				{
					$current_timestamp = $start_time + $i * 86400;
					$day_name = DateHelper::getDayOfWeekNameByDate($current_timestamp);
					$date = date('Y-m-d', $current_timestamp);

					$start_time_field = 'start_time_' . $day_name;
					$end_time_field = 'end_time_' . $day_name;

					$doctor_start_time = $time_model->{$start_time_field};
					$doctor_end_time = $time_model->{$end_time_field};

					if(DateHelper::isTime($doctor_start_time))
					{
						$doctor_start_time = DateHelper::getHoursByTime($doctor_start_time) . ':' . DateHelper::getMinutesByTime($doctor_start_time);
					}

					if(DateHelper::isTime($doctor_end_time))
					{
						$doctor_end_time = DateHelper::getHoursByTime($doctor_end_time) . ':' . DateHelper::getMinutesByTime($doctor_end_time);
					}

					if(!DateHelper::isTime($doctor_start_time) || !DateHelper::isTime($doctor_end_time))
					{
						$schedule_manager->deleteByDoctorIdAndDate($doctor_id, $date);
						continue;
					}

					$slots = $schedule_manager->getListByDoctorIdAndDate($doctor_id, $date);

					$is_exists = false;
					if($slots)
					{
						foreach($slots as $slot)
						{
							$slot_time_start = date('H:i', strtotime($slot->dt_start));
							$slot_time_end = date('H:i', strtotime($slot->dt_end));

							if(($slot_time_start == $doctor_start_time) && ($slot_time_end == $doctor_end_time))
							{
								$is_exists = true;
								continue;
							}
							else
							{
								$schedule_manager->deleteById($slot->id);
							}
						}
					}

					if(!$is_exists)
					{
						$schedule = new ScheduleModel();
						$schedule->doctor_id = $doctor->getId();
						$schedule->clinic_id = $clinic->getId();
						$schedule->dt_start = date('Y-m-d ' . $doctor_start_time, $current_timestamp);
						$schedule->dt_end = date('Y-m-d ' . $doctor_end_time, $current_timestamp);
						$schedule->is_busy = 0;
						$schedule->save();
					}
				}

			}
		}

		public static function processSendVisitMail()
		{

			$visit_mail_manager = new VisitMailManager();
			$visit_mails = $visit_mail_manager->getListByVisitMailStatusId(VisitMailStatusModel::NOT_SEND);

			if($visit_mails)
			{
				foreach($visit_mails as $visit_mail)
				{
					$visit_mail->run();
				}
			}

		}

		public static function calculateDoctorBalls()
		{
			set_time_limit(0);
			$doctor_manager = new DoctorManager();
			foreach($doctor_manager->getIterator() as $doctor)
			{
				if(!$doctor->is_virtual)
				{
					RateBallsCalculateHelper::calculateDoctorBalls($doctor);
				}
			}
		}

		public static function calculateClinicBalls()
		{
			$clinic_manager = new ClinicManager();

			foreach($clinic_manager->getIterator() as $clinic)
			{
				/**
				 * @var ClinicModel $clinic
				 */
				RateBallsCalculateHelper::calculateClinicBalls($clinic);
			}
		}

		public static function generateStructuredSitemap()
        {
            ini_set('memory_limit', '3G');
            $sitemapGenerator = new SitemapGenerator();
            $sitemapGenerator->generateDoctorsSitemap();
            $sitemapGenerator->generateClinicsSitemap();
            $sitemapGenerator->generateServicesSitemap();
            $sitemapGenerator->generateDiseasesSitemap();
            $sitemapGenerator->generateCommonSitemap();
            $sitemapGenerator->generateIndex('sitemap');
        }

		public static function generateSitemap()
		{
			ini_set('memory_limit', '2G');
			$links = array();

			$link = new SitemapLink();
			$link->url = SITE_URL;
			$link->changefreq = 'daily';
			$link->priority = '1.00';
			$links[] = $link;

			$link = new SitemapLink();
			$link->url = SITE_URL . '/disease';
			$links[] = $link;

			$link = new SitemapLink();
			$link->url = SITE_URL . '/help';
			$links[] = $link;

			$link = new SitemapLink();
			$link->url = SITE_URL . '/about';
			$links[] = $link;

			$link = new SitemapLink();
			$link->url = SITE_URL . '/doctor';
			$links[] = $link;

			$link = new SitemapLink();
			$link->url = SITE_URL . '/clinic';
			$links[] = $link;

			$doctor_manager = new DoctorManager();
			$doctors = $doctor_manager->getActiveList();

			if($doctors)
			{
				foreach($doctors as $doctor)
				{
					$link = new SitemapLink();
					$link->url = DoctorPageLinkViewHelper::getLink($doctor);
					$links[] = $link;
				}
			}

			$clinic_manager = new ClinicManager();
			$clinics = $clinic_manager->getActiveList();

			if($clinics)
			{
				foreach($clinics as $clinic)
				{
					$link = new SitemapLink();
					$link->url = ClinicPageLinkViewHelper::getLink($clinic);
					$links[] = $link;
				}
			}

			$disease_manager = new DiseaseManager();
			$diseases = $disease_manager->getActiveList();

			$disease_block_manager = new DiseaseBlockManager();

			if($diseases)
			{
				foreach($diseases as $disease)
				{
                    $link = new SitemapLink();
                    $link->url = DiseasePageLinkViewHelper::getLink($disease);
                    $links[] = $link;
				}
			}

			// Ссылки SEO-страниц
			$city_manager = new CityManager();
			$specialty_manager = new SpecialtyManager();

			$cities = $city_manager->getActiveList();

			$district_manager = new DistrictManager();
			$region_manager = new RegionManager();
			$street_manager = new StreetManager();
			$metro_station_manager = new MetroStationManager();

			foreach($cities as $city)
			{
                $city_specialties = $specialty_manager->getHavingDoctorsListByAddressObject($city);

                if($city->alias && count($city_specialties) > 0)
                {
                    foreach($city_specialties as $city_specialty)
                    {
                        $link = new SitemapLink();
                        $link->url = SeoLinkViewHelper::getSpecialtyPageLink($city_specialty, $city);
                        $links[] = $link;
                    }
                    unset($city_specialties);
                }
			}


			$districts = $district_manager->getHavingDoctorsList();
			foreach($districts as $district)
			{
                $district_specialties = $specialty_manager->getHavingDoctorsListByAddressObject($district);

                if($district->alias && count($district_specialties) > 0 && !empty($district->city))
                {
                    foreach($district_specialties as $specialty)
                    {
                        $link = new SitemapLink();
                        $link->url = SeoLinkViewHelper::getSpecialtyPageLink($specialty, $district);
                        $links[] = $link;
                    }
                    unset($district_specialties);
                }
			}

			$regions = $region_manager->getHavingDoctorsList();
			foreach($regions as $region)
			{
				$region_specialties = $specialty_manager->getHavingDoctorsListByAddressObject($region);

                if($region->alias && count($region_specialties) > 0 && !empty($region->district))
                {
                    foreach($region_specialties as $specialty)
                    {
                        $link = new SitemapLink();
                        $link->url = SeoLinkViewHelper::getSpecialtyPageLink($specialty, $region);
                        $links[] = $link;
                    }
                    unset($region_specialties);
                }
			}

			$metro_stations = $metro_station_manager->getHavingDoctorsList();
			if($metro_stations)
			{
				foreach($metro_stations as $metro_station)
				{
					$metro_station_specialties = $specialty_manager->getHavingDoctorsListByAddressObject($metro_station);

                    if($metro_station->alias && count($metro_station_specialties) > 0 && !empty($metro_station->region))
                    {
                        foreach($metro_station_specialties as $specialty)
                        {
                            $link = new SitemapLink();
                            $link->url = SeoLinkViewHelper::getSpecialtyPageLink($specialty, $metro_station);
                            $links[] = $link;
                        }
                        unset($metro_station_specialties);
                    }
				}
			}

			$streets = $street_manager->getHavingDoctorsList();
			if($streets)
			{
				foreach($streets as $street)
				{
					$street_specialties = $specialty_manager->getHavingDoctorsListByAddressObject($street);

                    if($street->alias && count($street_specialties) > 0 && !empty($street->region))
                    {
                        foreach($street_specialties as $specialty)
                        {
                            $link = new SitemapLink();
                            $link->url = SeoLinkViewHelper::getSpecialtyPageLink($specialty, $street);
                            $links[] = $link;
                        }
                        unset($street_specialties);
                    }
				}
			}

			$specialty_manager->clearRegister();
			$city_manager->clearRegister();
			$district_manager->clearRegister();
			$region_manager->clearRegister();
			$street_manager->clearRegister();
			$metro_station_manager->clearRegister();

			unset($specialties);
			unset($streets);
			unset($districts);
			unset($regions);

			echo 'Done ' . count($links) . "\r\n";
			flush();

			$sitemap_generator = new SitemapGenerator();
			$sitemap_generator->generate($links);
		}

        public static function generateSiteMapsForCities() {
            ini_set('memory_limit', '2G');

            $city_manager           = new CityManager();
            $doctor_manager         = new DoctorManager();
            $clinic_manager         = new ClinicManager();
            $disease_manager        = new DiseaseManager();
            $disease_block_manager  = new DiseaseBlockManager();
            $specialty_manager      = new SpecialtyManager();
            $district_manager       = new DistrictManager();
            $region_manager         = new RegionManager();
            $street_manager         = new StreetManager();
            $metro_station_manager  = new MetroStationManager();

            $cities = $city_manager->getListWithClinicsOrDoctorsOrLaboratories();
            $totalCities = count($cities);
            $count = 1;

            if($totalCities) {
                foreach($cities AS $cValue) {
                    $city = $cValue;
                    $city_id = $cValue->id;
                    $cite_url = LinkHelper::getSiteUrlByCity($cValue);

                    $fileName = $city->alias . '.sitemap';
                    echo 'Initiated the formation of the file: ' . $fileName . ". File number " . $count . " of " . $totalCities . "\r\n";

                    $doctors = $doctor_manager->getListByCityId($city_id);
                    $doctorsThere = count($doctors) > 0;

                    $clinics = $clinic_manager->getListByCityId($city_id);
                    $clinicsThere = count($clinics) > 0;

                    $diseases = $city_id == 2 ? $disease_manager->getActiveList() : array();
                    $diseasesThere = count($diseases) > 0;

                    $links = array();
                    $link = new SitemapLink();
                    $link->url = $cite_url;
                    $link->changefreq = 'daily';
                    $link->priority = '1.00';
                    $links[] = $link;

                    $link = new SitemapLink();
                    $link->url = $cite_url . '/disease';
                    $links[] = $link;

                    $link = new SitemapLink();
                    $link->url = $cite_url . '/help';
                    $links[] = $link;

                    $link = new SitemapLink();
                    $link->url = $cite_url . '/about';
                    $links[] = $link;

                    if($doctorsThere) {
                        $link = new SitemapLink();
                        $link->url = $cite_url . '/doctor';
                        $links[] = $link;
                    }

                    if($clinicsThere) {
                        $link = new SitemapLink();
                        $link->url = $cite_url . '/clinic';
                        $links[] = $link;
                    }

                    if($doctorsThere)
                    {
                        foreach($doctors as $doctor)
                        {
                            $link = new SitemapLink();
                            $link->url = DoctorPageLinkViewHelper::getLink($doctor);
                            $links[] = $link;
                        }
                    }

                    if($clinicsThere)
                    {
                        foreach($clinics as $clinic)
                        {
                            $link = new SitemapLink();
                            $link->url = ClinicPageLinkViewHelper::getLink($clinic);
                            $links[] = $link;
                        }
                    }

                    if($diseasesThere)
                    {
                        foreach($diseases as $disease)
                        {
                            if($disease->alias) {
                                $disease_tabs_flags = $disease_block_manager->getActiveDiseaseTabsFlagsByDiseaseId($disease->getId());
                                foreach($disease_tabs_flags as $key => $value)
                                {
                                    if($value)
                                    {
                                        $link = new SitemapLink();
                                        $disease->city = $city;
                                        $link->url = DiseasePageLinkViewHelper::getLink($disease) . '/' . $key;
                                        $links[] = $link;
                                    }
                                }
                            }
                        }
                    }

                     /*Ссылки SEO-страниц*/

                    $city_specialties = $specialty_manager->getHavingDoctorsListByAddressObject($city);
                    foreach($city_specialties as $city_specialty)
                    {
                        $link = new SitemapLink();
                        $link->url = SeoLinkViewHelper::getSpecialtyPageLink($city_specialty, $city);
                        $links[] = $link;
                    }
                    unset($city_specialties);

                    $districts = $district_manager->getHavingDoctorsListByCityId($city_id);
                    foreach($districts as $district)
                    {
                        $district_specialties = $specialty_manager->getHavingDoctorsListByAddressObject($district);
                        $district->city = $city;

                        foreach($district_specialties as $specialty)
                        {
                            $link = new SitemapLink();
                            $link->url = SeoLinkViewHelper::getSpecialtyPageLink($specialty, $district);
                            $links[] = $link;
                        }
                        unset($district_specialties);
                    }

                    $regions = $region_manager->getHavingDoctorsListByCityId($city_id);
                    foreach($regions as $region)
                    {
                        $region_specialties = $specialty_manager->getHavingDoctorsListByAddressObject($region);
                        $region->city = $city;

                        foreach($region_specialties as $specialty)
                        {
                            $link = new SitemapLink();
                            $link->url = SeoLinkViewHelper::getSpecialtyPageLink($specialty, $region);
                            $links[] = $link;
                        }
                        unset($region_specialties);
                    }

                    $metro_stations = $metro_station_manager->getHavingDoctorsListByCityId($city_id);
                    if($metro_stations)
                    {
                        foreach($metro_stations as $metro_station)
                        {
                            $metro_station_specialties = $specialty_manager->getHavingDoctorsListByAddressObject($metro_station);

                            foreach($metro_station_specialties as $specialty)
                            {
                                $link = new SitemapLink();
                                $link->url = SeoLinkViewHelper::getSpecialtyPageLink($specialty, $metro_station);
                                $links[] = $link;
                            }
                            unset($metro_station_specialties);
                        }
                    }

                    $streets = $street_manager->getHavingDoctorsListByCityId($city_id);
                    if($streets)
                    {
                        foreach($streets as $street)
                        {
                            $street_specialties = $specialty_manager->getHavingDoctorsListByAddressObject($street);

                            foreach($street_specialties as $specialty)
                            {
                                $link = new SitemapLink();
                                $link->url = SeoLinkViewHelper::getSpecialtyPageLink($specialty, $street);
                                $links[] = $link;
                            }
                            unset($metro_station_specialties);
                        }
                    }

                    unset($city_specialties);
                    unset($streets);
                    unset($districts);
                    unset($regions);

                    echo 'File is formed. Contains records: ' . count($links) . "\r\n\r\n";
                    flush();

                    $sitemap_generator = new SitemapGenerator();
                    $sitemap_generator->generateSiteMapFile($fileName, $links);
                    $count++;
                }

                $specialty_manager->clearRegister();
                $city_manager->clearRegister();
                $district_manager->clearRegister();
                $region_manager->clearRegister();
                $street_manager->clearRegister();
                $metro_station_manager->clearRegister();


                $doctor_manager = new DoctorManager();
                $doctors = $doctor_manager->getActiveList();

                if($doctors)
                {
                    foreach($doctors as $doctor)
                    {
                        $link = new SitemapLink();
                        $link->url = SITE_URL . DoctorPageLinkViewHelper::getLink($doctor);
                        $links[] = $link;
                    }
                }
            }
        }

		public static function generateImageSitemap() {
			ini_set('memory_limit', '2G');

			$city_manager           = new CityManager();
			$doctor_manager         = new DoctorManager();
			$clinic_manager         = new ClinicManager();

			$cities = $city_manager->getListWithClinicsOrDoctorsOrLaboratories();
			$totalCities = count($cities);
			$count = 1;
			$totalImages = 0;
			$domain = LinkHelper::getDomain();

			$links = array();

			if($totalCities) {
//				foreach($cities AS $cValue) {
                    $cValue = $city_manager->getOneById(2); /* Удалить в случае если нужно будет обрабатывать данные для всех городов */
					$city = $cValue;
					$city_id = $cValue->id;
					$cite_url = LinkHelper::getSiteUrlByCity($cValue);
					$imagesCount = 0;

//					echo 'Started processing for the city: ' . $city->alias . ". City number " . $count . " of " . $totalCities . "\r\n";
					echo 'Started processing for the city: ' . $city->alias . ". \r\n";

					$doctors = $doctor_manager->getListByCityId($city_id);
					$doctorsThere = count($doctors) > 0;

					$clinics = $clinic_manager->getListByCityId($city_id);
					$clinicsThere = count($clinics) > 0;


					if($doctorsThere)
					{
						foreach($doctors as $doctor)
						{
							$image_manager           = ModelManagerFactory::getByName('image');
							$resized_image_manager   = ModelManagerFactory::getByName('resized_image');

							$diImages = array();

							$doctor_images = $image_manager->getListByDoctorId($doctor->getId());

							$location = DoctorPageLinkViewHelper::getLink($doctor);

							if(count($doctor_images) > 0) {
								foreach($doctor_images AS $diValue) {
									if(file_exists(ABS_ROOT . $diValue->path))
									{
										$diImages[] = $cite_url . $diValue->path;
									}

									$resized_images = $resized_image_manager->getListByImageId($diValue->getId());

									foreach($resized_images AS $riValue) {

										if(file_exists(ABS_ROOT . $riValue->path))
										{
											$diImages[] = $cite_url . $riValue->path;
										}
									}
								}
							}


							if(count($diImages) > 0) {
								$imagesCount += count($diImages);
								$links[] = array(
									'location' => $location,
									'images'   => $diImages
								);
							}
						}
					}

					if($clinicsThere)
					{
						foreach($clinics as $clinic)
						{
							$image_manager           = ModelManagerFactory::getByName('image');
							$resized_image_manager   = ModelManagerFactory::getByName('resized_image');

							$ciImages = array();

							$clinic_images = $image_manager->getListByClinicId($clinic->getId());

							$location = ClinicPageLinkViewHelper::getLink($clinic);

							if(count($clinic_images) > 0) {
								foreach($clinic_images AS $ciValue) {
									if(file_exists(ABS_ROOT . $ciValue->path))
									{
										$ciImages[] = $cite_url . $ciValue->path;
									}

									$resized_images = $resized_image_manager->getListByImageId($ciValue->getId());

									foreach($resized_images AS $riValue) {

										if(file_exists(ABS_ROOT . $riValue->path))
										{
											$ciImages[] = $cite_url . $riValue->path;
										}
									}
								}
							}

							if(count($ciImages) > 0) {
								$imagesCount += count($ciImages);
								$links[] = array(
									'location' => $location,
									'images'   => $ciImages
								);
							}
						}
					}


					echo 'Data processing is completed. Number of images: ' . $imagesCount . "   \r\n\r\n";
					flush();
					$count++;
					$totalImages += $imagesCount;
//				}

				$totalRecords = count($links);

				if($totalRecords > 0) {
					$sitemap_generator = new SitemapGenerator();
					$sitemap_generator->generateImageSiteMap($links);
				}

                echo 'Image is formed. Total number of records: ' . $totalRecords . '. Total images:' . $totalImages . "   \r\n\r\n";
				$city_manager->clearRegister();
			}
		}

		public static function generateDiseaseExcelFile()
		{
			$disease_manager = new DiseaseManager();

			$disease_block_manager = new DiseaseBlockManager();

			$result = array();
			foreach($disease_manager->getList() as $disease)
			{
				$disease_tabs_flags = $disease_block_manager->getActiveDiseaseTabsFlagsByDiseaseId($disease->getId());

				foreach($disease_tabs_flags as $key => $value)
				{
					$disease_info = array();
					$disease_info[] = $disease->title;

					if($value)
					{
						switch($key)
						{
							case 'male':
								$disease_info[] = 'Мужчины';
								break;
							case 'female':
								$disease_info[] = 'Женщины';
								break;
							case 'children':
								$disease_info[] = 'Дети';
								break;
							case 'newborn':
								$disease_info[] = 'Новорожденные';
								break;
							case 'pregnant':
								$disease_info[] = 'Беременные';
								break;
							case 'adult':
								$disease_info[] = 'Взрослые';
								break;
						}
						$disease_info[] = SITE_URL . DiseasePageLinkViewHelper::getLink($disease) . '/' . $key;
						$result[] = $disease_info;
					}
				}
			}

			PhpHeaderHelper::csv('disease.csv');
			$csv_generator = new CsvGenerator();
			echo $csv_generator->generateFromArray($result);
		}

		public static function generateDoctorsExcelFile()
		{
			set_time_limit(0);
			$clinic_manager = new ClinicManager();

			$result = array();
			foreach($clinic_manager->getList() as $clinic)
			{
				$result[] = array($clinic->name);
				$result[] = array('----------------------------------------------------------------------------------------');


				foreach($clinic->doctors as $doctor)
				{
					$doctor_info = array();
					$doctor_info[] = $doctor->full_name;

					$specialties = $doctor->getSpecialtiesListByClinicId($clinic->getId());

					$specialties_names = '';
					foreach($specialties as $specialty)
					{
						$specialties_names .= $specialty->name . ', ';
					}
					$specialties_names = trim($specialties_names, ', ');

					$doctor_info[] = $specialties_names;

					$result[] = $doctor_info;
				}
				$result[] = array();
				$result[] = array();
			}

			PhpHeaderHelper::csv('doctor.csv');
			$csv_generator = new CsvGenerator();
			echo $csv_generator->generateFromArray($result);
		}

		public static function generateActiveDiseaseExcelFile()
		{
			$disease_manager = new DiseaseManager();

			$resultEnd = array();
			foreach($disease_manager->getActiveList() as $disease)
			{
				$result = array();
				$result[] = $disease->title;
				$link = '';
				$link .= SITE_URL . '/disease/' . $disease->alias . ', ';
				$link = trim($link, ', ');
				$result[] = $link;
				$resultEnd[] = $result;
			}

			PhpHeaderHelper::csv('disease.csv');
			$csv_generator = new CsvGenerator();
			echo $csv_generator->generateFromArray($resultEnd);
		}

		public static function generateFileCache()
		{
			$generator = new FileCacheGenerator();
			$generator->generate();
		}

		public static function setDoctorsPurposesOfVisit()
		{
			$specialty_manager = new SpecialtyManager();
			$specialties_ids = $specialty_manager->getIdList();

			foreach($specialties_ids as $specialty_id)
			{
				self::setDoctorsPurposesOfVisitBySpecialtyId($specialty_id);
			}
		}

		public static function setDoctorsPurposesOfVisitBySpecialtyId($specialty_id)
		{
			$doctor_specialty_to_clinic_manager = new DoctorSpecialtyToClinicManager();

			$relations = $doctor_specialty_to_clinic_manager->getListBySpecialtyId($specialty_id);

			$specialty_manager = new SpecialtyManager();
			$specialty = $specialty_manager->getOneById($specialty_id);

			$pv2d_manager = new PurposeOfVisitToDoctorManager();


			if($relations)
			{
				foreach($relations as $relation)
				{
					if($specialty->main_purposes_of_visit)
					{
						foreach($specialty->main_purposes_of_visit as $purpose)
						{
							if(!$pv2d_manager->getOneByPurposeOfVisitIdAndDoctorIdAndClinicIdAndSpecialtyId($purpose->getId(), $relation->doctor_id, $relation->clinic_id, $relation->specialty_id))
							{
								$pv2d = new PurposeOfVisitToDoctorModel();
								$pv2d->doctor_id = $relation->doctor_id;
								$pv2d->purpose_of_visit_id = $purpose->getId();
								$pv2d->clinic_id = $relation->clinic_id;
								$pv2d->specialty_id = $relation->specialty_id;
								$pv2d->is_auto = 1;
								$pv2d->save();
							}
						}
					}
				}

				$pv2d_manager->deleteUnActiveMainPurposesBySpecialtyId($specialty_id);
			}
		}

		public static function generateDiseaseDescriptions()
		{
			$disease_manager = new DiseaseManager();
			$diseases = $disease_manager->getDiseasesWithoutDescription(15);

			foreach($diseases as $disease)
			{
				$disease->save();
			}
		}

		public static function sendVisitNotifications()
		{
			$date = date('Y-m-d H:i:00');
			$visit_manager = new VisitManager();
			$visits = $visit_manager->getListForYandexNotificationsByStatusIdAndDate(VisitModel::CONFIRMED, $date);

			if($visits)
			{
				foreach($visits as $visit)
				{
					if(!$visit->doctor_id || $visit->doctor->is_virtual == 1)
					{
						$tokens = array(
							'clinic_name' => $visit->clinic_name,
							'doctor_specialty' => $visit->visit_specialty,
							'doctor_specialty_g' => $visit->genitive_visit_specialty,
							'doctor_specialty_d' => $visit->dative_visit_specialty,
							'doctor_specialty_pl' => $visit->plural_visit_specialty,
							'visit_date' => DateViewHelper::date($visit->dt, 'day_and_month_and_week_day'),
							'visit_time' => DateViewHelper::date($visit->visit_start_time, 'time'),
							'clinic_address' => $visit->clinic->address
						);
						$code = 'sms_notify_without_doctor';
					}
					else
					{
						$tokens = array(
							'clinic_name' => $visit->clinic_name,
							'doctor_specialty' => $visit->visit_specialty,
							'doctor_specialty_g' => $visit->genitive_visit_specialty,
							'doctor_specialty_d' => $visit->dative_visit_specialty,
							'doctor_specialty_pl' => $visit->plural_visit_specialty,
							'doctor_name' => $visit->doctor_name,
							'visit_date' => DateViewHelper::date($visit->dt, 'day_and_month_and_week_day'),
							'visit_time' => DateViewHelper::date($visit->visit_start_time, 'time'),
							'clinic_address' => $visit->clinic->address
						);
						$code = 'sms_notify';
					}
					$template_data = MailTemplateDataHelper::getTemplateByCodeAndTokens($code, $tokens);
					SmsSender::sendMessage($visit->phone, $template_data->text);
				}
			}
		}

		public static function sendVisitPushNotifications()
		{
			/**
			 * @var VisitManager $visit_manager
			 * @var MobileNotificationTokenManager $mobile_notification_token_manager
			 */
			$visit_manager = ModelManagerFactory::getByName('visit');
			$mobile_notification_token_manager = ModelManagerFactory::getByName('mobile_notification_token');
			$push_sender = new MobilePushNotificationSenderHelper();

			if((date('i') == 0) || (date('i') == 30))
			{
				$server_timezone = SettingsManager::get('server_timezone');
				$needed_timezone = date('H') - 14 + $server_timezone;

				$tokens = $mobile_notification_token_manager->getListByTimezone($needed_timezone);
				// Только для этих аккаунтов сейчас необходимо слать уведомления за неделю, за сутки и напоминание об отзыве
				// (у них сейчас 14 часов дня)
				$active_accounts = array();
				if($tokens)
				{
					foreach($tokens as $token)
					{
						$active_accounts[$token->account_id] = true;
					}
				}

				// Уведомления за сутки до визита
				$visit_criteria = new VisitSearchCriteria();
				$visit_criteria->days_count_to_visit_min = 1;
				$visit_criteria->days_count_to_visit_max = 1;
				$visit_criteria->visit_status_id = VisitStatusModel::CONFIRMED;
				$visits = $visit_manager->getListByModelSearchCriteria($visit_criteria);
				$visits = self::getVisitsBySelectedAccount($active_accounts, $visits);
				$push_sender->sendVisitsNotifications($visits, MobilePushNotificationTypeModel::VISIT_TOMORROW);

				// Уведомления за неделю до визита
				$visit_criteria = new VisitSearchCriteria();
				$visit_criteria->days_count_to_visit_min = 7;
				$visit_criteria->days_count_to_visit_max = 7;
				$visit_criteria->visit_status_id = VisitStatusModel::CONFIRMED;
				$visits = $visit_manager->getListByModelSearchCriteria($visit_criteria);
				$visits = self::getVisitsBySelectedAccount($active_accounts, $visits);
				$push_sender->sendVisitsNotifications($visits, MobilePushNotificationTypeModel::VISIT_IN_A_WEEK);

				$days_after_visit = array(7, 14, 21, 28);
				foreach($days_after_visit as $days_count)
				{
					$visit_criteria = new VisitSearchCriteria();
					$visit_criteria->days_count_after_visit_min = $days_count;
					$visit_criteria->days_count_after_visit_max = $days_count;
					$visit_criteria->visit_status_id = VisitStatusModel::VISITED;
					$visit_criteria->not_has_doctor_review = 1;
					$visits = $visit_manager->getListByModelSearchCriteria($visit_criteria);
					$visits = self::getVisitsBySelectedAccount($active_accounts, $visits);
					$push_sender->sendVisitsNotifications($visits, MobilePushNotificationTypeModel::NEED_TO_WRITE_A_REVIEW);
				}
			}

			// уведомление перед началом визита
			$visit_criteria = new VisitSearchCriteria();
			$visit_criteria->minutes_to_visit = SettingsManager::get('push_notification_remind_time');
			$visit_criteria->visit_status_id = VisitStatusModel::CONFIRMED;
			$visits = $visit_manager->getListByModelSearchCriteria($visit_criteria);
			$push_sender->sendVisitsNotifications($visits, MobilePushNotificationTypeModel::VISIT_TODAY);
		}

		private static function getVisitsBySelectedAccount($active_accounts, array $visits)
		{
			/**
			 * @var VisitModel[] $visits
			 */
			$result = array();
			foreach($visits as $visit)
			{
				if(isset($active_accounts[$visit->account_id]))
				{
					$result[] = $visit;
				}
			}

			return $result;
		}

		public static function createVirtualDoctors()
		{
			ModelManager::disableEntityMapGlobal();

			/**
			 * @var ClinicManager $clinic_manager
			 */
			$clinic_manager = ModelManagerFactory::getByName('clinic');

			$clinics = [];
			$number = 1;
			foreach($clinic_manager->getIterator() as $clinic) {
				$clinics[$number] = $clinic->getId();
				$number++;
			}

			foreach($clinics as $number=>$clinic_id) {
				/**
				 * @var ClinicModel $clinic
				 */
				self::createClinicVirtualDoctors($clinic_id);
				echo $number . ' Done! clinic_id = ' . $clinic_id . "\r\n";
				flush();
			}

		}

		public static function createClinicVirtualDoctors($clinic_id)
		{
			//ini_set('memory_limit', '512M');

			/**
			 * @var SpecializationManager $specialization_manager
			 * @var SpecialtyManager $specialty_manager
			 * @var ClinicModel $clinic
			 * @var SpecialtyModel[] $empty_specialties
			 * @var PurposeOfVisitManager $purpose_of_visit_manager
			 * @var SpecializationModel[] $empty_specializations
			 */
			$clinic_manager = ModelManagerFactory::getByName('clinic');
			$specialization_manager = ModelManagerFactory::getByName('specialization');

			//есть подозрение, что этот id - одно и то же, что на входе в функцию
			$clinic = $clinic_manager->getOneById($clinic_id);
			$clinic_id = $clinic->getId();
			unset($clinic);

			$empty_specializations = $specialization_manager->getListWithoutDoctorsByClinicId($clinic_id);
			$empty_specialties = array();

			if($empty_specializations)
			{
				foreach($empty_specializations as $specialization)
				{
					$empty_specialties[] = $specialization->main_specialty;
				}
			}

			$purpose_of_visit_to_doctor_manager = ModelManagerFactory::getByName('purpose_of_visit_to_doctor');

			if($empty_specialties)
			{
				foreach($empty_specialties as $empty_specialty)
				{
					$doctor = new DoctorModel();
					$doctor->is_active = 1;
					$doctor->is_virtual = 1;
					$doctor->rate = 4;
					$doctor->disableValidation();
					$doctor->save();

					$doctor_to_clinic = new DoctorToClinicModel();
					$doctor_to_clinic->doctor_id = $doctor->getId();
					$doctor_to_clinic->clinic_id = $clinic_id;
					$doctor_to_clinic->disableValidation();
					$doctor_to_clinic->save();


					$doctor_specialty_to_clinic = new DoctorSpecialtyToClinicModel();
					$doctor_specialty_to_clinic->doctor_id = $doctor->getId();
					$doctor_specialty_to_clinic->clinic_id = $clinic_id;
					$doctor_specialty_to_clinic->specialty_id = $empty_specialty->id;
					$doctor_specialty_to_clinic->disableValidation();
					$doctor_specialty_to_clinic->save();

					$purpose_of_visit_manager = ModelManagerFactory::getByName('purpose_of_visit');
					$purposes = $purpose_of_visit_manager->getListBySpecialtyIdAndClinicId($empty_specialty->getId(), $clinic_id);

					/**
					 * @var PurposeOfVisitModel[] $purposes
					 */
					$purpose_of_visit_to_doctor_data = array();
					foreach($purposes as $purpose)
					{
						$purpose_of_visit_to_doctor_data[] = array(
							'clinic_id' => $clinic_id,
							'doctor_id' => $doctor->getId(),
							'specialty_id' => $empty_specialty->getId(),
							'purpose_of_visit_id' => $purpose->getId(),
						);
					}

					$fields = array(
						'clinic_id',
						'doctor_id',
						'specialty_id',
						'purpose_of_visit_id'
					);

					$purpose_of_visit_to_doctor_manager->insertList($purpose_of_visit_to_doctor_data, $fields);
				}
			}

			if(isset($empty_specializations))
			{
				unset($empty_specializations);
			}

			if(isset($empty_specialties))
			{
				unset($empty_specialties);
			}
		}


		public function fillRandomSortFields()
		{
			/**
			 * @var DoctorManager $doctor_manager
			 */
			$doctor_manager = ModelManagerFactory::getByName('doctor');
			$doctor_manager->fillRandomSortField();
		}

		public static function generateClinicExcelFile()
		{
			set_time_limit(0);

			/**
			 * @var ClinicManager $clinic_manager
			 * @var ClinicModel $clinic
			 */

			$clinic_manager = ModelManagerFactory::getByName('clinic');

			$result = array();
			foreach($clinic_manager->getListByCityId(CityModel::MOSCOW_ID) as $clinic)
			{
				$clinic_info = array();

				$clinic_info[] = $clinic->name;
				$clinic_info[] = SITE_URL . ClinicPageLinkViewHelper::getLink($clinic);
				$clinic_info[] = $clinic->metro_station_name;
				$clinic_info[] = $clinic->region->district->formal_name;
				$clinic_info[] = $clinic->region->name;
				$clinic_info[] = $clinic->street->street_type->name;
				$clinic_info[] = $clinic->address;

				$result[] = $clinic_info;
			}

			PhpHeaderHelper::csv('clinic.csv');
			$csv_generator = new CsvGenerator();
			echo $csv_generator->generateFromArray($result);
		}

		public static function generateDoctorsSpecialtyExcelFile()
		{
			set_time_limit(0);

			/**
			 * @var DoctorManager $doctor_manager
			 */

			$doctor_manager = ModelManagerFactory::getByName('doctor');

			$params = new DoctorSearchParams();
			$params->city_id = CityModel::MOSCOW_ID;

			$result = array();
			foreach($doctor_manager->getListByDoctorSearchParams($params) as $doctor)
			{
				if($doctor->is_virtual)
				{
					continue;
				}

				$doctor_info = array(
					'link' => '',
					'full_name' => '',
					'metro_station_name' => '',
					'specialty' => '',
					'clinic' => '',
					'district' => '',
					'region' => '',
					'street_type' => '',
					'street' => '',
				);

				$doctor_info['link'] = SITE_URL . DoctorPageLinkViewHelper::getLink($doctor);
				$doctor_info['full_name'] = $doctor->full_name;

				foreach($doctor->specialties as $specialty)
				{
					$doctor_info['specialty'] .= ($specialty->name . ', ');
				}
				$doctor_info['specialty'] = trim($doctor_info['specialty'], ', ');

				/**
				 * @var ClinicModel $clinic
				 */

				foreach($doctor->clinics as $clinic)
				{
					$doctor_info['clinic'] .= ($clinic->name . ', ');
					$doctor_info['metro_station_name'] .= ($clinic->metro_station_name . ', ');
					$doctor_info['district'] .= ($clinic->region->district->formal_name . ', ');
					$doctor_info['region'] .= ($clinic->region->name . ', ');
					$doctor_info['street_type'] .= ($clinic->street->street_type->name . ', ');
					$doctor_info['street'] .= ($clinic->address . ', ');
				}
				$doctor_info['clinic'] = trim($doctor_info['clinic'], ', ');
				$doctor_info['metro_station_name'] = trim($doctor_info['metro_station_name'], ', ');
				$doctor_info['district'] = trim($doctor_info['district'], ', ');
				$doctor_info['region'] = trim($doctor_info['region'], ', ');
				$doctor_info['street_type'] = trim($doctor_info['street_type'], ', ');
				$doctor_info['street'] = trim($doctor_info['street'], ', ');

				$result[] = $doctor_info;
			}

			PhpHeaderHelper::csv('doctor_specialty.csv');
			$csv_generator = new CsvGenerator();
			echo $csv_generator->generateFromArray($result);
		}

		public static function generateSpecialtyLocationExcelFile()
		{
			set_time_limit(0);
			/**
			 * @var SpecialtyManager $specialty_manager
			 * @var ClinicManager $clinic_manager
			 * @var ClinicModel $clinic
			 * @var SpecialtyModel $specialty
			 * @var DoctorManager $doctor_manager
			 * @var DoctorModel $doctor
			 */

			$clinic_manager = ModelManagerFactory::getByName('clinic');
			$specialty_manager = ModelManagerFactory::getByName('specialty');
			$doctor_manager = ModelManagerFactory::getByName('doctor');

			$result = array();
			foreach($specialty_manager->getHavingDoctorsListByCityId(CityModel::MOSCOW_ID) as $specialty)
			{
				$doctor_search_params = new DoctorSearchParams();
				$doctor_search_params->specialty_id = $specialty->getId();
				$doctor_search_params->city_id = CityModel::MOSCOW_ID;

				$doctors = $doctor_manager->getListByDoctorSearchParams($doctor_search_params);

				foreach($doctors as $doctor)
				{
					$clinic_search_params = new ClinicSearchParams();
					$clinic_search_params->specialty_id = $specialty->getId();
					$clinic_search_params->city_id = CityModel::MOSCOW_ID;
					$clinic_search_params->doctor_id = $doctor->getId();

					foreach($clinic_manager->getListByClinicSearchParams($clinic_search_params) as $clinic)
					{
						$clinic_specialty_info = array();
						$url = AliasLinkViewHelper::getLink('doctor', $specialty);

						$clinic_specialty_info[] = $specialty->name;
						$clinic_specialty_info[] = $url;
						$clinic_specialty_info[] = $clinic->region->district->formal_name;
						$clinic_specialty_info[] = $url . '/' . $clinic->region->district->alias;
						$clinic_specialty_info[] = $clinic->region->name;
						$clinic_specialty_info[] = $url . '/' . $clinic->region->alias;
						$clinic_specialty_info[] = $clinic->street->street_type->name;
						$clinic_specialty_info[] = $clinic->street->name;
						$url .= ('/' . $clinic->street->alias);
						$clinic_specialty_info[] = $url;

						if(count($result) == 0)
						{
							$result[] = $clinic_specialty_info;
						}
						elseif(!in_array($url, $result[count($result) - 1]))
						{
							$result[] = $clinic_specialty_info;
						}
					}
				}
			}

			PhpHeaderHelper::csv('specialty_location.csv');
			$csv_generator = new CsvGenerator();
			echo $csv_generator->generateFromArray($result);
		}

        public static function clearDeletedDoctorBindings()
        {
            /**
             * @var DoctorSpecialtyToClinicManager $doctor_specialty_to_clinic_manager
             * @var ModerateDoctorToClinicManager $moderate_doctor_to_clinic_manager
             * @var ModeratePurposeOfVisitToDoctorManager $moderate_purpose_of_visit_to_doctor_manager
             * @var PurposeOfVisitToDoctorManager $purpose_of_visit_to_doctor_manager
             * @var DoctorSpecialtyToClinicModel[] $doctor_specialties_to_clinic
             * @var PurposeOfVisitToDoctorModel[] $purposes_of_visit_to_doctor
             * @var ModeratePurposeOfVisitToDoctorModel[] $moderate_purposes_of_visit_to_doctor
             */
            $doctor_specialty_to_clinic_manager = ModelManagerFactory::getByName('doctor_specialty_to_clinic');
            $purpose_of_visit_to_doctor_manager = ModelManagerFactory::getByName('purpose_of_visit_to_doctor');
            $moderate_purpose_of_visit_to_doctor_manager = ModelManagerFactory::getByName('moderate_purpose_of_visit_to_doctor');

            $time = strtotime("-12 hour", time());
            $doctor_specialties_to_clinic = $doctor_specialty_to_clinic_manager->getListByIsToDeleteAndDeleteDate(1, $time);
            $purposes_of_visit_to_doctor = $purpose_of_visit_to_doctor_manager->getListByIsToDeleteAndDeleteDate(1, $time);

            if ($doctor_specialties_to_clinic) {
                foreach ($doctor_specialties_to_clinic as $doctor_specialty_to_clinic) {
                    $doctor_specialty_to_clinic->delete();
                }
            }
            if ($purposes_of_visit_to_doctor) {
                foreach ($purposes_of_visit_to_doctor as $purpose_of_visit_to_doctor) {
                    $moderate_purposes_of_visit_to_doctor = $moderate_purpose_of_visit_to_doctor_manager->getListByDoctorIdAndClinicId($purpose_of_visit_to_doctor->doctor_id, $purpose_of_visit_to_doctor->clinic_id);
                    if ($moderate_purposes_of_visit_to_doctor) {
                        foreach ($moderate_purposes_of_visit_to_doctor as $moderate_purpose_of_visit_to_doctor) {
                            $moderate_purpose_of_visit_to_doctor->delete();
                        }
                    }
                    $purpose_of_visit_to_doctor->delete();
                }
            }
        }

        public static function checkLaboratoriesInCities()
        {
            /**
             * @var CityManager $city_manager
             */
            $city_manager = ModelManagerFactory::getByName('city');
            $city_manager->setIsHasLaboratoriesFlag();
        }

        private static $number_of_stored_equals = 6;

        public static function createEqualClinicsAndDoctors()
        {
            /**
             * @var ClinicManager $clinic_manager
             * @var ClinicModel $clinic
             * @var DoctorManager $doctor_manager
             * @var DoctorModel $doctor
             * @var DistrictManager $district_manager
             * @var DistrictModel[] $districts
             * @var EqualClinicManager $equal_clinic_manager
             * @var EqualClinicModel[] $equal_clinics
             * @var EqualDoctorManager $equal_doctor_manager
             * @var EqualDoctorModel[] $equal_doctors
             * @var DistrictModel $doctor_district
             * @var RegionModel[] $doctor_regions
             * @var RegionModel $region
             */

            set_time_limit(0);
            ini_set('memory_limit', '512M');

            $clinic_manager = ModelManagerFactory::getByName('clinic');
            $equal_clinic_manager = ModelManagerFactory::getByName('equal_clinic');

            // Удаление неактивных клиник из списка похожих клиник
            $equal_clinic_manager->deleteListOfNotActiveClinics();

            foreach($clinic_manager->getIterator() as $clinic)
            {
                if($clinic->region_id)
                {
                    $equal_clinics = $equal_clinic_manager->getListByClinicId($clinic->getId());

                    if(count($equal_clinics) < self::$number_of_stored_equals)
                    {
                        self::createEqualClinic($clinic->getId(), $equal_clinics);
                    }
                }
            }

            $doctor_manager = ModelManagerFactory::getByName('doctor');
            $equal_doctor_manager = ModelManagerFactory::getByName('equal_doctor');
            $district_manager = ModelManagerFactory::getByName('district');

            $equal_doctor_manager->deleteInactiveDoctors();

            for ($page = 1; $doctors = $doctor_manager->getActiveDoctors($page); $page++) {

                foreach ($doctors as $doctor) {
                    $districts = $district_manager->getListOfDoctorDistrictsByDoctorId($doctor->getId());

                    if (count($districts) == 1) {
                        $equal_doctors = $equal_doctor_manager->getListByDoctorId($doctor->getId());

                        if (count($equal_doctors) < self::$number_of_stored_equals) {
                            $doctor_district = $districts[0];
                            $doctor_regions = $doctor_district->regions;

                            $regions = '';
                            foreach ($doctor_regions as $region) {
                                $regions .= ', ' . (int)$region->getId();
                            }

                            $regions = trim($regions, ', ');

                            self::createEqualDoctor($doctor->getId(), $equal_doctors, $regions);
                        }
                    }
                }
            }
        }

        /**
         * @param int $clinic_id
         * @param EqualClinicModel[] $equal_clinics
         */
        public static function createEqualClinic($clinic_id, $equal_clinics)
        {
            /**
             * @var SpecializationToClinicManager $specialization_to_clinic_manager
             * @var SpecializationToClinicModel[] $related_clinics
             * @var SpecializationToClinicModel $related_clinic
             * @var EqualClinicManager $equal_clinic_manager
             * @var EqualClinicModel $equal_clinic
             */

            $equal_ids = '';
            $equal_clinics_count = count($equal_clinics);
            if($equal_clinics_count > 0)
            {
                foreach($equal_clinics as $equal_clinic)
                {
                    $equal_ids .= ', ' .(int)$equal_clinic->equal_clinic_id;
                }

                $equal_ids = trim($equal_ids, ', ');
            }

            $specialization_to_clinic_manager = ModelManagerFactory::getByName('specialization_to_clinic');
            $related_clinics = $specialization_to_clinic_manager->getListEqualOfDistrictByClinicId($clinic_id, $equal_ids);

            if($related_clinics)
            {
                $equal_clinics_data = array();

                $number = 1;
                foreach($related_clinics as $related_clinic)
                {
                    $equal_clinics_data[] = array(
                        'clinic_id' => $clinic_id,
                        'equal_clinic_id' => $related_clinic->clinic_id
                    );

                    if($number >= self::$number_of_stored_equals - $equal_clinics_count)
                    {
                        break;
                    }

                    $number++;
                }

                $fields = array(
                    'clinic_id',
                    'equal_clinic_id'
                );

                $equal_clinic_manager = ModelManagerFactory::getByName('equal_clinic');
                $equal_clinic_manager->insertList($equal_clinics_data, $fields);
            }
        }

        public static function createEqualDoctor($doctor_id, $equal_doctors, $doctor_regions)
        {
            /**
             * @var DoctorSpecialtyToClinicManager $doctor_specialty_to_clinic_manager
             * @var DoctorSpecialtyToClinicModel[] $related_doctors
             * @var DoctorSpecialtyToClinicModel $related_doctor
             * @var EqualDoctorManager $equal_doctor_manager
             * @var EqualDoctorModel[] $equal_doctors
             * @var EqualDoctorModel $equal_doctor
             */

            $equal_ids = '';
            $equal_doctors_count = count($equal_doctors);
            if($equal_doctors_count > 0)
            {
                foreach($equal_doctors as $equal_doctor)
                {
                    $equal_ids .= ', ' .(int)$equal_doctor->equal_doctor_id;
                }

                $equal_ids = trim($equal_ids, ', ');
            }

            $doctor_specialty_to_clinic_manager = ModelManagerFactory::getByName('doctor_specialty_to_clinic');
            $related_doctors = $doctor_specialty_to_clinic_manager->getListEqualOfDistrictByDoctorId($doctor_id, $equal_ids, $doctor_regions);

            if($related_doctors)
            {
                $equal_doctors_data = array();

                $number = 1;
                foreach($related_doctors as $related_doctor)
                {
                    $equal_doctors_data[] = array(
                        'doctor_id' => $doctor_id,
                        'equal_doctor_id' => $related_doctor->doctor_id
                    );

                    if($number >= self::$number_of_stored_equals - $equal_doctors_count)
                    {
                        break;
                    }

                    $number++;
                }

                $fields = array(
                    'doctor_id',
                    'equal_doctor_id'
                );

                $equal_doctor_manager = ModelManagerFactory::getByName('equal_doctor');
                $equal_doctor_manager->insertList($equal_doctors_data, $fields);
            }
        }

        public static function updateClinicMetroStationId()
        {
            /**
             * @var CityManager $city_manager
             * @var CityModel $city
             * @var ClinicManager $clinic_manager
             * @var ClinicModel[] $clinics
             * @var ClinicModel $clinic
             */
            $city_manager = ModelManagerFactory::getByName('city');
            $city = $city_manager->getOneByName('Москва');

            $clinic_manager = ModelManagerFactory::getByName('clinic');
            $clinics = $clinic_manager->getListByCityIdWithoutMetroStationId($city->getId());

            if($clinics) {
                foreach($clinics as $clinic) {
                    /**
                     * @var MetroStationToClinicManager $metro_station_to_clinic_manager
                     * @var MetroStationToClinicModel $metro_station_to_clinic
                     */
                    $metro_station_to_clinic_manager = ModelManagerFactory::getByName('metro_station_to_clinic');
                    $metro_station_to_clinic = $metro_station_to_clinic_manager->getOneByClinicId($clinic->getId());

                    if($metro_station_to_clinic) {
                        $clinic->metro_station_id = $metro_station_to_clinic->metro_station_id;
                        $clinic->save();
                    }
                }
            }
        }
	}
