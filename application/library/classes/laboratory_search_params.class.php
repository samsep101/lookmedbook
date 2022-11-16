<?php

class LaboratorySearchParams extends ModelSearchCriteria
{
    /**
     * @var GeoPoint
     */
    public $geo_point;
    public $is_metro;

    public $urgent_tests;
    public $card_pay;
    public $work_seven_days;
    public $easy_entry;
    public $without_turn;
    public $day_and_night;

    public $metro_station_name;
    public $metro_branch_name;
    public $distance = 2000;

    public $city_id;
}
