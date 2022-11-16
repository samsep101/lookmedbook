<?php
	class MobileNotificationTokenManager extends ModelManager
	{
		protected $table_name = 'mobile_notification_token';
		protected $model_name = 'MobileNotificationTokenModel';


		/**
		 * @param $account_id
		 * @return MobileNotificationTokenModel
		 */
		public function getOneByAccountId($account_id)
		{
			$sql = 'SELECT *
					FROM '.$this->table_name.'
					WHERE account_id = '.(int)$account_id.'
					LIMIT 1';

			$data = $this->db->query($sql);

			if(!$data)
				return null;

			return $this->initOne($data[0]);
		}

		/**
		 * @param $timezone
		 * @return MobileNotificationTokenModel[]
		 */
		public function getListByTimezone($timezone)
		{
			$sql = 'SELECT *
					FROM '.$this->table_name.'
					WHERE timezone = '.(int)$timezone;

			$data = $this->db->query($sql);

			return $this->initList($data);
		}
	}