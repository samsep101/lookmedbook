<?php

/**
 * Класс для авторизации пользователя на сайте через Mail.ru
 */
 
class MailruAuth extends OauthService
{    
    public function getToken($code)
    {
        $destination = (isset($_GET['destination'])) ? '?destination='.$_GET['destination'] : '';
        $url = 'https://connect.mail.ru/oauth/token';
        $fields = 'client_id='.$this->client_id.'&client_secret='.$this->client_secret.'&grant_type=authorization_code&code='.$code.'&redirect_uri='.$this->redirect_uri . urlencode($destination);
        $result = CurlRequestSender::post($url,$fields);
        $result = json_decode($result, true);

        return (isset($result['access_token'])) ? $result['access_token'] : false;
    }
    
    public function getUserData($token)
    {
        $sign = md5('app_id='.$this->client_id.'method=users.getInfosecure=1session_key='.$token.$this->client_secret);

        $url = 'http://www.appsmail.ru/platform/api?method=users.getInfo&secure=1&app_id='.$this->client_id.'&session_key='.$token.'&sig='.$sign;
        $userData = CurlRequestSender::get($url);
        $userData = json_decode($userData, true);

        // установка города
        if (count($userData))
        {
            foreach($userData as $key => $value)
            {
                $user_city = (isset($userData[$key]['location']['city']['name'])) ? $userData[$key]['location']['city']['name'] : '';
                if ($user_city) {
                    $city_manager = new CityManager();
                    $city = $city_manager->getOneByName($user_city);
                    $userData[$key]['city_id'] = (isset($city) && $city->id) ? $city->id : '';
                } else {
                    $userData[$key]['city_id'] = '';
                }
            }
        }

        return (isset($userData)) ? $userData[0] : false;
    }

    public static function getUserDataForApi($token)
    {
        $client_id = SettingsManager::get('mailru_client_id');
        $client_secret = SettingsManager::get('mailru_client_secret');

        $sign = md5('app_id='.$client_id.'method=users.getInfosecure=1session_key='.$token.$client_secret);

        $url = 'http://www.appsmail.ru/platform/api?method=users.getInfo&secure=1&app_id='.$client_id.'&session_key='.$token.'&sig='.$sign;
        $userData = CurlRequestSender::get($url);
        $userData = json_decode($userData, true);

        // установка города
        if (count($userData))
        {
            foreach($userData as $key => $value)
            {
                $user_city = (isset($userData[$key]['location']['city']['name'])) ? $userData[$key]['location']['city']['name'] : '';
                if ($user_city) {
                    $city_manager = new CityManager();
                    $city = $city_manager->getOneByName($user_city);
                    $userData[$key]['city_id'] = (isset($city) && $city->id) ? $city->id : '';
                } else {
                    $userData[$key]['city_id'] = '';
                }
            }
        }

        return ($userData) ? $userData[0] : false;
    }

    public function getUserFriends($token)
    {
        $sign = md5('app_id='.$this->client_id.'ext=1method=friends.getsecure=1session_key='.$token.$this->client_secret);

        $url = 'http://www.appsmail.ru/platform/api?method=friends.get&ext=1&app_id='.$this->client_id.'&session_key='.$token.'&secure=1&sig='.$sign;
        $userData = CurlRequestSender::get($url);
        $userData = json_decode($userData, true);

        return (isset($userData)) ? $userData : false;
    }
}
