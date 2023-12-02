<?php
	class SystemAccessIpManager extends ModelManager
	{
		protected $table_name = 'system_access_ip';
		protected $model_name = 'SystemAccessIpModel';


		public function getActiveList()
		{
			$data = $this->orm_model->select()->where('is_active = 1')->fetchAll();
			return count($data) ? $this->initList($data) : array();
		}
	}