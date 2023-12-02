<?php

	/**
	 * Interface ISearchIndexModelManager
	 *
	 * Интерфейс для получения списка моделей, которые должны быть
	 * удалены либо добавлены в индекс
	 */
	interface ISearchIndexModelManager
	{
		/**
		 * Метод для получения списка моделей, которые необходимо проиндексировать
		 *
		 * @param $limit - лимит количества моделей
		 *
		 * @return DynamicModel[]
		 */
		public function getListForAddToIndex($limit = 0);

		/**
		 * Метод для получения списка id, которые
		 * должны быть удалены из индекса
		 *
		 * @param int $limit - лимит
		 *
		 * @return mixed
		 */
		public function getIdsForDeleteFromIndex($limit = 0);

		/**
		 * Устанавливает флаг, который отвечает за индексацию данных (т.е.
		 * помечает все записи, как необходимые для индексации)
		 *
		 * @return bool
		 */
		public function setReIndexStatus();

		/**
		 * Устанавливает флаг "проиндексированы" для переданных моделей
		 * @param array $models
		 *
		 * @return void
		 */
		public function markAsIndexed(array $models);
	}