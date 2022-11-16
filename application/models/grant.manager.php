<?php
	class GrantManager extends ModelManager
	{
		protected $table_name = 'grant';
		protected $model_name = 'GrantModel';

        /**
		 * @return GrantModel[]
		 */
		public function getListByRoleId($role_id)
		{
			$data = $this->orm_model->select()->where('role_id = ?', $role_id)->fetchAll();
			return $this->initList($data);
		}
	}
