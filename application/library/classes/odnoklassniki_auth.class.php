<?php

    /**
     * Класс для авторизации пользователя на сайте через ВКонтакте
     */

    class OdnoklassnikiAuth extends OauthService
    {
        private $public_key;

        public function __construct($client_id, $client_secret, $redirect_uri, $public_key)
        {
            $this->public_key = $public_key;

            parent::__construct($client_id, $client_secret, $redirect_uri);
        }

        public function getToken($code)
        {
            $destination = (isset($_GET['destination'])) ? '?destination='.$_GET['destination'] : '';

            $url = 'http://api.odnoklassniki.ru/oauth/token.do';
            $post_fields = 'code='.$code.'&redirect_uri='.$this->redirect_uri . urlencode($destination).'&grant_type=authorization_code&client_id='.$this->client_id.'&client_secret='.$this->client_secret;
            $result = CurlRequestSender::post($url, $post_fields);

            $result = json_decode($result, true);

            return $result['access_token'];
        }

        public function getUserData($token)
        {
            $sign = md5('application_key='.$this->public_key.'method=users.getCurrentUser'.md5($token.$this->client_secret));
            $url = 'http://api.odnoklassniki.ru/fb.do?method=users.getCurrentUser&access_token='.$token.'&application_key='.$this->public_key.'&sig='.$sign;
            $userData = CurlRequestSender::get($url);

            $userData = json_decode($userData, true);

            $userData['country'] = (isset($userData['location']['country'])) ? $this->getCountryName($userData['location']['country']) : '';

            if (isset($userData['location']['city'])) {
                $city_manager = new CityManager();
                $city = $city_manager->getOneByName($userData['location']['city']);
                $userData['city_id'] = (isset($city) && $city->id) ? $city->id : '';
            } else {
                $userData['city_id'] = '';
            }

            // Получаем большую фотку
            $userData['photo'] = preg_replace('/photoType=[0-9]+/', 'photoType=100', $userData['pic_2']);
            return $userData;
        }

        public static function getUserDataForApi($token)
        {
            $ok_client_id = SettingsManager::get('ok_client_id');
            $ok_client_secret = SettingsManager::get('ok_client_secret');
            $public_key = SettingsManager::get('ok_public_key');

            $sign = md5('application_key='.$public_key.'method=users.getCurrentUser'.md5($token.$ok_client_secret));
            $url = 'http://api.odnoklassniki.ru/fb.do?method=users.getCurrentUser&access_token='.$token.'&application_key='.$public_key.'&sig='.$sign;
            $userData = CurlRequestSender::get($url);

            $userData = json_decode($userData, true);

            $userData['country'] = (isset($userData['location']['country'])) ? self::getCountryName($userData['location']['country']) : '';

            if (isset($userData['location']['city'])) {
                $city_manager = new CityManager();
                $city = $city_manager->getOneByName($userData['location']['city']);
                $userData['city_id'] = (isset($city) && $city->id) ? $city->id : '';
            } else {
                $userData['city_id'] = '';
            }

            // Получаем большую фотку
            $userData['photo'] = preg_replace('/photoType=[0-9]+/', 'photoType=100', $userData['pic_2']);
            return $userData;
        }

        public function getUserFriends($token)
        {
            $sign = md5('application_key='.$this->public_key.'method=friends.get'.md5($token.$this->client_secret));
            $url = 'http://api.odnoklassniki.ru/fb.do?method=friends.get&access_token='.$token.'&application_key='.$this->public_key.'&sig='.$sign;
            $userData = CurlRequestSender::get($url);

            $userData = json_decode($userData, true);

            return $userData;
        }

        private function getCountryName($country_name)
        {
            switch($country_name)
            {
                case 'BELARUS':
                    return 'Беларусь';
                case 'RUSSIA':
                    return 'Россия';
                default:
                    return '';
            }
        }
    }
