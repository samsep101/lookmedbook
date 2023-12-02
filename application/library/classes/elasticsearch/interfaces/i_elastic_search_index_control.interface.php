<?php

	/**
	 * Interface IElasticSearchIndexManager
	 * Интерфейс для основных операций над ElasticSearch
	 */
	interface IElasticSearchIndexControl
	{
		/**
		 * Создание нового индекса
		 *
		 * @param $index_name
		 *
		 * @return mixed
		 */
		public function createIndex($index_name);


		/**
		 * Получение индекса
		 *
		 * @param $index_name
		 *
		 * @return mixed
		 */
		public function getIndex($index_name);

		/**
		 * Удаление индекса
		 *
		 * @param $index_name
		 *
		 * @return mixed
		 */
		public function deleteIndex($index_name);

		/**
		 * Обновление индекса
		 *
		 * @param $index_name название индекса
		 * @return mixed
		 */
		public function refreshIndex($index_name);
	}