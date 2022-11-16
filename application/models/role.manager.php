<?php

	class RoleManager extends ModelManager
	{

		protected $table_name = 'role';
		protected $model_name = 'RoleModel';


		public function getRoleGrantsById($role_id)
		{
			$grant_manager = new GrantManager();
			$grants = $grant_manager->getListByRoleId($role_id);

			return $grants;
		}

		public static function getRolesForCalc()
		{
			$db = Register::get('db');
			$sql = "select rl.*, (select count(*) from " . DB_PREFIX . "user where role_id = rl.id) countUsers
		from " . DB_PREFIX . "role rl
		where production_involved = 1 order by name";
			return $db->query($sql);
		}

		public static function getUserRole($userId)
		{
			$db = Register::get('db');
			$sql = "select *
		from " . DB_PREFIX . "role rl
		where 
		id = (select id from " . DB_PREFIX . "user where id = $userId)";
			$data = $db->query($sql);
			return count($data) ? $data : null;
		}

		public static function getRolesDirectByParentId($userId)
		{
			$db = Register::get('db');
			$sql = "
                select rld.id,
                    rld.role_id,
                    rld.director_id,
                    (select name from " . DB_PREFIX . "role where id = rld.role_id) role
                from " . DB_PREFIX . "role_direct rld
                where rld.director_id = $userId";
			return $db->query($sql);
		}

		public static function countAll()
		{
			$db = Register::get('db');
			$sql = "SELECT COUNT(*) cnt
		            from " . DB_PREFIX . "role ";
			$return = $db->query($sql);
			return $return[0]['cnt'];
		}


	}