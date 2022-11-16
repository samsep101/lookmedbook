<?php
	class MailruAccountManager extends ModelManager
	{
		protected $table_name = 'mailru_account';
		protected $model_name = 'MailruAccountModel';

        /**
		 * @return MailruAccountModel
		 */
		public function getOneByUid($uid)
		{
			$data = $this->orm_model->select()->where('uid = ?', $this->db->escape($uid))->fetchOne();
			return (isset($data)) ? $this->initOne($data) : null;
		}

        /**
		 * @return MailruAccountModel
		 */
		public function getOneByAccountId($account_id)
		{
			$data = $this->orm_model->select()->where('account_id = ?', (int)$account_id)->fetchOne();
			return (isset($data)) ? $this->initOne($data) : null;
		}
	}
