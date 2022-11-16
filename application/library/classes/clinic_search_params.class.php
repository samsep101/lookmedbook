<?php

    class ClinicSearchParams extends ModelSearchCriteria
    {
        public $specialty_id;
        public $primary_clinic_id;
        public $specialization_id;
        public $purpose_of_visit_id;
        public $children;
        public $handicapped;
        public $pregnant;
        public $day_and_night;
        public $clinic_name;
        public $address;

        public $doctor_id;

        public $city_id;
        public $is_active;
        public $not_work;
        public $redirect_list;
        public $not_show_example;

        /**
         * @var GeoPoint
         */
        public $geo_point;
        public $is_metro;

        public $district_id;
        public $region_id;
        public $street_id;
        public $metro_station_id;

        // параметры в регистратуре
        public $registry_user_id;
        public $freelancer_id;

        public $is_region = 0;

        public $metro_station_name;
        public $metro_branch_name;
        public $distance = 9000;

        public $regions;
        public $status;

        public $publish_date_from;
        public $publish_date_to;

        public $services;
        public $service_categories;
        public $types;

        public $calc_found_rows = FALSE;

        public $only_children;
        public $is_card_pay;
        public $twenty_four_hours;
        public $have_ramp;
        public $nearest = false;
        public $top_phone;
    }
