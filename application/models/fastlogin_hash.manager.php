<?php
	class FastloginHashManager extends ModelManager
	{
		protected $table_name = "fastlogin_hash";
		protected $model_name = "FastloginHashModel";


		/**
		 * @var int $account_id
		 * @return FastloginHashModel[]
		 */
		public function getListByAccountId($account_id)
		{
			$data = $this->orm_model->select()->where('account_id = ?', $account_id)->fetchAll();
			return $this->initList($data);
		}

		/**
		 * @var int $hash
		 * @return FastloginHashModel[]
		 */
		public function getListByHash($hash)
		{
			$data = $this->orm_model->select()->where('hash = ?', $hash)->fetchAll();
			return $this->initList($data);
		}

	}