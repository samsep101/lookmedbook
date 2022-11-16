<?php

class ClinicSearchAlgorithm
{
  private $manager;
  private $good_search_flag = true;
  private $next_page_flag = true;
  private $hash = '';
  private $use_discard_criteria_algorithm = true;
  private $totalClinicCount = 0;
  private $isSearchNearestAllowed = false;
  private $resultsComposition = self::RESULTS_ORIGINAL;

  const RESULTS_ORIGINAL = 1;
  const RESULTS_EXTENDED_WITH_NEAREST = 2;
  const RESULTS_ONLY_NEAREST = 3;

  /**
   * @var ClinicSearchParams
   */
  private $search_params = null;

  public function __construct()
  {
    $this->manager = new ClinicManager();
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
  public function getAdditionalClinics()
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

  public function getHash()
  {
    return $this->hash;
  }

  public function count(ClinicSearchParams $params)
  {
      /** @var ClinicManager $clinic_manager */
      $clinic_manager = ModelManagerFactory::getByName('clinic');
      $params->get_extra_item = true;
      return $clinic_manager->getCountByModelSearchCriteria($params);
  }

  public function search(ClinicSearchParams $params)
  {
    /**
     * @var ClinicManager $clinic_manager
     */
    $clinic_manager = ModelManagerFactory::getByName('clinic');

    $params->get_extra_item = !$params->nearest;

    $clinics = $clinic_manager->getListByClinicSearchParams($params);

    if (!$clinics) {
      $clinics = $this->removeCriteriaAlgorithm($params);
    }

    $this->totalClinicCount = $clinic_manager->getTotalHits();

    $this->next_page_flag = true;
    if (count($clinics) == ($params->by_page + 1)) {
      unset($clinics[$params->by_page]);
    }

    if (count($clinics) < ($params->by_page)) {
      $this->next_page_flag = false;
    }

      $searchNearest = $this->isSearchNearestAllowed
          && $this->totalClinicCount < 5
          && !$params->geo_point
          && !$params->clinic_name;
      if ($searchNearest) {
          $this->resultsComposition = empty($clinics)
              ? self::RESULTS_ONLY_NEAREST
              : self::RESULTS_EXTENDED_WITH_NEAREST;
          $geoPoint = GeoHelper::getGeoPointBySearchParams($params);
          if ($geoPoint) {
              $params->nearest = true;
              $params->geo_point = $geoPoint;
              $params->metro_station_id = null;
              $params->street_id = null;
              $params->region_id = null;
              $params->district_id = null;
              $params->get_extra_item = false;
              $params->page = 1;
              $params->by_page = 10;
              $self = new self();
              $clinics = $self->search($params);
              $this->totalClinicCount = count($clinics);
          }
      }

      return $clinics;
  }

  private function removeCriteriaAlgorithm(ClinicSearchParams $clinic_search_params)
  {
    $clinics = array();

    while (!$clinics) {
      if ($clinic_search_params->geo_point && $clinic_search_params->distance < 64000 && $clinic_search_params->distance > 0) {
        $clinic_search_params->distance *= 2;
        $clinics = $this->manager->getListByClinicSearchParams($clinic_search_params);
        continue;
      }

      if ($clinic_search_params->distance >= 64000) {
        $clinic_search_params->geo_point = null;
        $clinic_search_params->distance = null;
        $clinics = $this->manager->getListByClinicSearchParams($clinic_search_params);
        $clinic_search_params->metro_station_name = null;
        continue;
      }

      $this->good_search_flag = false;

      if (!$this->use_discard_criteria_algorithm)
        break;

      if ($clinic_search_params->clinic_name) {
        $clinic_search_params->clinic_name = null;
        $clinics = $this->manager->getListByClinicSearchParams($clinic_search_params);
        continue;
      }

      if ($clinic_search_params->children) {
        $clinic_search_params->children = null;
        $clinics = $this->manager->getListByClinicSearchParams($clinic_search_params);
        continue;
      }

      if ($clinic_search_params->pregnant) {
        $clinic_search_params->pregnant = null;
        $clinics = $this->manager->getListByClinicSearchParams($clinic_search_params);
        continue;
      }

      if ($clinic_search_params->handicapped) {
        $clinic_search_params->handicapped = null;
        $clinics = $this->manager->getListByClinicSearchParams($clinic_search_params);
        continue;
      }

      if ($clinic_search_params->day_and_night) {
        $clinic_search_params->day_and_night = null;
        $clinics = $this->manager->getListByClinicSearchParams($clinic_search_params);
        continue;
      }

      if ($clinic_search_params->purpose_of_visit_id) {
        $clinic_search_params->purpose_of_visit_id = null;
        $clinics = $this->manager->getListByClinicSearchParams($clinic_search_params);
        continue;
      }

      if ($clinic_search_params->specialty_id) {
        $clinic_search_params->specialty_id = null;
        $clinics = $this->manager->getListByClinicSearchParams($clinic_search_params);
        continue;
      }

      break;
    }

    return $clinics;
  }

  /**
   * @return int
   */
  public function getTotalClinicCount()
  {
    return $this->totalClinicCount;
  }
}
