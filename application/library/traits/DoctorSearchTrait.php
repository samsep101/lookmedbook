<?php

namespace app\library\traits;

trait DoctorSearchTrait
{
    public function ajaxSearch__address_object() {
        $city = $this->city;
        $city_id = $city->getId();

        $district = $this->ajaxSearch__address_object__district($city_id);
        $region = $this->ajaxSearch__address_object__region($district);
        $street = $this->ajaxSearch__address_object__street($district);
        $metro_station = $this->ajaxSearch__address_object__metro($region);

        $this->view->district = $district;
        $this->view->region = $region;
        $this->view->street = $street;
        $this->view->metro_station = $metro_station;

        $address_object = NULL;
        if ($metro_station) {
            $address_object = $metro_station;
        } elseif ($street) {
            $address_object = $street;
        } elseif ($region) {
            $address_object = $region;
        } elseif ($district) {
            $address_object = $district;
        } elseif ($city) {
            $address_object = $city;
        }

        return $address_object;
    }

    public function ajaxSearch__address_object__district($city_id) {
        $district_manager = new \DistrictManager();
        $district = NULL;
        $district_id = $this->request('district_id');

        if ($district_id) {
            $district = $district_manager->getOneById($district_id);
            if (!$district || $district->city_id != $city_id) \ErrorPageViewHelper::page404('404');
        }
        return $district;
    }


    public function ajaxSearch__address_object__region($district) {
        $region_manager = new \RegionManager();
        $region = NULL;
        $region_id = $this->request('region_id');

        if ($region_id and $district) {
            $region = $region_manager->getOneById($region_id);

            if (!$region || $region->district_id != $district->getId()) \ErrorPageViewHelper::page404();
        }
        return $region;
    }


    public function ajaxSearch__address_object__street($district) {
        $street_manager = new \StreetManager();
        $street = NULL;
        $street_id = $this->request('street_id');

        if ($street_id and $district) {
            $street = $street_manager->getOneById($street_id);

            if (!$street || !$street->isBelongToDistrict($district->getId())) \ErrorPageViewHelper::page404();
        }
        return $street;
    }


    public function ajaxSearch__address_object__metro($region) {
        $metro_station_manager = new \MetroStationManager();
        $metro_station = NULL;
        $metro_id = $this->request('metro_station_id');

        if ($metro_id and $region) {
            $metro_station = $metro_station_manager->getOneById($metro_id);

            if (!$metro_station || ($metro_station->region_id != $region->getId())) \ErrorPageViewHelper::page404();
        }


        return $metro_station;
    }
}
