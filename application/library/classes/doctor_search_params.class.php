<?php

class DoctorSearchParams extends ModelSearchCriteria
{
    public $specialty_id;
    public $specialties_ids = [];
    public $suitable_specialties_ids = [];
    public $service_categories;
    public $city_id;

    public $purpose_of_visit_id;
    public $visit_type;
    public $morning_time;
    public $evening_time;
    public $weekend_time;
    public $any_time;
    public $doctor_name;
    public $doctor_sex_id;

    public $doctor_type;
    public $clinic_id;
    public $not_work;
    /**
     * @var GeoPoint
     */
    public $geo_point;
    public $is_metro;

    public $district_id;
    public $region_id;
    public $street_id;

    public $has_visit_slots = null;

    public $metro_station_name;
    public $metro_branch_name;
    public $metro_station_id;
    public $distance = 2000;

    public $sort_salt;

    public $get_extra_item = false;

    public $is_active = 1;
    public $clinic_is_active = null;

    public $disease_doctor = false;

    public $registry_user_id = null;

    public $for_api = null;

    public $has_avatar = null;

    public $calc_found_rows = false;

    public $not_virtual;
    public $unbounded = null;
    public $without_filters = null;

    public $is_has_clinic = true;
    public $is_has_active_clinic = true;

    public $exclude_ids = [];
    public $discount;

    public $nearest = false;

    public $_id;

    public $high_priority_doctor_ids = [];
    public $high_priority_clinic_ids = [];

    public function getParamsHash()
    {
        $data = clone $this;
        $data->sort_salt = null;
        return parent::getParamsHash($data);
    }
}
