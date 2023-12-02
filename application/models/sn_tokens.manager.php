<?php
	class SnTokensManager extends ModelManager
	{
		protected $table_name = 'sn_tokens';
		protected $model_name = 'SnTokensModel';


        /**
		 * @return SnTokensModel[]
		 */
		public function getListByAccountId($account_id)
		{
			$data = $this->orm_model->select()->where('account_id = ?', $account_id)->fetchAll();
			return $this->initList($data);
		}

		public function checkExistsByAccountIdAndSnNameAndToken($sn_name, $token)
		{
			$sql = 'SELECT COUNT(*) as `result`
                    FROM ' . $this->table_name . '
                    WHERE sn_name = "' . $this->db->escape($sn_name) . '"
                    AND token = "' . $this->db->escape($token) . '"';

			$data = $this->db->query($sql);

			return (bool)$data[0]['result'];

		}

        /**
		 * @return SnTokensModel
		 */
		public function getOneByAccountIdAndSnName($account_id, $sn_name)
		{
			$sql = 'SELECT *
                    FROM ' . $this->table_name . '
                    WHERE account_id = ' . (int)$account_id . '
                    AND sn_name = "' . $this->db->escape($sn_name) . '";';

			$data = $this->db->query($sql);

			return ($data) ? $this->initOne($data[0]) : null;
		}

        /**
		 * @return SnTokensModel[]
		 */
		public function getListByTaskStatusIdWithLimit($task_status_id, $limit)
		{
			$sql = 'SELECT *
                    FROM ' . $this->table_name . '
                    WHERE task_status_id = ' . (int)$task_status_id . '
                    LIMIT ' . (int)$limit;

			$data = Register::get('db')->query($sql);
			return $this->initList($data);
		}

	}
