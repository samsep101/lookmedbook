<?php
	/**
	 * @property int $id
	 * @property int $account_id
	 * @property AccountModel $account
	 * @property int $sms_notify
	 * @property int $sms_notify_phone_id
	 * @property AccountPhoneModel $sms_notify_phone
	 * @property int $sms_notify_visit
	 * @property int $sms_notify_change
	 * @property int $sms_notify_news
	 * @property int $email_notify_visit
	 * @property int $email_notify_bonus
	 * @property int $email_notify_change
	 */
	class NotificationSettingsModel extends DynamicModel
	{
		public function _field_sms_notify_phone()
		{
			if(!isset($this->sms_notify_phone))
			{
				$account_phone_manager = new AccountPhoneManager();
				$this->sms_notify_phone = $account_phone_manager->getOneById($this->sms_notify_phone_id);
			}

			return $this->sms_notify_phone;
		}
	}