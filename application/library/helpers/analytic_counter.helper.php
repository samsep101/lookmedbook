<?php
	class AnalyticCounterHelper
	{
    public static function getCounterIdByCityIdAndCounterTypeId($city_id, $counter_type_id)
    {
			/**
			 * @var AnalyticCounterManager $analytic_counter_manager
			 */
        $analytic_counter_manager = ModelManagerFactory::getByName('analytic_counter');

        $analytic_counter = $analytic_counter_manager->getOneByAnalyticCounterTypeIdAndCityId($counter_type_id, $city_id);
        return $analytic_counter ? $analytic_counter->counter_id : null;
    }
}