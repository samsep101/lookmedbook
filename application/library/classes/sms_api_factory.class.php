<?php

    class SmsApiFactory
    {
        public static function getSmsApi()
        {
            if(SettingsManager::get('sms_provider') == 'smsaero')
            {
              return new SmsAeroApi(SettingsManager::get('sms_account_login'), SettingsManager::get('sms_account_password'), SettingsManager::get('sms_account_name'));
			} else {
				return new SmsApiLogger();
            }
        }
    }
