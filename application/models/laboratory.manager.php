<?php

class LaboratoryManager extends ModelManager
{
  protected $table_name = 'laboratory';
  protected $model_name = 'LaboratoryModel';

  public function getOneByAddress($address)
  {
    $data = $this->orm_model->select()->where('address = ?', $address)->fetchOne();
    return $this->initOne($data);
  }

  public function getIdListBySearchParams(SearchParams $search_params)
  {
    /**
     * @var LaboratoryModel[] $laboratories
     */
    $laboratories = $this->getListBySearchParams($search_params);

    $result = array();

    if ($laboratories) {
      foreach ($laboratories as $laboratory) {
        $result[$laboratory->getId()] = $laboratory->getId();
      }
    }

    return $result;
  }

  public function getListByModelSearchCriteria(ModelSearchCriteria $criteria)
  {
    $index_control = new ElasticSearchLaboratoryIndexControl();

    $ids = $index_control->search($criteria);
    $laboratories = $this->getListByIds($ids);

    return $laboratories;
  }

  public function getMetroStationByGeoPoint($latitude, $longitude)
  {
    $distance_min = 2000;
    $distance_max = 16000;
    $step = $distance_min;

    $metro_station_manager = new MetroStationManager();
    $metro_stations = $metro_station_manager->getList();

    $distance = GeoPoint::getMetroStationIdAndMinDistanceToMetroStationByStartCoordinates($metro_stations, $latitude, $longitude, $distance_min, $distance_max, $step);

    if (!count($distance))
      JsonResponse::error(ApiRequestErrors::METRO_STATIONS_NOT_EXIST);

    return $distance['metro_station_id'];
  }

  public function checkExistsOfStatusesByCityId($city_id)
  {
    $sql = 'SELECT
						(SELECT COUNT(*)
						FROM laboratory
						WHERE city_id = ' . (int)$city_id . '
							AND is_urgent_tests = 1
						LIMIT 1) as is_urgent_tests,
						(SELECT COUNT(*)
						FROM laboratory
						WHERE city_id = ' . (int)$city_id . '
							AND is_card_pay = 1
							LIMIT 1) as is_card_pay,
						(SELECT COUNT(*)
						FROM laboratory
						WHERE city_id = ' . (int)$city_id . '
							AND is_work_seven_days = 1
							LIMIT 1) as is_work_seven_days,
						(SELECT COUNT(*)
						FROM laboratory
						WHERE city_id = ' . (int)$city_id . '
							AND is_easy_entry = 1
							LIMIT 1) as is_easy_entry,
						(SELECT COUNT(*)
						FROM laboratory
						WHERE city_id = ' . (int)$city_id . '
							AND is_without_turn = 1
							LIMIT 1) as is_without_turn,
						(SELECT COUNT(*)
						FROM laboratory
						WHERE city_id = ' . (int)$city_id . '
							AND is_day_and_night = 1
							LIMIT 1) as is_day_and_night';

    $data = $this->db->query($sql);

    return $data[0];
  }

  public function checkExistsByCityId($city_id)
  {
    $sql = 'SELECT COUNT(*) as count
					FROM laboratory
					WHERE city_id = ' . (int)$city_id . '
					LIMIT 1';
    $data = $this->db->query($sql);

    return (bool)$data[0]['count'];
  }

  /**
   * Метод необходим для получения координатного окна, в котором располжены объекты
   *
   * @param GeoPoint $geo_point
   * @param float $distance
   *
   * @return array
   */
  public function getBoundsByGeoPointAndDistance(GeoPoint $geo_point, $distance)
  {
    $search = new ElasticSearchLaboratoryIndexControl();
    try {
      $laboratories = $search->getListByGeoPointAndDistance($geo_point, $distance);
    } catch (Exception $e) {
      $laboratories = [];
    }

    $bounds = array();
    $bounds['min_latitude'] = 10000;
    $bounds['max_latitude'] = 0;
    $bounds['min_longitude'] = 1000;
    $bounds['max_longitude'] = 0;

    foreach ($laboratories as $clinic) {
      if ($clinic['geo_point']['lat'] < $bounds['min_latitude']) {
        $bounds['min_latitude'] = $clinic['geo_point']['lat'];
      }

      if ($clinic['geo_point']['lon'] < $bounds['min_longitude']) {
        $bounds['min_longitude'] = $clinic['geo_point']['lon'];
      }

      if ($clinic['geo_point']['lat'] > $bounds['max_latitude']) {
        $bounds['max_latitude'] = $clinic['geo_point']['lat'];
      }

      if ($clinic['geo_point']['lon'] > $bounds['max_longitude']) {
        $bounds['max_longitude'] = $clinic['geo_point']['lon'];
      }
    }

    return $bounds;
  }
}