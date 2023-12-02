<?php
    class ClinicApiController extends ApiController
    {
        public $layout = 'ajax';

        public function getCachedMethods()
        {
            return array(
                'getOneById' => array(
                    'clinic:%clinic_id%',
                    'clinic',
                ),
                'search' => array(
                    'clinic:list',
                    'clinic',
                )
            );
        }

        // получение полной информации о клинике
        public function getOneById()
        {
           	 $clinic_id = $this->request('clinic_id');

            if (!$clinic_id)
                ApiHeader::error(ApiRequestErrors::INCORRECT_PARAMS);

            $clinic_manager = new ClinicManager();
            $clinic = $clinic_manager->getOneById($clinic_id);

            if (!$clinic)
                ApiHeader::error(ApiRequestErrors::CLINIC_NOT_EXIST);

            $clinic_images = array();
            foreach ($clinic->images as $image) {
                $clinic_images[] = array(
                    'id' => $image->id,
                    'url' => $image->Path,
                );
            }

            $specialty_doctors = array();
            foreach ($clinic->specialties as $specialty) {
                $doctors = ModelManagerFactory::getByName('doctor_specialty_to_clinic')->getDoctorIdBySpecialtyIdAndClinicId($specialty->getId(), $clinic_id);

                if (count($doctors) != 0) {
                    $specialty_doctors[] = array(
                        'specialty_id' => $specialty->getId(),
                        'specialty_name' => $specialty->name,
                        'doctor_count' => count($doctors),
                    );
                }
            }

            $features = array();
            foreach ($clinic->features as $feature) {
                $features[] = array(
                    'feature_id' => $feature->getId(),
                    'feature_name' => $feature->name,
                    'feature_sort' => $feature->sort,
                );
            }

            $result = array(
                'clinic_id' => $clinic_id,
                'advice_rate' => ($clinic->advice_rate) ? $clinic->advice_rate * 100 : 0,
                'name' => $clinic->name,
                'metro' => array(
                    'metro_station_id' => $clinic->metro_station_id,
                    'name' => $clinic->metro_station_name,
                ),
                'address' => $clinic->address,
                'longitude' => $clinic->longitude,
                'latitude' => $clinic->latitude,
                'images_to_clinic' => $clinic_images,
                'card_image_id' => array(
                    'id' => $clinic->card_image_id,
                    'url' => $clinic->card_image->Path,
                ),
                'about' => $clinic->about,
                'schedule' => array(
                    'start_time_monday' => $clinic->start_time_monday,
                    'end_time_monday' => $clinic->end_time_monday,
                    'start_time_tuesday' => $clinic->start_time_tuesday,
                    'end_time_tuesday' => $clinic->end_time_tuesday,
                    'start_time_wednesday' => $clinic->start_time_wednesday,
                    'end_time_wednesday' => $clinic->end_time_wednesday,
                    'start_time_thursday' => $clinic->start_time_thursday,
                    'end_time_thursday' => $clinic->end_time_thursday,
                    'start_time_friday' => $clinic->start_time_friday,
                    'end_time_friday' => $clinic->end_time_friday,
                    'start_time_saturday' => $clinic->start_time_saturday,
                    'end_time_saturday' => $clinic->end_time_saturday,
                    'start_time_sunday' => $clinic->start_time_sunday,
                    'end_time_sunday' => $clinic->end_time_sunday,
                ),
                'specialty_doctors' => $specialty_doctors,
                'feature' => $features,
            );

            ApiHeader::response($result, $this->e_tag);
        }

        // добавление отзыва клинике
        public function addReview()
        {
            $account_id = $this->request('account_id');
            $visit_id = $this->request('visit_id');
            $clinic_id = $this->request('clinic_id');
            $text = $this->request('text');

            if (!$account_id || !$visit_id || !$clinic_id || !$text)
                ApiHeader::error(ApiRequestErrors::INCORRECT_PARAMS);

            $visit_manager = new VisitManager();
            if (!$visit = $visit_manager->getOneById($visit_id))
                ApiHeader::error(ApiRequestErrors::VISIT_NOT_EXIST);

            if (!$one_visit = $visit_manager->getOneByAccountIdAndClinicId($account_id, $clinic_id))
                ApiHeader::error(ApiRequestErrors::VISIT_WITH_ACCOUNT_AND_CLINIC_NOT_EXIST);

            /**
             * @var VisitRatingManager $visit_rating_manager
             */
            $visit_rating_manager = ModelManagerFactory::getByName('visit_rating');
            $visit_rating = $visit_rating_manager->getOneByVisitId($visit_id);

            $visit_rating->visit_id = $visit_id;
            $visit_rating->account_id = $account_id;
            $visit_rating->clinic_id = $clinic_id;
            $visit_rating->clinic_review_text = $text;

            if (!$visit_rating->save()) {
                $error_code = $visit_rating->getValidator()->getErrorCodes();
                ApiHeader::error($error_code);
            }

            $result = array(
                'clinic_review_id' => $visit_rating->getId()
            );

            ApiHeader::response($result);
        }

        // поиск врачей
        public function search()
        {
            $city_id = $this->request('city_id');
            $specialty_id = $this->request('specialty_id');
            $latitude = $this->request('latitude');
            $longitude = $this->request('longitude');
            $metro_station_id = $this->request('metro_station_id');
            $is_city = $this->request('is_city');
            $radius = $this->request('radius');

            $is_children = (isset($_GET['is_children'])) ? $_GET['is_children'] : false;
            $is_pregnant = (isset($_GET['is_pregnant'])) ? $_GET['is_pregnant'] : false;
            $is_handicapped = (isset($_GET['is_handicapped'])) ? $_GET['is_handicapped'] : false;
            $is_day_and_night = (isset($_GET['is_day_and_night'])) ? $_GET['is_day_and_night'] : false;
            $clinic_id = (isset($_GET['clinic_id'])) ? $_GET['clinic_id'] : false;

            if (!$city_id || !$specialty_id)
                ApiHeader::error(ApiRequestErrors::INCORRECT_PARAMS);

            if (!($latitude && $longitude) && !$metro_station_id && !$is_city && !($latitude && $longitude && $radius))
                ApiHeader::error(ApiRequestErrors::INCORRECT_PARAMS);

            $clinic_search_params = new ClinicSearchParams();
            $clinic_search_params->specialty_id = $specialty_id;
            $clinic_search_params->city_id = $city_id;

            if ($is_children)
                $clinic_search_params->children = 1;

            if ($is_pregnant)
                $clinic_search_params->pregnant = 1;

            if ($is_handicapped)
                $clinic_search_params->handicapped = 1;

            if ($is_day_and_night)
                $clinic_search_params->day_and_night = 1;

            if (!$is_city){
                if ($metro_station_id) {
                    $metro_station_manager = new MetroStationManager();
                    if ($metro_station = $metro_station_manager->getOneById($metro_station_id)) {
                        $latitude = ($metro_station->latitude) ? $metro_station->latitude : false;
                        $longitude = ($metro_station->longitude) ? $metro_station->longitude : false;
                    } else {
                        ApiHeader::error(ApiRequestErrors::METRO_STATION_NOT_EXIST);
                    }
                }

                $clinic_search_params->geo_point = new GeoPoint($latitude, $longitude);
            }

            if ($radius)
                $clinic_search_params->distance = $radius;

            $clinic_search_params->purpose_of_visit_id = (int)$this->request('purpose_of_visit_id');
            $clinic_search_params->page = $this->request('page', 1);
            $clinic_search_params->by_page = $this->request('by_page', 10);

            $clinic_search_algorithm = new ClinicSearchAlgorithm();
            $clinic_search_algorithm->setUseDiscardCriteriaAlgorithm(false);
            $clinics = $clinic_search_algorithm->search($clinic_search_params);

            if (!$clinics)
                ApiHeader::error(ApiRequestErrors::CLINICS_NOT_SEARCH);

            $result = array();

            foreach ($clinics as $clinic) {

                $clinic_specialties = array();

                $id = 0;
                $url = '';

                if ($clinic->card_image_id && file_exists(ABS_ROOT . '/media/upload/' . $clinic->card_image->folder . $clinic->card_image->filename)) {
                    $id = ($clinic->card_image_id) ? $clinic->card_image_id : 0;
                    $url = ($clinic->card_image_id) ? $clinic->card_image->crop(74, 31)->path : '';
                }

                foreach ($clinic->specialties as $specialty) {
                    $clinic_specialties[] = array(
                        'id' => $specialty->getId(),
                        'name' => $specialty->name
                    );
                }

                $schedule = array();
                $days = array('monday','tuesday','wednesday','thursday','friday','saturday','sunday');
                foreach ($days as $day) {
                    $schedule[] = array(
                        'day' => $day,
                        'start_time' => $clinic->{'start_time_'.$day},
                        'end_time' => $clinic->{'end_time_'.$day}
                    );
                }

                $clinic_geopoint = array(
                    'latitude' => 1,
                    'longitude' => 1,
                );
                $clinic_metro = array(
                    'id' => 0,
                    'name' => '',
                );

                $clinic_geopoint['latitude'] = $clinic->latitude;
                $clinic_geopoint['longitude'] = $clinic->longitude;
                $clinic_metro['id'] = $clinic->metro_station_id;
                $clinic_metro['name'] = $clinic->metro_station ? $clinic->metro_station->name : '';

                $result[] = array(
                    'clinic_id' => $clinic->getId(),
                    'name' => $clinic->name ? $clinic->name : '',
                    'metro' => $clinic_metro,
                    'geopoint' => $clinic_geopoint,
                    'rate' => $clinic->rate,
                    'is_best' => $clinic->is_best? 1 : 0,
                    'is_children' => $clinic->is_children,
                    'is_pregnant' => $clinic->is_pregnant,
                    'is_handicapped' => $clinic->is_handicapped,
                    'is_day_and_night' => $clinic->is_day_and_night,
                    'card_image' => array(
                        'id' => $id,
                        'url' => $url,
                    ),
                    'specialty' => $clinic_specialties,
                    'schedule' => $schedule,
                );

            }
            test::dump($result);

            ApiHeader::response($result, $this->e_tag);
        }
    }