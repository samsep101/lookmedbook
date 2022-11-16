<?php

class GeoHelper
{
    public static function getGeoPointBySearchParams($params)
    {
        if (!empty($params->street_id)) {
            $geoPoint = static::getGeoPointByStreetId($params->street_id);
        } elseif (!empty($params->region_id)) {
            $geoPoint = static::getGeoPointByRegionId($params->region_id);
        } elseif (!empty($params->district_id)) {
            $geoPoint = static::getGeoPointByDistrictId($params->district_id);
        } elseif (!empty($params->city_id)) {
            $geoPoint = static::getGeoPointByCityId($params->city_id);
        } else {
            $geoPoint = null;
        }

        return $geoPoint;
    }

    /**
     * Получение координаты центра улицы
     * @param $streetId
     * @return GeoPoint|false
     */
    public static function getGeoPointByStreetId($streetId)
    {
        /** @var StreetManager $streetManager */
        $streetManager = ModelManagerFactory::getByName('street');
        /** @var StreetModel $street */
        $street = $streetManager->getOneById($streetId);

        if ($street->latitude && $street->longitude) {
            return new GeoPoint($street->latitude, $street->longitude);
        }

        $district = $street->district;
        if ($district) {
            $districtName = $district->name;
            $cityName = $district->city ? $district->city->name : "";
        } else {
            $cityName = "";
            $districtName = "";
        }

        $addressName = "$cityName,$districtName,{$street->name}";

        $geoPoint = static::geoCoder()->geocode($addressName);

        if ($geoPoint) {
            $street->latitude = $geoPoint->getLatitude();
            $street->longitude = $geoPoint->getLongitude();
            $street->save();
        }

        return $geoPoint;
    }

    /**
     * Получение координаты центра района
     * @param $regionId
     * @return GeoPoint|false
     */
    public static function getGeoPointByRegionId($regionId)
    {
        /** @var RegionManager $regionManager */
        $regionManager = ModelManagerFactory::getByName('region');
        /** @var RegionModel $region */
        $region = $regionManager->getOneById($regionId);

        if ($region->latitude && $region->longitude) {
            return new GeoPoint($region->latitude, $region->longitude);
        }

        $district = $region->district;
        if ($district) {
            $districtName = $district->name;
            $cityName = $district->city ? $district->city->name : "";
        } else {
            $cityName = "";
            $districtName = "";
        }

        $addressName = "$cityName,$districtName,{$region->name}";

        $geoPoint = static::geoCoder()->geocode($addressName);

        if ($geoPoint) {
            $region->latitude = $geoPoint->getLatitude();
            $region->longitude = $geoPoint->getLongitude();
            $region->save();
        }

        return $geoPoint;
    }

    /**
     * Получение координаты центра округа
     * @param $districtId
     * @return GeoPoint|false
     */
    public static function getGeoPointByDistrictId($districtId)
    {
        /** @var DistrictManager $districtManager */
        $districtManager = ModelManagerFactory::getByName('district');
        /** @var DistrictModel $district */
        $district = $districtManager->getOneById($districtId);

        if ($district->latitude && $district->longitude) {
            return new GeoPoint($district->latitude, $district->longitude);
        }

        if ($district) {
            $districtName = $district->name;
            $cityName = $district->city ? $district->city->name : "";
        } else {
            $cityName = "";
            $districtName = "";
        }

        $addressName = "$cityName,$districtName";

        $geoPoint = static::geoCoder()->geocode($addressName);

        if ($geoPoint) {
            $district->latitude = $geoPoint->getLatitude();
            $district->longitude = $geoPoint->getLongitude();
            $district->save();
        }

        return $geoPoint;
    }

    /**
     * Получение координаты центра округа
     * @param $cityId
     * @return GeoPoint|false
     */
    public static function getGeoPointByCityId($cityId)
    {
        /** @var CityManager $cityManager */
        $cityManager = ModelManagerFactory::getByName('city');
        /** @var CityModel $city */
        $city = $cityManager->getOneById($cityId);

        if ($city->lat && $city->lng) {
            return new GeoPoint($city->lat, $city->lng);
        }

        if ($city) {
            $geoPoint = static::geoCoder()->geocode($city->name);
            $city->lat = $geoPoint->getLatitude();
            $city->lng = $geoPoint->getLongitude();
            $city->save();

            return $geoPoint;
        }

        return false;
    }

    /**
     * @return YandexGeocoder
     */
    public static function geoCoder()
    {
        static $geoCoder = false;

        if ($geoCoder === false) {
            $geoCoder = new YandexGeocoder();
        }

        return $geoCoder;
    }
}
