<?php
	class FastLoginHashManager extends ModelManager
	{
		protected $table_name = 'fastlogin_hash';
		protected $model_name = 'FastLoginHashModel';

		/**
		 * @param $account_id
		 * @return FastLoginHashModel[]
		 */
		public function getListByAccountId($account_id)
		{
			$data = $this->orm_model->select()->where('account_id = ?', $account_id)->fetchAll();
			return $this->initList($data);
		}

		/**
		 * @param $hash
		 * @return FastLoginHashModel
		 */
		public function getOneByHash($hash)
		{
			$data = $this->orm_model->select()->where('hash = ?', $hash)->fetchOne();

			return $this->initOne($data);
		}
	}