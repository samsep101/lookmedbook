<?php
	class DbHelper {

		public static function getTablesList()
		{
			$db = Register::get('db');

			$data = $db->query('SHOW TABLES');


			$result = array();
			foreach ($data as $k => $v) {
				foreach ($v as $v1) {
					$result[] = $v1;
				}
			}

			return $result;
		}

		public static function getTableFields($table_name)
		{
			$db = Register::get('db');
			$data = $db->query('SHOW COLUMNS FROM `' . $table_name . '`');

			return $data;
		}
	}