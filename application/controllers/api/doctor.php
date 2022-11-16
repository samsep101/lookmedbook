<?php

class DoctorApiController extends ApiController
{
  public $layout = 'ajax';

  public function getCachedMethods()
  {
    return array(
      'getOneById' => array(
        'tags' => array(
          'doctor:%doctor_id%',
          'doctor',
        ),
      ),
      'search' => array(
        'tags' => array(
          'doctor:list',
          'doctor',
        ),
      ),
      'searchByMetro' => array(
        'tags' => array(
          'doctor:list',
          'doctor',
        ),
      ),
      'searchByClinicId' => array(
        'tags' => array(
          'doctor:list',
          'doctor',
        ),
      ),
      'getVisitSlots' => array(
        'tags' => array(
          'doctor:%doctor_id%',
          'clinic:%clinic_id%',
          'doctor:list',
          'schedule',
          'schedule:list'
        )
      )
    );
  }


  // получение полной информации о враче
  public function getOneById()
  {
    $doctor_id = $this->request('doctor_id');

    if (!$doctor_id)
      ApiHeader::error(ApiRequestErrors::INCORRECT_PARAMS);

    $doctor_manager = new DoctorManager();
    $doctor = $doctor_manager->getOneById($doctor_id);

    if (!$doctor)
      ApiHeader::error(ApiRequestErrors::DOCTOR_NOT_EXIST);

    $doctor_images = array();
    if (count($doctor->images)) {
      foreach ($doctor->images as $image) {
        $doctor_images[] = array(
          'id' => ($image->id) ? $image->id : 0,
          'url' => $image->resize(658, 279)->path,
        );
      }
    }

    $doctor_type_manager = new DoctorTypeManager();
    $doctor_type = $doctor_type_manager->getOneById($doctor->doctor_type_id);

    $specialties = array();
    if (count($doctor->specialties)) {
      foreach ($doctor->specialties as $specialty) {
        $specialties[] = array(
          'id' => $specialty->id,
          'name' => $specialty->name,
          'clinic_id' => ($specialty->clinic_id) ? $specialty->clinic_id : $doctor->clinic->id,
        );
      }
    }

    $clinics = array();
    if (count($doctor->clinics)) {
      foreach ($doctor->clinics as $clinic) {
        $clinics[] = array(
          'id' => $clinic->id,
          'name' => $clinic->name,
          'address' => $clinic->address,
          'metro' => array(
            'metro_station_id' => $clinic->metro_station_id,
            'name' => ($clinic->metro_station_id) ? $clinic->metro_station->name : '',
          ),
        );
      }
    }

    $high_educations = array();
    if (count($doctor->educations)) {
      foreach ($doctor->educations as $doctor_education) {
        $university = ModelManagerFactory::getByName('university')->getOneById($doctor_education->university_id);
        $university_specialty = ModelManagerFactory::getByName('specialty')->getOneById($doctor_education->specialty_id);

        $high_educations[] = array(
          'end_year' => $doctor_education->end_year,
          'university' => array(
            'id' => $university->getId(),
            'name' => $university->name,
          ),
          'specialty' => array(
            'id' => ($university_specialty) ? $university_specialty->getId() : '',
            'name' => ($university_specialty) ? $university_specialty->name : '',
          ),
        );
      }
    }

    $certificates = array();
    if (count($doctor->certificates)) {
      foreach ($doctor->certificates as $certificate) {
        $university = ModelManagerFactory::getByName('university')->getOneById($certificate->university_id);
        $specialty = ModelManagerFactory::getByName('specialty')->getOneById($certificate->specialty_id);

        $certificates[] = array(
          'id' => $certificate->id,
          'university' => array(
            'id' => ($university) ? $university->id : '',
            'name' => ($university) ? $university->name : '',
          ),
          'specialty' => array(
            'id' => ($specialty) ? $specialty->id : '',
            'name' => ($specialty) ? $specialty->name : '',
          ),
          'date' => $certificate->date,
          'duration' => $certificate->duration
        );
      }
    }

    $academic_degrees = array();
    if (count($doctor->academic_degrees)) {
      foreach ($doctor->academic_degrees as $degree) {
        $academic_degree = ModelManagerFactory::getByName('doctor_academic_degree')->getOneById($degree->doctor_academic_degree_id);
        $academic_degrees[] = array(
          'id' => ($degree->doctor_academic_degree_id) ? $degree->doctor_academic_degree_id : 0,
          'name' => ($academic_degree->name) ? $academic_degree->name : '',
          'description' => ($academic_degree->description) ? $academic_degree->description : '',
          'dt' => @strtotime($degree->dt),
        );
      }
    }

    $purpose = array();
    $purpose_of_visit_to_doctor_manager = new PurposeOfVisitToDoctorManager();
    if (count($purposes_of_visit_to_doctor = $purpose_of_visit_to_doctor_manager->getListByDoctorId($doctor_id))) {
      foreach ($purposes_of_visit_to_doctor as $purpose_of_visit) {
        if ($purpose_of_visit->clinic_id) {
          $purpose[] = array(
            'clinic_id' => $purpose_of_visit->clinic_id,
            'specialty_id' => $purpose_of_visit->specialty_id,
            'purpose_of_visit_id' => $purpose_of_visit->purpose_of_visit_id,
            'name' => $purpose_of_visit->purpose_of_visit->name,
            'visit_price' => ($purpose_of_visit->price) ? (int)$purpose_of_visit->price : 0,
          );
        }
      }
    }

    $doctor_review_manager = new DoctorReviewManager();
    $review_count = $doctor_review_manager->getCountConfirmedListByDoctorId($doctor_id);

    $result = array(
      'id' => $doctor_id,
      'first_name' => ($doctor->first_name) ? $doctor->first_name : '',
      'last_name' => $doctor->last_name ? $doctor->last_name : '',
      'second_name' => $doctor->second_name ? $doctor->second_name : '',
      'full_lower_name' => $doctor->full_lower_name,
      'sex' => array(
        'id' => ($doctor->sex_id) ? (int)$doctor->sex_id : 0,
        'name' => (($doctor->sex_id) ? true : false) ? (($doctor->sex_id == 1) ? 'Мужской' : 'Женский') : '',
      ),
      'images_to_doctor' => $doctor_images,
      'card_image' => array(
        'id' => ($doctor->card_image_id) ? $doctor->card_image_id : 0,
        'url' => ($doctor->card_image->path) ? $doctor->card_image->path : '',
      ),
      'about' => ($doctor->about) ? $doctor->about : '',
      'doctor_type' => array(
        'id' => ($doctor_type) ? $doctor_type->getId() : 0,
        'name' => ($doctor_type) ? $doctor_type->name : '',
      ),
      'rate' => (int)$doctor->rate,
      'is_best' => $doctor->is_best ? 1 : 0,
      'review_count' => (int)$review_count,
      'advice_rate' => ($doctor->advice_rate) ? $doctor->advice_rate * 100 : 0,
      'cabinet_rate' => ($doctor->cabinet_rate) ? $doctor->cabinet_rate : 0,
      'waiting_time_rate' => ($doctor->waiting_time_rate) ? $doctor->waiting_time_rate : 0,
      'relationship_rate' => ($doctor->relationship_rate) ? $doctor->relationship_rate : 0,
      'value_for_money_rate' => ($doctor->value_for_money_rate) ? $doctor->value_for_money_rate : 0,
      'diagnosis_is_clear_rate' => ($doctor->diagnosis_is_clear_rate) ? $doctor->diagnosis_is_clear_rate : 0,
      'balls' => ($doctor->balls) ? (int)$doctor->balls : 0,
      'is_has_morning_time' => (int)$doctor->is_has_morning_time,
      'is_has_evening_time' => (int)$doctor->is_has_evening_time,
      'is_has_weekend_time' => (int)$doctor->is_has_weekend_time,
      'is_leave_the_house' => ($doctor->is_leave_the_house) ? $doctor->is_leave_the_house : 0,
      'specialty' => $specialties,
      'clinic' => $clinics,
      'high_education' => $high_educations,
      'certificate' => $certificates,
      'academic_degree' => $academic_degrees,
      'purpose' => $purpose,
    );

    ApiHeader::response($result, $this->e_tag);
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

    $is_adult = (isset($_GET['is_adult'])) ? $_GET['is_adult'] : false;
    $is_children = (isset($_GET['is_children'])) ? $_GET['is_children'] : false;
    $is_pregnant = (isset($_GET['is_pregnant'])) ? $_GET['is_pregnant'] : false;
    $clinic_id = (isset($_GET['clinic_id'])) ? $_GET['clinic_id'] : false;

    if (!$city_id || !$specialty_id)
      ApiHeader::error(ApiRequestErrors::INCORRECT_PARAMS);

    if (!($latitude && $longitude) && !$metro_station_id && !$is_city && !($latitude && $longitude && $radius))
      ApiHeader::error(ApiRequestErrors::INCORRECT_PARAMS);

    $doctor_type_id = $this->request('doctor_type_id', 0);

    $doctor_search_params = new DoctorSearchParams();
    $doctor_search_params->specialty_id = $specialty_id;
    $doctor_search_params->city_id = $city_id;
    $doctor_search_params->has_visit_slots = 1;

    if ($metro_station_id)
      $doctor_search_params->for_api = 1;

    if ($is_pregnant)
      $doctor_search_params->doctor_type = 'pregnant';
    elseif ($is_children)
      $doctor_search_params->doctor_type = 'children';
    else
      $doctor_search_params->doctor_type = 'adult';

    if (!$is_city) {
      if ($metro_station_id) {
        $metro_station_manager = new MetroStationManager();
        if ($metro_station = $metro_station_manager->getOneById($metro_station_id)) {
          $latitude = ($metro_station->latitude) ? $metro_station->latitude : false;
          $longitude = ($metro_station->longitude) ? $metro_station->longitude : false;
        } else {
          ApiHeader::error(ApiRequestErrors::METRO_STATION_NOT_EXIST);
        }
      }

      $doctor_search_params->geo_point = new GeoPoint($latitude, $longitude);
    }

    if ($radius)
      $doctor_search_params->distance = $radius;

    $doctor_search_params->purpose_of_visit_id = (int)$this->request('purpose_of_visit_id');
    $doctor_search_params->page = $this->request('page', 1);
    $doctor_search_params->by_page = $this->request('by_page', 10);

    $doctor_search_algorithm = new DoctorSearchAlgorithm();
    $doctor_search_algorithm->setUseDiscardCriteriaAlgorithm(false);
    $doctors = $doctor_search_algorithm->search($doctor_search_params);

    if (!$doctors)
      ApiHeader::error(ApiRequestErrors::DOCTORS_NOT_SEARCH);

    $result = array();

    $doctor_specialty_to_clinic_manager = new DoctorSpecialtyToClinicManager();
    $doctor_to_clinic_manager = new DoctorToClinicManager();
    $doctor_academic_degree_manager = new DoctorAcademicDegreeToDoctorManager();

    foreach ($doctors as $doctor) {

      $doctor_card_image = array();
      $doctor_specialties = array();
      $clinic = array();
      $doctor_academic_degree = array();

      $id = 0;
      $url = '';

      if ($doctor->card_image_id && file_exists(ABS_ROOT . '/media/upload/' . $doctor->card_image->folder . $doctor->card_image->filename)) {
        $id = ($doctor->card_image_id) ? $doctor->card_image_id : 0;
        $url = ($doctor->card_image_id) ? $doctor->card_image->crop(74, 111)->path : '';
      }

      if ($doctor_specialties_to_clinic = $doctor_specialty_to_clinic_manager->getListByDoctorId($doctor->getId())) {
        foreach ($doctor_specialties_to_clinic as $doctor_specialty_to_clinic) {
          $doctor_specialties[] = array(
            'id' => $doctor_specialty_to_clinic->specialty_id,
            'name' => $doctor_specialty_to_clinic->specialty->name,
            'clinic_id' => $doctor_specialty_to_clinic->clinic_id,
          );
        }
      }
      $doctor_to_clinic = $doctor_to_clinic_manager->getOneByDoctorId($doctor->getId());

      $distance = 99999999;
      $clinic_id = 0;
      $clinic_name = '';
      $clinic_address = '';
      $clinic_rate = 5;
      $clinic_is_best = 0;
      $clinic_geopoint = array(
        'latitude' => 1,
        'longitude' => 1,
      );
      $clinic_metro = array(
        'id' => 0,
        'name' => '',
      );

      foreach ($doctor->clinics as $clinic) {
        if ($distance > GeoPoint::getDistance($latitude, $longitude, $clinic->latitude, $clinic->longitude)) {
          $distance = GeoPoint::getDistance($latitude, $longitude, $clinic->latitude, $clinic->longitude);
          $clinic_id = $clinic->id;
          $clinic_name = $clinic->name;
          $clinic_address = $clinic->address;
          $clinic_geopoint['latitude'] = $clinic->latitude;
          $clinic_geopoint['longitude'] = $clinic->longitude;
          $clinic_metro['id'] = $clinic->metro_station_id;
          $clinic_metro['name'] = $clinic->metro_station ? $clinic->metro_station->name : '';
          $clinic_rate = $clinic->rate;
          $clinic_is_best = $clinic->is_best ? 1 : 0;
        }
      }

      if ($doctor_academic_degrees = $doctor_academic_degree_manager->getListByDoctorId($doctor->getId())) {
        foreach ($doctor_academic_degrees as $doctor_academic) {
          $doctor_academic_degree[] = array(
            'id' => $doctor_academic->doctor_academic_degree_id,
            'name' => ($doctor_academic->doctor_academic_degree_id) ? $doctor_academic->doctor_academic_degree->name : '',
            'description' => ($doctor_academic->doctor_academic_degree_id) ? $doctor_academic->doctor_academic_degree->description : '',
            'dt' => @strtotime($doctor_academic->dt),
          );
        }
      }

      $purpose = array();
      $purpose_of_visit_to_doctor_manager = new PurposeOfVisitToDoctorManager();
      if (count($purposes_of_visit_to_doctor = $purpose_of_visit_to_doctor_manager->getListByDoctorId($doctor->getId()))) {
        foreach ($purposes_of_visit_to_doctor as $purpose_of_visit) {
          $purpose[] = array(
            'clinic_id' => $purpose_of_visit->clinic_id,
            'specialty_id' => $purpose_of_visit->specialty_id,
            'purpose_of_visit_id' => $purpose_of_visit->purpose_of_visit_id,
            'name' => $purpose_of_visit->purpose_of_visit->name,
            'visit_price' => ($purpose_of_visit->price) ? (int)$purpose_of_visit->price : 0,
          );
        }
      }

      $result[] = array(
        'doctor_id' => $doctor->getId(),
        'first_name' => $doctor->first_name ? $doctor->first_name : '',
        'last_name' => $doctor->last_name ? $doctor->last_name : '',
        'rate' => $doctor->rate,
        'is_best' => $doctor->is_best ? 1 : 0,
        'sex' => array(
          'id' => $doctor->sex_id,
          'name' => (($doctor->sex_id) ? true : false) ? (($doctor->sex_id == 1) ? 'Мужской' : 'Женский') : '',
        ),
        'is_has_weekend_time' => $doctor->is_has_weekend_time,
        'card_image' => array(
          'id' => $id,
          'url' => $url,
        ),
        'specialty' => $doctor_specialties,
        'clinic' => array(
          'id' => $clinic_id,
          'name' => $clinic_name,
          'address' => $clinic_address,
          'rate' => $clinic_rate,
          'is_best' => $clinic_is_best,
          'geopoint' => $clinic_geopoint,
          'metro' => $clinic_metro,
        ),
        'academic_degree' => $doctor_academic_degree,
        'distance' => ($distance != 99999999) ? $distance : '',
        'purpose' => $purpose,
      );

    }
    //Test::dump($result);

    ApiHeader::response($result, $this->e_tag);
  }

  // поиск врачей с результатом в виде списка станций метро с количеством врачей, привязанных к данной станции метро
  public function searchByMetro()
  {
    $city_id = $this->request('city_id');
    $specialty_id = $this->request('specialty_id');

    $is_adult = (isset($_GET['is_adult'])) ? $_GET['is_adult'] : false;
    $is_children = (isset($_GET['is_children'])) ? $_GET['is_children'] : false;
    $is_pregnant = (isset($_GET['is_pregnant'])) ? $_GET['is_pregnant'] : false;

    if (!$city_id || !$specialty_id)
      ApiHeader::error(ApiRequestErrors::INCORRECT_PARAMS);

    $doctor_type_id = $this->request('doctor_type_id', 0);

    $doctor_search_params = new DoctorSearchParams();
    $doctor_search_params->specialty_id = $specialty_id;
    $doctor_search_params->city_id = $city_id;

    if ($is_pregnant)
      $doctor_search_params->doctor_type = 'pregnant';
    elseif ($is_children)
      $doctor_search_params->doctor_type = 'children';
    else
      $doctor_search_params->doctor_type = 'adult';

    $doctor_search_params->purpose_of_visit_id = (int)$this->request('purpose_of_visit_id', 0);
    $doctor_search_params->page = $this->request('page');
    $doctor_search_params->by_page = $this->request('by_page');

    $doctor_search_params->clinic_is_active = true;
    $doctor_search_params->has_visit_slots = 1;

    $doctor_search_algorithm = new DoctorSearchAlgorithm();
    $doctors = $doctor_search_algorithm->search($doctor_search_params);

    if (!$doctors)
      ApiHeader::error(ApiRequestErrors::DOCTORS_NOT_SEARCH);

    $result = array();

    foreach ($doctors as $doctor) {

      foreach ($doctor->clinics as $clinic) {
        $metro_stations_id[] = $clinic->metro_station->id;
      }

      $metro_stations_id = array_unique($metro_stations_id);
    }

    foreach ($metro_stations_id as $metro_station_id) {
      $metro_station_manager = new MetroStationManager();
      $metro_station = $metro_station_manager->getOneById($metro_station_id);

      $doctor_manager = new DoctorManager();
      $count_doctors = $doctor_manager->getCountDoctorsInDoctorsListByMetroStationId($metro_station->getId(), $doctors);

      $response = array(
        'metro_station_id' => $metro_station->getId(),
        'name' => $metro_station->name,
        'doctor_count' => $count_doctors,
      );

      $result[] = $response;
    }

    ApiHeader::response($result, $this->e_tag);
  }

  // добавление отзыва врачу
  public function addReview()
  {
    $account_id = $this->request('account_id');
    $visit_id = $this->request('visit_id');
    $doctor_id = $this->request('doctor_id');
    $text = $this->request('text');

    if (!$account_id || !$visit_id || !$doctor_id || !$text)
      ApiHeader::error(ApiRequestErrors::INCORRECT_PARAMS);

    $visit_manager = new VisitManager();
    if (!$visit = $visit_manager->getOneById($visit_id))
      ApiHeader::error(ApiRequestErrors::VISIT_NOT_EXIST);

    if (!$one_visit = $visit_manager->getOneByAccountIdAndDoctorId($account_id, $doctor_id))
      ApiHeader::error(ApiRequestErrors::VISIT_WITH_ACCOUNT_AND_DOCTOR_NOT_EXIST);


    /**
     * @var VisitRatingManager $visit_rating_manager
     */

    $visit_rating_manager = ModelManagerFactory::getByName('visit_rating');

    $visit_rating = $visit_rating_manager->getOneByVisitId($visit_id);

    $visit_rating->account_id = $account_id;
    $visit_rating->doctor_id = $doctor_id;
    $visit_rating->dt = date('Y-m-d H:i:s');
    $visit_rating->doctor_review_text = $text;
    $visit_rating->is_confirmed = 0;

    if (!$visit_rating->save()) {
      $error_code = $visit_rating->getValidator()->getErrorCodes();
      ApiHeader::error($error_code);
    }

    $result = array(
      'doctor_review_id' => $visit_rating->getId()
    );

    ApiHeader::response($result, $this->e_tag);
  }

  public function searchByClinicId()
  {
    $specialty_id = $this->request('specialty_id');
    $clinic_id = $this->request('clinic_id');

    $is_adult = (isset($_GET['is_adult'])) ? $_GET['is_adult'] : false;
    $is_children = (isset($_GET['is_children'])) ? $_GET['is_children'] : false;
    $is_pregnant = (isset($_GET['is_pregnant'])) ? $_GET['is_pregnant'] : false;

    if (!$clinic_id || !$specialty_id)
      ApiHeader::error(ApiRequestErrors::INCORRECT_PARAMS);

    $doctor_search_params = new DoctorSearchParams();
    $doctor_search_params->specialty_id = $specialty_id;
    $doctor_search_params->clinic_id = $clinic_id;

    $doctor_search_params->purpose_of_visit_id = (int)$this->request('purpose_of_visit_id');
    $doctor_search_params->doctor_type = $this->request('doctor_type_id', 0);

    if ($is_pregnant) $doctor_search_params->doctor_type = 'pregnant';
    elseif ($is_children) $doctor_search_params->doctor_type = 'children';
    else $doctor_search_params->doctor_type = 'adult';

    $doctor_search_params->page = $this->request('page', 1);
    $doctor_search_params->by_page = $this->request('by_page');
    $doctor_search_params->has_visit_slots = 1;


    $doctor_search_algorithm = new DoctorSearchAlgorithm();
    $doctor_search_algorithm->setUseDiscardCriteriaAlgorithm(false);
    $doctors = $doctor_search_algorithm->search($doctor_search_params);

    if (!$doctors)
      ApiHeader::error(ApiRequestErrors::DOCTORS_NOT_SEARCH);

    $result = array();

    $doctor_specialty_to_clinic_manager = new DoctorSpecialtyToClinicManager();
    $doctor_to_clinic_manager = new DoctorToClinicManager();
    $doctor_academic_degree_manager = new DoctorAcademicDegreeToDoctorManager();

    foreach ($doctors as $doctor) {
      $doctor_card_image = array();
      $doctor_specialties = array();
      $clinic = array();
      $doctor_academic_degree = array();
      if ($doctor->card_image_id && file_exists(ABS_ROOT . '/media/upload/' . $doctor->card_image->folder . $doctor->card_image->filename)) {
        $doctor_card_image['id'] = ($doctor->card_image_id) ? $doctor->card_image_id : '';
        $doctor_card_image['url'] = ($doctor->card_image_id) ? $doctor->card_image->crop(74, 111)->path : '';

      }

      if ($doctor_specialties_to_clinic = $doctor_specialty_to_clinic_manager->getListByDoctorId($doctor->getId())) {

        foreach ($doctor_specialties_to_clinic as $doctor_specialty_to_clinic) {

          $doctor_specialties[] = array(
            'id' => $doctor_specialty_to_clinic->specialty_id,
            'name' => $doctor_specialty_to_clinic->specialty->name,
            'clinic_id' => $doctor_specialty_to_clinic->clinic_id,
          );
        }
      }

      $doctor_to_clinic = $doctor_to_clinic_manager->getOneByDoctorId($doctor->getId());
      if ($doctor_to_clinic) {
        $clinic['id'] = $doctor_to_clinic->clinic_id;
        $clinic['name'] = ($doctor_to_clinic->clinic_id) ? $doctor_to_clinic->clinic->name : '';

        $clinic['address'] = ($doctor_to_clinic->clinic_id) ? $doctor_to_clinic->clinic->address : '';
        $clinic['geopoint'] = array(
          'longtitude' => ($doctor_to_clinic->clinic_id) ? $doctor_to_clinic->clinic->longitude : '',
          'latitude ' => ($doctor_to_clinic->clinic_id) ? $doctor_to_clinic->clinic->latitude : '',
        );
        $clinic['metro'] = array(
          'id' => ($doctor_to_clinic->clinic_id) ? $doctor_to_clinic->clinic->metro_station_id : '',
          'name' => ($doctor_to_clinic->clinic_id) ? $doctor_to_clinic->clinic->metro_station->name : '',
        );
      }

      if ($doctor_academic_degrees = $doctor_academic_degree_manager->getListByDoctorId($doctor->getId())) {

        foreach ($doctor_academic_degrees as $doctor_academic) {
          $doctor_academic_degree[] = array(
            'id' => $doctor_academic->doctor_academic_degree_id,
            'name' => ($doctor_academic->doctor_academic_degree_id) ? $doctor_academic->doctor_academic_degree->name : '',
            'description' => ($doctor_academic->doctor_academic_degree_id) ? $doctor_academic->doctor_academic_degree->description : '',
            'dt' => @strtotime($doctor_academic->dt),
          );
        }

      }

      $purpose = array();
      $purpose_of_visit_to_doctor_manager = new PurposeOfVisitToDoctorManager();
      if (count($purposes_of_visit_to_doctor = $purpose_of_visit_to_doctor_manager->getListByDoctorId($doctor->getId()))) {
        foreach ($purposes_of_visit_to_doctor as $purpose_of_visit) {

          $purpose[] = array(
            'clinic_id' => $purpose_of_visit->clinic_id,
            'specialty_id' => $purpose_of_visit->specialty_id,
            'purpose_of_visit_id' => $purpose_of_visit->purpose_of_visit_id,
            'name' => ($purpose_of_visit->purpose_of_visit_id) ? $purpose_of_visit->purpose_of_visit->name : '',
            'visit_price' => ($purpose_of_visit->price) ? (int)$purpose_of_visit->price : 0,
          );
        }
      }

      $result[] = array(
        'doctor_id' => $doctor->getId(),
        'first_name' => $doctor->first_name ? $doctor->first_name : '',
        'last_name' => $doctor->last_name ? $doctor->last_name : '',
        'rate' => $doctor->rate,
        'is_best' => $doctor->is_best ? 1 : 0,
        'sex' => array(
          'id' => $doctor->sex_id,
          'name' => (($doctor->sex_id) ? true : false) ? (($doctor->sex_id == 1) ? 'Мужской' : 'Женский') : '',
        ),
        'is_has_weekend_time' => $doctor->is_has_weekend_time,
        'card_image' => $doctor_card_image,
        'specialty' => $doctor_specialties,
        'clinic' => $clinic,

        'academic_degree' => $doctor_academic_degree,
        'purpose' => $purpose,
      );

    }

    ApiHeader::response($result, $this->e_tag);
  }

  public function getVisitSlots()
  {
    $doctor_id = $this->request('doctor_id');
    $clinic_id = $this->request('clinic_id');

    if (!$doctor_id || !$clinic_id)
      ApiHeader::error(ApiRequestErrors::INCORRECT_PARAMS);

    $schedule_manager = ModelManagerFactory::getByName('schedule');
    $slots = $schedule_manager->getListByDoctorIdAndClinicIdAndTodayDate($doctor_id, $clinic_id);
    $specialty_ids = $schedule_manager->getSpecialtyIdByDoctorIdAndClinicId($doctor_id, $clinic_id);

    if (!$slots)
      ApiHeader::error(ApiRequestErrors::SLOTS_NOT_EXIST);

    $result = array();

    foreach ($specialty_ids as $specialty_id) {
      $specialty_name = ModelManagerFactory::getByName('specialty')->getOneById($specialty_id['specialty_id'])->name;
      $result[$specialty_id['specialty_id']]['specialty_name'] = $specialty_name;

      foreach ($slots as $slot) {
        if ($slot->specialty_id == $specialty_id['specialty_id']) {
          $result[$specialty_id['specialty_id']]['slots'][] = array(
            'id' => $slot->getId(),
            'start_time' => @strtotime($slot->dt_start),
            'end_time' => @strtotime($slot->dt_end)
          );
        }
      }
    }

    ApiHeader::response($result, $this->e_tag);
  }

}
