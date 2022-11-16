<?php
	class DiseaseElasticSearchMapping implements IElasticSearchMapping
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
				'name' => array(
					'type' => 'text',
					'index' => true,
					'analyzer' => 'autocomplete',
					'search_analyzer' => 'searchAnalyzer',
					'boost' => 2,
				),
				'alt_name' => array(
					'type' => 'text',
					'index' => true,
					'analyzer' => 'autocomplete',
          'search_analyzer' => 'searchAnalyzer',
				),
				'tags' => array(
					'type' => 'keyword',
					'index' => true,
				),
				'is_active' => array(
					'type' => 'boolean',
				)
			);
		}
	}