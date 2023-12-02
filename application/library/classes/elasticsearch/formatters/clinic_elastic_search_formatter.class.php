<?php

    class ClinicElasticSearchFormatter implements IElasticSearchFormatter
    {
        /**
         * @param DynamicModel $model
         *
         * @return \Elastica\Document
         */
        public function toElasticSearchView(DynamicModel $model)
        {
            /**
             * @var ClinicModel $model
             */
            $result = array();

            $result['id'] = $model->getId();

            $result['specialties'] = array();
            if($model->specialties)
            {
                foreach($model->specialties as $specialty)
                {
                    $specialty_item                      = array();
                    $specialty_item['id']                = $specialty->getId();
                    $specialty_item['purposes_of_visit'] = array();
                    foreach($specialty->purposes_of_visit as $purpose_of_visit)
                    {
                        $specialty_item['purposes_of_visit'][] = $purpose_of_visit->getId();
                    }

                    $result['specialties'][] = $specialty_item;
                }
            }

            $result['services'] = array();
            if($model->services)
            {
                foreach($model->services AS $sValue)
                {
                    $clinic_service_item       = array();
                    $clinic_service_item['id'] = $sValue->getId();

                    $result['services'][] = $clinic_service_item;
                }
            }

            $result['service_categories'] = [];
            if ($model->service_categories) {
                foreach ($model->service_categories as $service_category) {
                    $result['service_categories'][] = [
                        'id' => $service_category->id,
                    ];
                }
            }

            $result['types'] = array();
            if($model->types)
            {
                foreach($model->types AS $sValue)
                {
                    $clinic_type_item       = array();
                    $clinic_type_item['id'] = $sValue->getId();

                    $result['types'][] = $clinic_type_item;
                }
            }

            $result['specializations'] = array();
            if($model->specializations)
            {
                foreach($model->specializations as $specialization)
                {
                    $result['specializations'][] = $specialization->getId();
                }
            }

            $result['is_children']      = (bool)$model->is_children;
            $result['is_pregnant']      = (bool)$model->is_pregnant;
            $result['is_handicapped']   = (bool)$model->is_handicapped;
            $result['is_day_and_night'] = (bool)$model->is_day_and_night;
            $result['name']             = $model->name;
            $result['address']          = $model->address;
            $result['only_children']    = (bool)$model->only_children;
            $result['is_card_pay']      = (bool)$model->is_card_pay;

            $result['doctors'] = array();
            if($model->doctors)
            {
                foreach($model->doctors as $doctor)
                {
                    $result['doctors'][] = $doctor->getId();
                }
            }

            $result['city']           = $model->city_id ? $model->city_id : NULL;
            $result['is_active']      = (bool)$model->is_active;
            $result['is_not_example'] = TRUE;

            if($model->longitude && $model->latitude)
            {
                $result['geo_point'] = array(
                    'lat' => $model->latitude,
                    'lon' => $model->longitude
                );
            }

            $result['registry_users'] = array();

            if($model->users)
            {
                foreach($model->users as $user)
                {
                    $result['registry_users'][] = $user->getId();
                }
            }

            $result['freelancers'] = array();

            if($model->freelancer)
            {
                $result['freelancers'][] = $model->freelancer->getId();
            }

            $result['is_region'] = (bool)$model->is_region;

            if($model->dt_publish)
            {
                $result['dt_publish'] = strtotime($model->dt_publish);
            }

            if($model->date_publish)
            {
                $result['date_publish'] = date('Y-m-d', strtotime($model->dt_publish));
            }

            if($model->clinic_status_id)
            {
                $result['status'] = $model->clinic_status_id;
            }

            $result['metro_station_ids'] = [];
            if ($model->metro_stations) {
                $ids = [];
                foreach ($model->metro_stations as $metro_station) {
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

            if($model->region_id)
            {
                $result['region'] = $model->region_id;
            }

            if($model->region && $model->region->district_id)
            {
                $result['district'] = $model->region->district_id;
            }

            if($model->street_id)
            {
                $result['street'] = $model->street_id;
            }
            if(empty($result['only_children']))
            {
                foreach($model->types AS $tValue)
                {
                    if($tValue->id == 11)
                    {
                        $result['only_children'] = TRUE;
                    }
                }
            }

            $work_week = array(
                'start_time_monday',
                'end_time_monday',
                'start_time_tuesday',
                'end_time_tuesday',
                'start_time_wednesday',
                'end_time_wednesday',
                'start_time_thursday',
                'end_time_thursday',
                'start_time_friday',
                'end_time_friday',
                'start_time_saturday',
                'end_time_saturday',
                'start_time_sunday',
                'end_time_sunday'
            );

            $twenty_four_hours = true;
            foreach ($work_week as $wwValue) {
                if ($model->$wwValue !== '00:00') {
                    $twenty_four_hours = false;
                    break;
                }
            }

            $result['twenty_four_hours'] = $twenty_four_hours;

            $have_ramp = FALSE;
            if(count($model->features) > 0)
            {
                foreach($model->features AS $fValue)
                {
                    if($fValue->id == 14)
                    {
                        $have_ramp = TRUE;
                    }
                }
            }

            $result['have_ramp'] = $have_ramp;

            $result['balls'] = $model->balls;
            $result['rate'] = (float)$model->rate;
            $result['is_doctor'] = !!($model->type_flags & ClinicModel::TYPE_FLAG_DOCTOR);
            $result['top_phone'] = $model->top_phone;

            return new \Elastica\Document($model->getId(), $result);
        }

    }
