<?php

class DoctorSearchHelper
{
    public static function initDoctorSearchParams(Request $request, $cityId)
    {
        $doctor_search_params = new DoctorSearchParams();
        $doctor_search_params->not_virtual = 1;
        $doctor_search_params->specialty_id = (int)$request->getParam('specialty_id', 0);
        $doctor_search_params->purpose_of_visit_id = (int)$request->getParam('purpose_of_visit_id', 0);
        $doctor_search_params->visit_type = $request->getParam('visit_type', 'clinic');
        $doctor_search_params->doctor_type = $request->getParam('doctor_type', 'adult');
        $doctor_search_params->morning_time = $request->getParam('morning_time', 0);
        $doctor_search_params->evening_time = $request->getParam('evening_time', 0);
        $doctor_search_params->weekend_time = $request->getParam('weekend_time', 0);
        $doctor_search_params->any_time = $request->getParam('any_time', 0);
        $doctor_search_params->doctor_name = $request->getParam('doctor_name', '');
        $doctor_search_params->doctor_sex_id = (int)$request->getParam('doctor_sex_id', 0);
        $doctor_search_params->sort_by = $request->getParam('sort_by', 'recomend');
        $doctor_search_params->city_id = $cityId;
        $doctor_search_params->district_id = $request->getParam('district_id');
        $doctor_search_params->region_id = $request->getParam('region_id');
        $doctor_search_params->street_id = $request->getParam('street_id');
        $doctor_search_params->discount = $request->getParam('discount');
        $doctor_search_params->metro_station_name = $request->getParam('metro_station_name', '');
        $doctor_search_params->metro_branch_name = $request->getParam('metro_branch_name', '');
        $doctor_search_params->metro_station_id = $request->getParam('metro_station_id', '');
        $doctor_search_params->specialties_ids = $request->getParam('specialties_ids', array());
        $doctor_search_params->service_categories = $request->getParam('service_categories', 0);
        $doctor_search_params->disease_doctor = $request->getParam('disease_doctor', FALSE);

        $latitude = (float)$request->getParam('latitude', 0);
        $longitude = (float)$request->getParam('longitude', 0);
        $is_metro = $request->getParam('is_metro', 0);

        if ($latitude && $longitude) {
            $doctor_search_params->geo_point = new GeoPoint($latitude, $longitude);
            $doctor_search_params->is_metro = $is_metro;
        }

        if (!$doctor_search_params->morning_time && !$doctor_search_params->evening_time && !$doctor_search_params->weekend_time) {
            $doctor_search_params->any_time = 1;
        }

        if ($doctor_search_params->doctor_sex_id == 3) {
            $doctor_search_params->doctor_sex_id = 0;
        }

        $doctor_search_params->page = $request->getParam('page', 1);
        $doctor_search_params->by_page = $request->getParam('by_page', 10);
        $doctor_search_params->sort_salt = (string) rand();

        return $doctor_search_params;
    }
}
