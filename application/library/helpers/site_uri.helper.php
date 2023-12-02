<?php

    class SiteUriHelper
    {
        public static function previousPageIsDoctorSearchPage()
        {
            $preg = str_replace('.', '\.', SITE_URL);
            $preg = str_replace('/', '\/', $preg);
            $preg = '/^' . $preg . '\/doctor(\?.*)?$/ims';

            if(isset($_SERVER['HTTP_REFERER']) and preg_match($preg, $_SERVER['HTTP_REFERER']))
            {
                return TRUE;
            }

            return FALSE;
        }

        public static function previousPageIsClinicSearchPage()
        {
            $preg = str_replace('.', '\.', SITE_URL);
            $preg = str_replace('/', '\/', $preg);
            $preg = '/^' . $preg . '\/clinic(\?.*)?$/ims';

            if(isset($_SERVER['HTTP_REFERER']) && preg_match($preg, $_SERVER['HTTP_REFERER']))
            {
                return TRUE;
            }

            return FALSE;
        }

        public static function previousPageIsDoctorsListInRegsistry()
        {
            if(isset($_SERVER['HTTP_REFERER']) && strpos($_SERVER['HTTP_REFERER'], 'doctor'))
            {
                return TRUE;
            }

            return FALSE;
        }

        public static function previousPageIsProductSearchPage()
        {
            $preg = str_replace('.', '\.', SITE_URL);
            $preg = str_replace('/', '\/', $preg);
            $preg = '/^' . $preg . '\/shop\/catalog/ims';

            if(isset($_SERVER['HTTP_REFERER']) && preg_match($preg, $_SERVER['HTTP_REFERER']))
            {
                return TRUE;
            }

            return FALSE;
        }

        public static function refererFromDoctorPage()
        {
            if(!isset($_SERVER['HTTP_REFERER']))
            {
                return FALSE;
            }
            /*
                        $specialty_string = substr(strrchr($_SERVER['HTTP_REFERER'], '/'), 1);
                        $specialty_parts = explode("?", $specialty_string);
                        $specialty_alias = $specialty_parts[0];
            */
            $url_parts = explode("/", $_SERVER['HTTP_REFERER']);

            if(!isset($url_parts[4]))
                return FALSE;

            $specialty_parts = explode("?", $url_parts[4]);
            $specialty_alias = $specialty_parts[0];

            if(!$specialty_alias)
                return FALSE;

            /**
             * @var SpecialtyManager $specialty_manager
             */

            $specialty_manager = ModelManagerFactory::getByName('specialty');
            $specialty         = $specialty_manager->getOneByAlias($specialty_alias);

            if(!$specialty)
                return FALSE;

            $preg = str_replace('.', '\.', SITE_URL);
            $preg = str_replace('/', '\/', $preg);
            $preg = '/^' . $preg . '\/doctor/';

            if(preg_match($preg, $_SERVER['HTTP_REFERER']))
            {
                return TRUE;
            }

            return FALSE;
        }

        public static function returnToSearchForm()
        {
            $preg = str_replace('.', '\.', SITE_URL);
            $preg = str_replace('/', '\/', $preg);
            $preg = '/^' . $preg . '\/doctor/';

            if(!isset($_SERVER['HTTP_REFERER']) or !preg_match($preg, $_SERVER['HTTP_REFERER']))
            {
                return 0;
            }

            $specialty_manager = ModelManagerFactory::getByName('specialty');
            $doctor_manager    = ModelManagerFactory::getByName('doctor');

//            $url_parts = explode("/", $_SERVER['HTTP_REFERER']);
//            $specialty_parts = explode("?", $url_parts[4]);
//            $specialty_alias = $specialty_parts[0];

            $specialty         = NULL;
            $search_params_key = NULL;
            $link              = NULL;

//            if(count($specialty_parts) > 4 && $specialty_alias) {
//                $search_params_key  = $url_parts[count($url_parts) - 1];
//                $specialty         = $specialty_manager->getOneByAlias($specialty_alias);
//            }

//            if(empty($specialty) || empty($specialty)) {
            $last_params       = isset($_SESSION['last_search_params']) ? $_SESSION['last_search_params'] : null;
            $search_params_key = isset($_SESSION['last_search_params']) ? $_SESSION['last_search_params']->search_params_key : null;
            $specialty         = $last_params ? $specialty_manager->getOneByIdOrAlias($last_params->specialty_id) : null;
//            }

            $count = 0;

            if(!empty($specialty))
            {
                $criteria = $_SESSION['search_params_return'][$specialty->getId()][$search_params_key];

                if(!empty($criteria))
                {
                    $dataDoctors = $doctor_manager->getTotalDoctorsForAllRelatedSpecialties($specialty, $criteria);
                    $count       = $dataDoctors['doctors_total_count'];
                }

                if(!$count)
                {
                    $total_doctors = $doctor_manager->doctorsForRelatedSpecialty($criteria, $specialty->getId());
                    $count         = count($total_doctors);
                }

                $link = self::returnLinkConstruct($criteria, $specialty);
            }

            if($count) $_SESSION['search_params_return'][$specialty->getId()][$search_params_key]->return = 1;

            return array(
                'count' => $count,
                'link'  => $link
            );
        }

        static function returnLinkConstruct($criteria, $specialty)
        {
            $link = '';

            $city           = NULL;
            $district       = NULL;
            $region         = NULL;
            $street         = NULL;
            $metro_station  = NULL;
            $address_object = NULL;

            if($criteria->city_id)
            {
                $city_manager = ModelManagerFactory::getByName('city');
                $city         = $city_manager->getOneByIdOrAlias($criteria->city_id);
            }

            if($criteria->district_id)
            {
                $district_manager = ModelManagerFactory::getByName('district');
                $district         = $district_manager->getOneByIdOrAlias($criteria->district_id);
            }

            if($criteria->region_id)
            {
                $region_manager = ModelManagerFactory::getByName('region');
                $region         = $region_manager->getOneByIdOrAlias($criteria->region_id);
            }

            if($criteria->street_id)
            {
                $street_manager = ModelManagerFactory::getByName('street');
                $street         = $street_manager->getOneByIdOrAlias($criteria->street_id);
            }

            if($criteria->metro_station_id)
            {
                $metro_station_manager = ModelManagerFactory::getByName('metro_station');
                $metro_station         = $metro_station_manager->getOneByIdOrAlias($criteria->metro_station_id);
            }

            if($metro_station)
            {
                $address_object = $metro_station;
            }
            elseif($street)
            {
                $address_object = $street;
            }
            elseif($region)
            {
                $address_object = $region;
            }
            elseif($district)
            {
                $address_object = $district;
            }
            elseif($city)
            {
                $address_object = $city;
            }

            if($address_object)
            {
                $link = SeoLinkViewHelper::getSpecialtyPageLink($specialty, $address_object, 'doctor');
            }

            $get_params = array();

            if($criteria->doctor_type)
            {
                $get_params[] = 'doctor_type=' . $criteria->doctor_type;
            }

            if($criteria->visit_type && $criteria->visit_type != 'clinic')
            {
                $get_params[] = 'visit_type=' . $criteria->visit_type;
            }

            if($criteria->weekend_time)
            {
                $get_params[] = 'weekend_time=' . $criteria->weekend_time;
            }

            if($criteria->doctor_sex_id)
            {
                $get_params[] = 'doctor_sex_id=' . $criteria->doctor_sex_id;
            }

            if($link && count($get_params) > 0)
            {
                $link .= '?' . implode('&', $get_params);
            }

            return $link;
        }

        public static function refererFromClinicPage()
        {
            if(!isset($_SERVER['HTTP_REFERER']))
            {
                return FALSE;
            }

            $specialty_string = substr(strrchr($_SERVER['HTTP_REFERER'], '/'), 1);
            $specialty_parts  = explode("?", $specialty_string);
            $specialty_alias  = $specialty_parts[0];

            if(!$specialty_alias)
                return FALSE;

            if($specialty_alias == 'clinic')
                return TRUE;

            /**
             * @var SpecialtyManager $specialty_manager
             */

            $specialty_manager = ModelManagerFactory::getByName('specialty');
            $specialty         = $specialty_manager->getOneByAlias($specialty_alias);

            if(!$specialty)
                return FALSE;

            $preg = str_replace('.', '\.', SITE_URL);
            $preg = str_replace('/', '\/', $preg);
            $preg = '/^' . $preg . '\/clinic/';

            if(preg_match($preg, $_SERVER['HTTP_REFERER']))
            {
                return TRUE;
            }

            return FALSE;
        }

        public static function previousPageIsClinicPage()
        {
            if(!isset($_SERVER['HTTP_REFERER']))
            {
                return FALSE;
            }

            $referer  = str_replace(SITE_URL . '/', '', $_SERVER['HTTP_REFERER']);
            $elements = explode('/', $referer);

            if($elements[0] == 'clinic')
            {
                return TRUE;
            }

            return FALSE;
        }
    }
