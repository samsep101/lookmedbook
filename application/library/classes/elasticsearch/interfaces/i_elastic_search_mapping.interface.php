<?php
	/**
	 * Задает меппинг для данных
	 *
	 * @link http://www.elasticsearch.org/guide/reference/mapping/
	 */
	interface IElasticSearchMapping
	{
		/**
		 * Получить меппинг для данной сущности
		 *
		 * @link http://www.elasticsearch.org/guide/reference/mapping/
		 * @return array
		 */
		public function getFieldsMapping();
	}