<?php

	/**
	 * Interface IElasticSearchModelManager
	 *
	 * Интерфейс для классов, которые умеют управлять данными данной модели в индексе:
	 * добавлять, обновлять, искать
	 */
	interface IElasticSearchModelIndexControl
	{
		/**
		 * Добавляет либо обновляет документы, которые необходимо проиндексировать
		 *
		 * @param array $documents
		 *
		 * @return mixed
		 */
		public function addDocuments(array $documents);

		/**
		 * Добавляет в индекс один документ
		 *
		 * @param DynamicModel $document
		 *
		 * @return mixed
		 */
		public function addDocument(DynamicModel $document);

		/**
		 * Очищает индекс документов
		 *
		 * @return bool
		 */
		public function clearIndex();

		/**
		 * Поиск данных согласно критериям поиска
		 *
		 * @param ModelSearchCriteria $criteria
		 *
		 * @return mixed
		 */
		public function search(ModelSearchCriteria $criteria);

		/**
		 * Добавление мэппинга
		 *
		 * @return mixed
		 */
		public function applyMapping();

		/**
		 * Удаление документа по идентификатору
		 *
		 * @param $id
		 *
		 * @return bool
		 */
		public function deleteById($id);

		/**
		 * Удаление списка документов
		 *
		 * @param array $ids
		 *
		 * @return mixed
		 */
		public function deleteByIds(array $ids);

	}