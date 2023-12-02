<?php
	class ProductElasticSearchMapping implements IElasticSearchMapping
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
				'full_name' => array(
					'type' => 'text',
					'analyzer' => 'autocomplete',
          'search_analyzer' => 'searchAnalyzer',
          'index' => true,
          'store' => true,
				),
				'full_name_sort' => array(
					'type' => 'keyword',
				),
				'product_category' => array(
					'type' => 'integer',
				),
				'is_active' => array(
					'type' => 'boolean',
				),
				'is_leader' => array(
					'type' => 'boolean',
				),
				'manufacturer' => array(
					'type' => 'integer',
				),
				'image_find_status' => array(
					'type' => 'integer',
				),
				'fill_information_status' => array(
					'type' => 'integer',
				),
			);
		}

	}