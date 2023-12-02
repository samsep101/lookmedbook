<?php
    class EmailApiFactory {
        private static $api;

        public static function getApi()
        {
            if (!self::$api)
            {
                self::$api = new \Unisender\ApiWrapper\UnisenderApi(SettingsManager::get('unisender_api_key'));
            }

            return self::$api;
        }
    }
