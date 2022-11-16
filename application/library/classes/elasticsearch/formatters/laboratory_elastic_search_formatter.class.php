<?php

	class LaboratoryElasticSearchFormatter implements  IElasticSearchFormatter
	{
		/**
		 * @param DynamicModel $model
		 *
		 * @return \Elastica\Document
		 */
		public function toElasticSearchView(DynamicModel $model)
		{
			/**
			 * @var LaboratoryModel $model
			 */
			$result = array();

			if($model->latitude && $model->longitude)
			{
				$result['geo_point'] = array(
					'lat' => $model->latitude,
					'lon' => $model->longitude
				);
			}

			if($model->is_urgent_tests)
			{
				$result['is_has_urgent_tests'] = (bool)$model->is_urgent_tests;
			}

			$result['is_has_card_pay'] = (bool)$model->is_card_pay;
			$result['is_work_seven_days'] = (bool)$model->is_work_seven_days;
			$result['is_easy_entry'] = (bool)$model->is_easy_entry;
			$result['is_without_turn'] = (bool)$model->is_without_turn;
			$result['is_day_and_night'] = (bool)$model->is_day_and_night;
			$result['city'] = $model->city_id;
			$result['metro_station'] = $model->metro_station_id;

			return new \Elastica\Document($model->getId(), $result);
		}

	}
