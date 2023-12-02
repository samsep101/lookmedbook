<?php
class CityApiController extends ApiController
{
    public $layout = 'ajax';

    public function getCachedMethods()
    {
        return array(
            'getList' => array(
                'tags' => array(
                    'city:list'
                ),
            ),
        );
    }

    // получение списка городов
    public function getList()
    {
        $city_manager = new CityManager();
        $service_flag = isset($_GET['service_flag']) ? (int)$_GET['service_flag'] : 1;

        if($service_flag)
        {
            $cities = $city_manager->getActiveList();
        }
        else
        {
            $cities = $city_manager->getList();
        }

        if (!$cities)
            ApiHeader::error(ApiRequestErrors::CITIES_NOT_EXIST);

        foreach ($cities as $city) {
            $result[] = array(
                'city_id' => $city->getId(),
                'name'  =>  $city->name,
                'prepositional_name' => $city->prepositional_name,
                'region'    =>  $city->region,
                'country'   =>  array(
                    'country_id'    => $city->country_id,
                    'name'  =>  ($city->country_id) ? $city->country->name : '',
                ),
                'service_flag'  =>  $city->service_flag,
                'latitude'  =>  $city->lat,
                'longtitude'    =>  $city->lng
            );
        }

        ApiHeader::response($result,$this->e_tag);
    }

    public function getId()
    {
        $latitude = $this->request('latitude');
        $longitude = $this->request('longitude');

        if (!$latitude || !$longitude)
            ApiHeader::error(ApiRequestErrors::INCORRECT_PARAMS);

        $geocoder = new YandexGeocoder();
        $city_name = $geocoder->getCityByGeoPoint(new GeoPoint($latitude, $longitude));

        $city_manager = new CityManager();

        $city = $city_manager->getOneByName($city_name);

        if (!$city)
        {
            ApiHeader::error(ValidationErrorCodes::FAILED_TO_DETERMINE_CITY);;
        }

        $result = array(
            'city_id'   =>  $city->id,
            'name'  =>  $city->name,
            'prepositional_name' => $city->prepositional_name,
            'country'   =>  array(
                'country_id'    =>  ($city->country_id) ? $city->country_id : 0,
                'name'  =>  ($city->country_id) ? $city->country->name : null,
            ),
            'service_flag'  => ($city->service_flag) ? (int)$city->service_flag : 0
        );

        ApiHeader::response($result,true);

    }
}