<?php
    class ImageSearchApiFactory
    {
        /**
         * @var BingSearchApi $api
         */

        private static $api = null;

        public static function getInstance()
        {
            if(!isset(self::$api) || self::$api->getTransactionsCount() >= 5000)
            {
                /**
                 * @var BingKeysManager $bing_keys_manager
                 * @var BingKeysModel $bing_keys
                 */

                $bing_keys_manager = ModelManagerFactory::getByName('bing_keys');
                $bing_keys = $bing_keys_manager->getOneWithMinimalTransactionsCount();

                self::$api = new BingSearchApi($bing_keys->id, $bing_keys->key, $bing_keys->transactions_count);
            }

            return self::$api;
        }
    }