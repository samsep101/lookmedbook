<?php

	class DoctorElasticSearchFormatter implements IElasticSearchFormatter
	{
		/**
		 * @param DynamicModel $model
		 *
		 * @return \Elastica\Document
		 */
		public function toElasticSearchView(DynamicModel $model)
		{
			/**
			 * @var DoctorModel $doctor
			 */
			$doctor = $model;
			$result = array();

			if($doctor->specialties)
			{
				$result['specialty_ids'] = array();
				foreach($doctor->specialties as $specialty)
				{
					$result['specialty_ids'][] = $specialty->getId();
				}
			}

			if($doctor->city)
			{
				$result['cities'] = array($doctor->city->getId());
			}

			if($doctor->purposes_of_visit)
			{
				$result['purposes_of_visit'] = array();

				foreach($doctor->purposes_of_visit as $purpose_of_visit)
				{
					$result['purposes_of_visit'][] = $purpose_of_visit->getId();
				}
			}

			$result['id'] = $doctor->id;
			$result['full_name'] = $doctor->full_name;
			$result['is_has_morning_time'] = (bool)$doctor->is_has_morning_time;
			$result['is_has_evening_time'] = (bool)$doctor->is_has_evening_time;
			$result['is_has_weekend_time'] = (bool)$doctor->is_has_weekend_time;
			$result['is_leave_the_house'] = (bool)$doctor->is_leave_the_house;
			$result['is_has_any_time'] = (bool)($doctor->is_has_morning_time || $doctor->is_has_evening_time || $doctor->is_has_weekend_time);
			$result['sex'] = (int)$doctor->sex_id;
			$result['doctor_type'] = (int)$doctor->doctor_type_id;
			$result['is_has_visit_slots'] = (bool)$doctor->is_has_visit_slots;
			$result['reviews_count'] = (int)$doctor->reviews_count;
			if($doctor->actions)
			{
                                $result['actions'] = array();
				foreach($doctor->actions as $action)
				{
					$action_info  = array(
						'id' => $action->getId()
					);

					if($action->date_from)
					{
						$action_info['date_from'] = $action->date_from;
					}
					if($action->date_to)
					{
						$action_info['date_to'] = $action->date_to;
					}
					if($action->clinic_id)
					{
						$action_info['clinic_id'] = $action->clinic_id;
					}
                                        $result['actions'][] = $action_info;                                        
                                }
                        }
                        
			if($doctor->clinics)
			{
				$result['clinics'] = array();

				foreach($doctor->clinics as $clinic)
				{
					$clinic_info  = array(
						'id' => $clinic->getId()
					);

					if($clinic->longitude && $clinic->latitude)
					{
						$clinic_info['geo_point'] = array(
							'lat' => $clinic->latitude,
							'lon' => $clinic->longitude,
						);
					}

					if($clinic->region_id)
					{
						$clinic_info['region'] = $clinic->region_id;
					}

                    if($clinic->street_id)
					{
						$clinic_info['street'] = $clinic->street_id;
					}

                    $clinic_info['metro_station_ids'] = [];

					if ($clinic->metro_stations) {
                        $ids = [];
                        foreach ($clinic->metro_stations as $metro_station) {
                            $ids[$metro_station->id] = $metro_station->id;
                            foreach ($metro_station->same_name_stations as $s) {
                                $ids[$s->id] = $s->id;
                            }
                        }
                        foreach ($ids as $id) {
                            $result['metro_station_ids'][] = ['id' => $id];
                        }
                        unset($ids);
                    }

                    if($clinic->region)
					{
						$clinic_info['district'] = $clinic->region->district_id;
					}

					$specialties  = $doctor->getSpecialtiesListByClinicId($clinic->getId());

					$clinic_info['specialties'] = array();

					foreach($specialties as $specialty)
					{
						$clinic_info['specialties'][] = $specialty->getId();
					}

					$result['clinics'][] = $clinic_info;
				}

			}

			/**
			 * @var UserManager $user_manager
			 */
			$user_manager = ModelManagerFactory::getByName('user');
			$users = $user_manager->getListByDoctorId($doctor->getId());

			$result['registry_users'] = array();

			if($users)
			{
				foreach($users as $user)
				{
					$result['registry_users'][] = $user->getId();
				}
			}

			$result['is_active'] = (bool)$doctor->is_active;
			$result['is_virtual'] = (bool)$doctor->is_virtual;
			$result['is_has_avatar'] = (bool)$doctor->card_image_id;
			$result['is_adult'] = (bool)$doctor->is_adult;
			$result['is_children'] = (bool)$doctor->is_children;
			$result['is_pregnant'] = (bool)$doctor->is_pregnant;
			$result['balls'] = $doctor->balls;
			$result['rate'] = (float)$doctor->rate;
			$result['is_has_active_clinic'] = (bool)($doctor->clinics);
            /**
             * @var ClinicManager $clinic_manager
             */
            $clinic_manager = ModelManagerFactory::getByName('clinic');
            $result['is_has_clinic'] = (bool)$clinic_manager->getListByDoctorId($doctor->getId());

			return new Elastica\Document($doctor->getId(), $result);
		}
	}
