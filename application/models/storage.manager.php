<?php
	class StorageManager extends ModelManager
	{
		protected $table_name = 'storage';
		protected $model_name = 'StorageModel';

        /**
		 * @return StorageModel[]
		 */
		public function getListByController($controller){
			$data = $this->orm_model->select()->where('controller = ?', $controller)->fetchAll();
			return count($data) ? $this->initList($data) : array();
		}

        /**
		 * @return StorageModel[]
		 */
		public function getListByAction($action){
			$data = $this->orm_model->select()->where('action = ?', $action)->fetchAll();
			return $this->initList($data);
		}

        /**
		 * @return StorageModel[]
		 */
		public function getListByAccountId($account_id){
			$data = $this->orm_model->select()->where('account_id = ?', $account_id)->fetchAll();
			return $this->initList($data);
		}

        /**
		 * @return StorageModel[]
		 */
		public function getListByDt($dt){
			$data = $this->orm_model->select()->where('dt = ?', $dt)->fetchAll();
			return $this->initList($data);
		}
	}
