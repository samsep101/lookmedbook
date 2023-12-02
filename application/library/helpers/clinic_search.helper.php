<?php

class ClinicSearchHelper
{
    public static function initClinicSearchParams(Request $request, $cityId)
    {
        $params = new ClinicSearchParams();
        $landing_item = $request->getParam('landing_item_id', '');

        if (!empty($landing_item) && is_string($landing_item)) {
            $type_page = self::getLandingPageItem($landing_item);

            if ($type_page instanceof ClinicServicesModel) {
                $params->services = $type_page->id;
            } else {
                $params->services = 0;
            }

            if ($type_page instanceof ClinicTypeModel) {
                $params->types = $type_page->id;
            } else {
                $params->types = 0;
            }
        }

        $specialization_id = intval($request->getParam('specialization_id', 0));

        if ($specialization_id > 0) {
            $params->specialization_id = $specialization_id;
        } else {
            $params->specialty_id = (int)$request->getParam('specialty_id', 0);
        }

        $params->service_categories = $request->getParam('service_categories', 0);
        $params->purpose_of_visit_id = (int)$request->getParam('purpose_of_visit_id', 0);
        $params->children = $request->getParam('children', 0);
        $params->handicapped = $request->getParam('handicapped', 0);
        $params->primary_clinic_id = $request->getParam('primary_clinic_id', 0);
        $params->pregnant = $request->getParam('pregnant', 0);
        $params->day_and_night = $request->getParam('day_and_night', 0);
        $params->clinic_name = $request->getParam('clinic_name', '');
        $params->sort_by = $request->getParam('sort_by', 'rate');
        $params->city_id = $cityId;
        $params->not_show_example = true;
        $params->is_active = 1;
        $params->metro_station_name = $request->getParam('metro_station_name', '');
        $params->metro_branch_name = $request->getParam('metro_branch_name', '');
        $params->district_id = $request->getParam('district_id');
        $params->region_id = $request->getParam('region_id');
        $params->street_id = $request->getParam('street_id');
        $params->metro_station_id = $request->getParam('metro_station_id');
        $params->twenty_four_hours = $request->getParam('twenty_four_hours', 0);
        $params->is_card_pay = $request->getParam('is_card_pay', 0);
        $params->have_ramp = $request->getParam('have_ramp', 0);

        $latitude = (float)$request->getParam('latitude', 0);
        $longitude = (float)$request->getParam('longitude', 0);
        $is_metro = $request->getParam('is_metro', 0);

        if ($latitude && $longitude) {
            $params->geo_point = new GeoPoint($latitude, $longitude);

            $params->is_metro = $is_metro;
        }
        $clinic_type = $request->getParam('clinic_type');
        if (!empty($clinic_type) && $clinic_type == 'children') {
            $params->only_children = 1;
        }

        $district = $request->getParam('district');
        if (!isset($params->district_id) && $district) {
            $params->district_id = $district->id;
        }

        $region = $request->getParam('region');
        if (!isset($params->region_id) && $region) {
            $params->region_id = $region->id;
        }

        $street = $request->getParam('street');
        if (!isset($params->street_id) && $street) {
            $params->street_id = $street->id;
        }

        $metro = $request->getParam('metro');
        if (!isset($params->metro_station_id) && $metro) {
            $params->metro_station_id = $metro->id;
        }

        $params->page = $request->getParam('page', 1);
        $params->by_page = $request->getParam('by_page', 10);

        return $params;
    }

    private static function getLandingPageItem($landing_page_alias)
    {
        $clinic_services_manager = ModelManagerFactory::getByName('clinic_services');
        $service = $clinic_services_manager->getItemByAlias($landing_page_alias);

        $clinic_type_manager = ModelManagerFactory::getByName('clinic_type');
        $type = $clinic_type_manager->getItemByAlias($landing_page_alias);

        $result_item = $service ? $service : ($type ? $type : array());
        if (empty($result_item) || !$result_item->perceived_as_page) {
            return [];
        } else {
            return $result_item;
        }
    }
}