<?php
	/**
	 * @property int $id
	 * @property int $role_id
	 * @property RoleModel $role
	 * @property int $controller_id
	 * @property ControllerModel $controller
	 * @property int $list
	 * @property int $add
	 * @property int $edit
	 * @property int $delete
	 * @property int $delete_list
	 * @property int $save
	 *
	 */
	class GrantModel extends DynamicModel
	{
		public function setAllRights()
		{
			$this->list = 1;
			$this->add = 1;
			$this->save = 1;
			$this->edit = 1;
			$this->delete_list = 1;
			$this->delete = 1;
		}

	}