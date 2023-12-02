<?php
	class RoleHelper
	{
		public static function getManagerRolesIdList()
		{
			return array(RoleModel::ACCOUNT_MANAGER, RoleModel::FREELANCE_MANAGER);
		}
	}