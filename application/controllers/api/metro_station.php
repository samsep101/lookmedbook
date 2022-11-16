<?php
    class MetroStationApiController extends ApiController
    {
        public $layout = 'ajax';

        public function getCachedMethods()
        {
            return array(
                'getList' => array(
                    'metro_station:list',
                    'metro_station'
                ),
                'getOneByGeoPoint' => array(
                    'metro_station:list',
                    'metro_station'
                )
            );
        }

        // получение списка всех станций метро
        public function getList()
        {
            $city_id = $this->request('city_id');

            if (!$city_id)
                ApiHeader::error(ApiRequestErrors::INCORRECT_PARAMS);

            $metro_station_manager = new MetroStationManager();
            $metro_stations = $metro_station_manager->getListByCityId($city_id);

            if (!$metro_stations)
                ApiHeader::error(ApiRequestErrors::METRO_STATION_NOT_EXIST);

            foreach ($metro_stations as $metro_station) {
                $result[] = array(
                    'metro_station_id ' => $metro_station->getId(),
                    'name' => $metro_station->name,
                    'metro_branch' => array(
                        'metro_branch_id' => $metro_station->metro_branch_id,
                        'name' => $metro_station->metro_branch->name,
                    ),
                    'region' => array(
                        'region_id' => $metro_station->region_id,
                        'name' => ($metro_station->region_id) ? $metro_station->region->name : '',
                    ),
                    'longtitude' => $metro_station->longitude,
                    'latitude' => $metro_station->latitude,
                );
            }

            ApiHeader::response($result, $this->e_tag);
        }

        // получение станции метро по координатам
        public function getOneByGeoPoint()
        {
            /**
            * @var MetroStationManager $metro_station_manager
            */

            $distance_min = 2000;
            $distance_max = 16000;
            $step = $distance_min;

            $latitude = $this->request('latitude');
            $longitude = $this->request('longitude');
            $city_id = $this->request('city_id');

            if (!$latitude || !$longitude || !$city_id)
                ApiHeader::error(ApiRequestErrors::INCORRECT_PARAMS);

            $metro_station_manager = ModelManagerFactory::getByName('metro_station');
            if (!$metro_stations = $metro_station_manager->getListByCityId($city_id))
                ApiHeader::error(ApiRequestErrors::METRO_STATIONS_NOT_EXIST);

            $distance = GeoPoint::getMetroStationIdAndMinDistanceToMetroStationByStartCoordinates($metro_stations, $latitude, $longitude, $distance_min, $distance_max, $step);

            if (!count($distance))
                ApiHeader::error(ApiRequestErrors::METRO_STATIONS_NOT_EXIST);

            $metro_station = $metro_station_manager->getOneById($distance['metro_station_id']);

            $result = array(
                'metro_station_id' => $distance['metro_station_id'],
                'name' => $metro_station->name,
                'distance' => $distance['distance'],
            );

            ApiHeader::response($result, $this->e_tag);
        }
    }