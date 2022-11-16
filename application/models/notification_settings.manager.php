<?php
	class NotificationSettingsManager extends ModelManager
	{
		protected $table_name = 'notification_settings';
		protected $model_name = 'NotificationSettingsModel';

        /**
		 * @return NotificationSettingsModel[]
		 */
		public function getListBySmsNotifyPhoneId($sms_notify_phone_id){
			$data = $this->orm_model->select()->where('sms_notify_phone_id = ?', $sms_notify_phone_id)->fetchAll();
			return $this->initList($data);
		}

        /**
		 * @return NotificationSettingsModel
		 */
		public function getOneByAccountId($account_id)
		{
			$sql = 'SELECT ' . $this->selected_fields . '
                    FROM ' . $this->table_name . '
                    WHERE account_id = ' . (int)$account_id;
			$db = Register::get('db');
			$data = $db->query($sql);

			return (isset($data[0])) ? $this->initOne($data[0]) : null;
		}


        /**
		 * @return NotificationSettingsModel
		 */
		public function getOneByPhoneId($phone_id)
		{
			$sql = 'SELECT ' . $this->selected_fields . '
                    FROM ' . $this->table_name . '
                    WHERE sms_notify_phone_id = ' . (int)$phone_id;
			$db = Register::get('db');
			$data = $db->query($sql);

			return (isset($data[0])) ? $this->initOne($data[0]) : null;
		}

		public function setNotificationSettingsByAccountId($account_id, $phone_id, $sms_notify, $sms_notify_visit, $sms_notify_change, $sms_notify_news, $mail_notify_bonus, $mail_notify_visit, $mail_notify_change)
		{

			$sql = 'UPDATE ' . $this->table_name . '
                    SET ';

			if($phone_id == 0)
			{
				$sql .= 'sms_notify_phone_id = null,';
			}
			else
			{
				$sql .= 'sms_notify_phone_id = ' . $phone_id . ',';
			}

			$sql .= ' sms_notify = ' . $sms_notify . ',
                    sms_notify_visit = ' . $sms_notify_visit . ',
                    sms_notify_change = ' . $sms_notify_change . ',
                    sms_notify_news = ' . $sms_notify_news . ',
                    email_notify_bonus = ' . $mail_notify_bonus . ',
                    email_notify_visit = ' . $mail_notify_visit . ',
                    email_notify_change = ' . $mail_notify_change . '
                    WHERE account_id = ' . $account_id;

			Register::get('db')->query($sql);
			return true;
		}

		public function checkExistsByAccountId($account_id)
		{
			$sql = 'SELECT COUNT(*) as result
                    FROM notification_settings
                    WHERE account_id = "' . $this->db->escape($account_id) . '"';

			$data = $this->db->query($sql);

			return (bool)$data[0]['result'];
		}
	}
