<?php
    class PhoneConfirmCodeGeneratorHelper
    {
        public static function generate()
        {
            if(SettingsManager::get('send_sms_flag'))
            {
                return 555;
            } else {
                return StringGeneratorHelper::generateNumbers(3);
            }
        }
    }