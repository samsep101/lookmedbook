<?php

	class ShopManagerStatisticManager
	{
		/**
		 * @var Db
		 */
		protected $db;

		public function __construct()
		{
			$this->db = Register::get('db');
		}

		public function getStatisticByUsers()
		{

			$sql = 'SELECT user_id, date, SUM(is_image_uploaded) as uploaded_image_count,
					SUM(is_status_confirmed) as status_confirmed_count, b.login
					FROM shop_contentmanager_log a
					INNER JOIN user b ON a.user_id = b.id
					WHERE b.role_id = '.(int)RoleModel::ESHOP_CONTENT_MANAGER.'
					GROUP BY user_id, date
					ORDER BY date DESC';


			$data = $this->db->query($sql);

			$result = array();

			if($data)
			{
				foreach($data as $v)
				{
					if(!isset($result[$v['login']]))
					{
						$result[$v['login']] = array();
					}

					$result[$v['login']][] = $v;
				}
			}

			return $result;
		}
	}