<?php
	class LogManager extends ModelManager
	{
		protected $table_name = 'log';
		protected $model_name = 'LogModel';


        /**
		 * @return LogModel[]
		 */
		public function getListByAccountId($account_id)
		{
			$data = $this->orm_model->select()->where('account_id = ?', $account_id)->fetchAll();
			return $this->initList($data);
		}

        /**
		 * @return LogModel[]
		 */
		public function getListLogs()
		{
			$db = Register::get('db');

			$sql = 'SELECT *
                    FROM ' . $this->table_name . '
                    ORDER BY dt DESC ;';

			$data = $db->query($sql);

			return $data;
		}
	}
