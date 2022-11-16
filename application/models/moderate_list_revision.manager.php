<?php
	class ModerateListRevisionManager extends ModelManager
	{
		protected $table_name = 'moderate_list_revision';
		protected $model_name = 'ModerateListRevisionModel';

		public function getLastRevisionNumberByRevisionConditionAndListName(array $params, $list_name)
		{
			$sql = 'SELECT revision_number
					FROM `' . $this->table_name . '`
					WHERE list_name = "'.$this->db->escape($list_name).'"
						AND '.$this->formParamsWhereCondition($params).'
					ORDER BY revision_number DESC, id DESC
					LIMIT 1';

			$data = $this->db->query($sql);

			return ($data) ? $data[0]['revision_number'] : 0;
		}

		/**
		 * Получение последней активной записи
		 *
		 * @param $params
		 * @param $list_name
		 */
		public function getLastActiveByRevisionConditionAndListName(array $params, $list_name)
		{

			$sql = 'SELECT *
					FROM ' . $this->table_name . '
					WHERE '.$this->formParamsWhereCondition($params).'
						AND list_name = "' . $this->db->escape($list_name) . '"
						AND moderate_status_id != ' . ModerateStatusModel::PUBLISHED . '
					ORDER BY revision_number DESC
					LIMIT 1';

			$data = $this->db->query($sql);

			return ($data) ? $this->initOne($data[0]) : null;
		}

		protected function formParamsWhereCondition($params)
		{
			$data = array();

			foreach($params as $k => $v)
				$data[] = $k.' = '.(int)$v;

			return join(' AND ', $data);
		}

	}