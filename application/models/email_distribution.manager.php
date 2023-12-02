<?php
	class EmailDistributionManager extends ModelManager
	{
		protected $table_name = 'email_distribution';
		protected $model_name = 'EmailDistributionModel';

		public function getOneByAccountId($account_id)
		{
			$data = $this->orm_model->select()->where('account_id = ?', $account_id)->fetchOne();
			return $this->initOne($data);
		}
	}