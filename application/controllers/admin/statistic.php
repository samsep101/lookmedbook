<?php

	class StatisticAdminController extends Controller
	{
		public $layout = 'admin';

		public function shop_manager()
		{
			$access_roles = array(
				RoleModel::ESHOP_MANAGER,
				RoleModel::ACCOUNT_ADMIN
			);

			if(!in_array(Acl::userRole(), $access_roles))
			{
				exit();
			}

			$manager = new ShopManagerStatisticManager();

			$this->view->statistic = $manager->getStatisticByUsers();
		}

		public function getShopManagerFile()
		{
			$access_roles = array(
				RoleModel::ESHOP_MANAGER,
				RoleModel::ACCOUNT_ADMIN
			);

			if(!in_array(Acl::userRole(), $access_roles))
			{
				exit();
			}

			$manager = new ShopManagerStatisticManager();

			$statistic = $manager->getStatisticByUsers();

			$result = array();

			foreach($statistic as $user_login => $data)
			{
				$result[] = array($user_login, '', '');
				if($data)
				{
					$result[] = array('Дата', 'Изображений загружено', 'Заполнено информацией');
					foreach($data as $v)
					{
						$result[] = array($v['date'], $v['uploaded_image_count'], $v['status_confirmed_count']);
					}
				}
				$result[] = array('', '', '');
			}

			$csv_generator = new CsvGenerator();
			$csv = $csv_generator->generateFromArray($result);

			PhpHeaderHelper::csv('report.csv');

			echo $csv;
			exit();
		}
	}