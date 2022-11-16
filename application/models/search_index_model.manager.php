<?php

	abstract class SearchIndexModelManager extends ModelManager implements ISearchIndexModelManager
	{

		/**
		 * Метод для получения списка моделей, которые необходимо проиндексировать
		 *
		 * @param $limit - лимит количества моделей
		 *
		 * @return DynamicModel[]
		 */
		public function getListForAddToIndex($limit = 0)
		{
			$sql = 'SELECT *
					FROM `'.$this->table_name.'`
					WHERE is_need_to_index_update = 1
					LIMIT '.(int)$limit;

			$data = $this->db->query($sql);

			return $this->initList($data);
		}

		/**
		 * Метод для получения списка id, которые
		 * должны быть удалены из индекса
		 *
		 * @param int $limit - лимит
		 *
		 * @return mixed
		 */
		public function getIdsForDeleteFromIndex($limit = 0)
		{
			/**
			 * @var ModelIndexDeleteManager $model_index_delete_manager
			 */
			$model_index_delete_manager = ModelManagerFactory::getByName('model_index_delete');
			$items = $model_index_delete_manager->getNotDeletedListByNameWithLimit($this->table_name, $limit);

			$ids = array();
			$delete_ids = array();

			if($items)
			{
				foreach($items as $item)
				{
					$ids[] = $item->model_id;
					$delete_ids[] = $item->getId();
				}
			}

			$model_index_delete_manager->setDeletedStatusByIds($delete_ids);

			return $ids;

		}

		/**
		 * Устанавливает флаг, который отвечает за индексацию данных (т.е.
		 * помечает все записи, как необходимые для индексации)
		 *
		 * @return bool
		 */
		public function setReIndexStatus()
		{
			$sql = 'UPDATE `'.$this->table_name.'`
					SET is_need_to_index_update = 1';

			$this->db->query($sql);
		}

		/**
		 * Устанавливает флаг "проиндексированы" для переданных моделей
		 *
		 * @param array $models
		 *
		 * @return void
		 */
		public function markAsIndexed(array $models)
		{
			$ids = array();

			foreach($models as $model)
			{
				$ids[] = (int)$model->getId();
			}

			$sql = 'UPDATE `'.$this->table_name.'`
					SET is_need_to_index_update = 0
					WHERE id IN ('.join(', ',$ids).')';

			$this->db->query($sql);

			$this->clearRegister();
		}


	}