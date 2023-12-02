<?php

class DoctorSearchAlgorithm
{
  private $manager;
  private $good_search_flag = true;
  private $next_page_flag = true;
  private $use_discard_criteria_algorithm = true;
  private $totalDoctorCount = 0;
  /**
   * @var DoctorSearchParams
   */
  private $search_params = null;
  private $isSearchNearestAllowed = false;
  private $resultsComposition = self::RESULTS_ORIGINAL;

  const RESULTS_ORIGINAL = 1;
  const RESULTS_EXTENDED_WITH_NEAREST = 2;
  const RESULTS_ONLY_NEAREST = 3;

  public function __construct()
  {
    $this->manager = new DoctorManager();
  }

  public function setUseDiscardCriteriaAlgorithm($value)
  {
    $this->use_discard_criteria_algorithm = (bool)$value;
  }

  public function getGoodSearchFlag()
  {
    return $this->good_search_flag;
  }

  public function getNextPageFlag()
  {
    return $this->next_page_flag;
  }

  public function getDoctorTotalCount()
  {
      return $this->totalDoctorCount;
  }

  public function getIsSearchNearestAllowed()
  {
    return $this->isSearchNearestAllowed;
  }

  /**
   * @param bool $isSearchNearestAllowed
   */
  public function setIsSearchNearestAllowed($isSearchNearestAllowed)
  {
    $this->isSearchNearestAllowed = $isSearchNearestAllowed;
  }

  /**
   * @return bool|null
   */
  public function getAdditionalDoctors()
  {
      switch ($this->resultsComposition) {
          case self::RESULTS_ORIGINAL:
              return null;
          case self::RESULTS_EXTENDED_WITH_NEAREST:
              return true;
          case self::RESULTS_ONLY_NEAREST:
              return false;
          default:
              throw new RuntimeException("Invalid resultsComposition: $this->resultsComposition");
      }
  }

  public function count(DoctorSearchParams $doctor_search_params)
  {
      $doctor_search_params = $this->prepareSearchParams($doctor_search_params);

      return $this->manager->getCountByDoctorSearchParams($doctor_search_params);
  }

  public function search(DoctorSearchParams $doctor_search_params)
  {
    $doctor_search_params = $this->prepareSearchParams($doctor_search_params);

    $doctors = $this->manager->getListByDoctorSearchParams($doctor_search_params);
    $this->totalDoctorCount = $this->manager->getTotalHits();


    if (count($doctors) == ($doctor_search_params->by_page + 1)) {
      $this->next_page_flag = true;
      unset($doctors[$doctor_search_params->by_page]);
    } else {
      $this->next_page_flag = false;
    }

    $searchNearest = $this->isSearchNearestAllowed
        && $this->totalDoctorCount < 5
        && !$doctor_search_params->geo_point
        && !$doctor_search_params->doctor_name
    ;
    if ($searchNearest) {
        $this->resultsComposition = empty($doctors)
            ? self::RESULTS_ONLY_NEAREST
            : self::RESULTS_EXTENDED_WITH_NEAREST;
        $geoPoint = GeoHelper::getGeoPointBySearchParams($doctor_search_params);
        if ($geoPoint) {
            $doctor_search_params->nearest = true;
            $doctor_search_params->geo_point = $geoPoint;
            $doctor_search_params->metro_station_id = null;
            $doctor_search_params->street_id = null;
            $doctor_search_params->region_id = null;
            $doctor_search_params->district_id = null;
            $doctor_search_params->sort_salt = null;
            $doctor_search_params->page = 1;
            $doctor_search_params->by_page = 10;
            $self = new self();
            $doctors = $self->search($doctor_search_params);
            $this->totalDoctorCount = count($doctors);
        }
    }

    return $doctors;
  }

  private function prepareSearchParams(DoctorSearchParams $doctor_search_params)
  {
      $this->search_params = $doctor_search_params;
      $this->search_params->is_active = 1;
      $this->search_params->is_has_active_clinic = true;
      $this->search_params->sort_by = 'balls';

      if ($doctor_search_params->specialty_id || $doctor_search_params->purpose_of_visit_id) {
          $specialty_manager = new SpecialtyManager();
          $specialty = $specialty_manager->getOneByIdOrAlias($doctor_search_params->specialty_id);

          $specialities = array_map(function ($v) {
              return $v->id;
          }, array_filter($this->manager->getRelatedSpecialties($specialty)
          ));

          // todo получение всех специальностей, пока так, затем можно переделать на более адекватный код
          $specialities[] = $doctor_search_params->specialty_id;

          $suitable_specialties = $specialty_manager->getSuitableListBySpecialtyIdAndPurposeOfVisitId($specialities, $doctor_search_params->purpose_of_visit_id);

          if ($suitable_specialties) {
              foreach ($suitable_specialties as $suitable_specialty) {
                  $doctor_search_params->suitable_specialties_ids[] = $suitable_specialty->getId();
              }
          }
          $doctor_search_params->suitable_specialties_ids = array_unique(array_merge($doctor_search_params->suitable_specialties_ids, $specialities));
      }

      $doctor_search_params->get_extra_item = !$doctor_search_params->nearest;

      return $doctor_search_params;
  }

  private function addDoctorSearchQueryTask(DoctorSearchParams $doctor_search_params)
  {
    // Добавление задания для построения полного списка врачей по данному доктору
    $hash = $doctor_search_params->getParamsHash();

    $doctor_search_query_task_manager = new DoctorSearchQueryTaskManager();

    if (!$doctor_search_query_task_manager->getOneByHash($hash)) {
      $doctor_search_query_task_model = new DoctorSearchQueryTaskModel();
      $doctor_search_query_task_model->sql = serialize($doctor_search_params);
      $doctor_search_query_task_model->task_status_id = TaskStatusModel::IN_QUEUE;
      $doctor_search_query_task_model->hash = $hash;
      $doctor_search_query_task_model->save();
    }
  }

  private function removeCriteriaAlgorithm(DoctorSearchParams $doctor_search_params)
  {
    $doctors = array();

    while (!$doctors) {
      if ($doctor_search_params->geo_point && $doctor_search_params->distance < 64000) {
        $doctor_search_params->distance *= 2;
        $doctors = $this->manager->getListByDoctorSearchParams($doctor_search_params);
        continue;
      }

      if ($doctor_search_params->distance >= 64000) {
        $doctor_search_params->geo_point = null;
        $doctor_search_params->distance = null;
        $doctors = $this->manager->getListByDoctorSearchParams($doctor_search_params);
        $doctor_search_params->metro_station_name = null;
        continue;
      }

      $this->good_search_flag = false;

      if (!$this->use_discard_criteria_algorithm)
        break;

      if ($doctor_search_params->district_id) {
        $doctor_search_params->district_id = null;
        $doctors = $this->manager->getListByDoctorSearchParams($doctor_search_params);
        continue;
      }

      if ($doctor_search_params->region_id) {
        $doctor_search_params->region_id = null;
        $doctors = $this->manager->getListByDoctorSearchParams($doctor_search_params);
        continue;
      }

      if ($doctor_search_params->street_id) {
        $doctor_search_params->street_id = null;
        $doctors = $this->manager->getListByDoctorSearchParams($doctor_search_params);
        continue;
      }

      if ($doctor_search_params->visit_type == 'home') {
        $doctor_search_params->visit_type = 'clinic';
        $doctors = $this->manager->getListByDoctorSearchParams($doctor_search_params);
        continue;
      }

      if ($doctor_search_params->doctor_sex_id) {
        $doctor_search_params->doctor_sex_id = null;
        $doctors = $this->manager->getListByDoctorSearchParams($doctor_search_params);
        continue;
      }

      if ($doctor_search_params->doctor_name) {
        $doctor_search_params->doctor_name = null;
        $doctors = $this->manager->getListByDoctorSearchParams($doctor_search_params);
        continue;
      }

      if ($doctor_search_params->weekend_time) {
        $doctor_search_params->weekend_time = null;
        $doctors = $this->manager->getListByDoctorSearchParams($doctor_search_params);
        continue;
      }

      if ($doctor_search_params->morning_time) {
        $doctor_search_params->morning_time = null;
        $doctors = $this->manager->getListByDoctorSearchParams($doctor_search_params);
        continue;
      }

      if ($doctor_search_params->evening_time) {
        $doctor_search_params->evening_time = null;
        $doctors = $this->manager->getListByDoctorSearchParams($doctor_search_params);
        continue;
      }

      if ($doctor_search_params->doctor_type) {
        $doctor_search_params->doctor_type = null;
        $doctors = $this->manager->getListByDoctorSearchParams($doctor_search_params);
        continue;
      }

      if ($doctor_search_params->purpose_of_visit_id) {
        $doctor_search_params->purpose_of_visit_id = null;
        $doctors = $this->manager->getListByDoctorSearchParams($doctor_search_params);
        continue;
      }

      if ($doctor_search_params->specialty_id) {
        $doctor_search_params->specialty_id = null;
        $doctors = $this->manager->getListByDoctorSearchParams($doctor_search_params);
        continue;
      }


      break;
    }

    return $doctors;
  }
}
