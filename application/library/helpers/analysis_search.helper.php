<?php

class AnalysisSearchHelper
{
    public static function initSearchParams(Request $request, $cityId)
    {
        $params = new LaboratorySearchParams();
        $params->city_id = $cityId;

        $params->metro_station_name = $request->getParam('metro_station_name', '');
        $params->metro_branch_name = $request->getParam('metro_branch_name', '');

        $params->urgent_tests = (float)$request->getParam('urgent_tests', 0);
        $params->card_pay = (float)$request->getParam('card_pay', 0);
        $params->work_seven_days = (float)$request->getParam('work_seven_days', 0);
        $params->easy_entry = (float)$request->getParam('easy_entry', 0);
        $params->without_turn = (float)$request->getParam('without_turn', 0);
        $params->day_and_night = (float)$request->getParam('day_and_night', 0);

        $latitude = (float)$request->getParam('latitude', 0);
        $longitude = (float)$request->getParam('longitude', 0);
        $is_metro = $request->getParam('is_metro', 0);

        if ($latitude && $longitude) {
            $params->geo_point = new GeoPoint($latitude, $longitude);
            $params->is_metro = $is_metro;
        }

        return $params;
    }
}
