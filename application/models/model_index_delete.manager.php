<?php
	class ModelIndexDeleteManager extends ModelManager
	{
		protected $table_name = "model_index_delete";
		protected $model_name = "ModelIndexDeleteModel";

		protected $insert_delayed = true;



		/**
		 * Отмечает указанные пункты, как удаленные
		 *
		 * @param $ids
		 */
		public function setDeletedStatusByIds($ids)
		{
			if(!count($ids))
			{
				return;
			}

			$sql = 'UPDATE '.$this->table_name.'
					SET is_deleted = 1
					WHERE id IN ('.join(', ',$ids).')';

			$this->db->query($sql);
		}

		/**
		 * @param $model
		 * @param $limit
		 *
		 * @return ModelIndexDeleteModel[]
		 */
		public function getNotDeletedListByNameWithLimit($model, $limit)
		{
			$sql = 'SELECT *
					FROM `'.$this->table_name.'`
					WHERE name="'.$this->db->escape($model).'"
						AND is_deleted IS NULL';

			if($limit)
			{
				$sql .= ' LIMIT '.(int)$limit;
			}

			$data = $this->db->query($sql);
			return $this->initList($data);
		}
	}