<?php
	class LandingAccountManager extends ModelManager
	{
		protected $table_name = 'landing_account';
		protected $model_name = 'LandingAccountModel';

        /**
		 * @return LandingAccountModel
		 */
		public function getOneByHash($hash)
		{
			$sql = 'SELECT *
                    FROM ' . $this->table_name . '
                    WHERE `hash` = "' . $this->db->escape($hash) . '"';

			$data = $this->db->query($sql);

			return (isset($data[0])) ? $this->initOne($data[0]) : null;
		}

		public function setIsConfirmedByAccountId($account_id)
		{
			$this->orm_model->update(array('is_confirmed' => 1), 'account_id = "' . (int)$account_id . '"');
		}

	}
