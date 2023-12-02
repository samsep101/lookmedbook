<?php

	class LaboratoryElasticSearchMapping implements IElasticSearchMapping
	{
		/**
		 * Получить меппинг для данной сущности
		 *
		 * @link http://www.elasticsearch.org/guide/reference/mapping/
		 * @return array
		 */
		public function getFieldsMapping()
		{
			return array(
				'id' => array(
					'type' => 'integer',
				),
				'is_has_urgent_tests' => array(
					'type' => 'boolean',
				),
				'is_has_card_pay' => array(
					'type' => 'boolean',
				),
				'is_work_seven_days' => array(
					'type' => 'boolean',
				),
				'is_easy_entry' => array(
					'type' => 'boolean',
				),
				'is_without_turn' => array(
					'type' => 'boolean',
				),
				'is_day_and_night' => array(
					'type' => 'boolean',
				),
				'city' => array(
					'type' => 'integer',
				),
				'registry_user' => array(
					'type' => 'integer',
				),
				'geo_point' => array(
					'type' => 'geo_point',
				)
			);
		}

	}