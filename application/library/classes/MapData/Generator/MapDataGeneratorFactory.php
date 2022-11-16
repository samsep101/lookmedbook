<?php

namespace app\library\classes\MapData\Generator;

use AnalysisSearchHelper;
use ClinicSearchAlgorithm;
use ClinicSearchHelper;
use DoctorSearchAlgorithm;
use DoctorSearchHelper;
use InvalidArgumentException;
use ModelManagerFactory;

class MapDataGeneratorFactory
{
    const TYPE_CLINIC = 'clinic';
    const TYPE_DOCTOR = 'doctor';
    const TYPE_ANALYSIS = 'analysis';

    private static $datatypes = [
        self::TYPE_CLINIC,
        self::TYPE_DOCTOR,
        self::TYPE_ANALYSIS
    ];

    /**
     * @param string $datatype
     * @param \Request $request
     * @param int $cityId
     * @return MapDataGenerator
     * @throws \InvalidArgumentException
     */
    public static function create($datatype, $request, $cityId)
    {
        if (!in_array($datatype, self::$datatypes, true)) {
            throw new InvalidArgumentException("Invalid datatype: $datatype");
        }

        if ($datatype === 'clinic') {
            $params = ClinicSearchHelper::initClinicSearchParams($request, $cityId);
            self::applyMaxDistanceToParams($cityId, $params);
            $search = new ClinicSearchAlgorithm();
            $manager = ModelManagerFactory::getByName('clinic');
            $generator = new ClinicMapDataGenerator($params, $search, $manager);
        }
        elseif ($datatype === 'doctor') {
            $params = DoctorSearchHelper::initDoctorSearchParams($request, $cityId);
            self::applyMaxDistanceToParams($cityId, $params);
            $search = new DoctorSearchAlgorithm();
            $generator = new DoctorMapDataGenerator($params, $search);
        }
        elseif ($datatype === 'analysis') {
            $params = AnalysisSearchHelper::initSearchParams($request, $cityId);
            $manager = ModelManagerFactory::getByName('laboratory');
            $generator = new AnalysisMapDataGenerator($params, $manager);
        }

        return $generator;
    }

    /**
     * @param                     $cityId
     * @param \ClinicSearchParams|\DoctorSearchParams $params
     */
    protected static function applyMaxDistanceToParams($cityId, $params)
    {
        if ($params->geo_point) {
            return;
        }
        $cityManager = ModelManagerFactory::getByName('city');
        /** @var \CityModel $city */
        $city = $cityManager->getOneById($cityId);
        if ($city) {
            $params->geo_point = new \GeoPoint($city->lat, $city->lng);
            $params->distance = 140000; // Примерный радиус МО
        }
    }
}
