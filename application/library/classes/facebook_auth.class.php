<?php
class FacebookAuth extends OauthService
{
    public function getToken($code)
    {
        setcookie("referrer", $_SERVER['DOCUMENT_ROOT']);

        $url = 'https://graph.facebook.com/oauth/access_token?client_id='.$this->client_id.'&client_secret='.$this->client_secret.'&code='.$code.'&redirect_uri='.$this->redirect_uri;
        $result = CurlRequestSender::get($url);
        //Test::dump($result);
		$result = explode('&', $result);
		$token = explode('=', $result[0]);

		$token = $token[1];


        return $token;
    }
    
	public function getUserData($token)
    {
        if (!$token) return FALSE;

        $url = 'https://graph.facebook.com/me?fields=id,first_name,last_name,middle_name,username,gender,link,birthday,location,photo,email&access_token='.$token;
        $user_data = CurlRequestSender::get($url);
		$user_data = json_decode($user_data, TRUE);

        $user_data['photo'] = CurlRequestSender::getRedirectFollowLocation('https://graph.facebook.com/'.$user_data['username'].'/picture?height=400&width=400');
        if (isset($user_data['location'])) $user_data['country'] = $this->getCountryName($user_data['location']['name']);
        if (isset($user_data['location'])) $user_data['city'] = $this->getCityName($user_data['location']['name']);

        //получаем city_id
        if (isset($user_data['city'])) {
            $city_manager = new CityManager();
            $city = $city_manager->getOneByName($user_data['city']);
            $user_data['city_id'] = (isset($city) && $city->id) ? $city->id : '';
        } else {
            $user_data['city_id'] = '';
        }

        return ($user_data) ? $user_data : array();
    }

    public static function getUserDataForApi($token)
    {
        $url = 'https://graph.facebook.com/me?fields=id,first_name,last_name,middle_name,username,gender,link,birthday,location,photo,email&access_token='.$token;
        $user_data = CurlRequestSender::get($url);
        $user_data = json_decode($user_data, TRUE);

        $user_data['photo'] = CurlRequestSender::getRedirectFollowLocation('https://graph.facebook.com/'.$user_data['username'].'/picture?height=400&width=400');
        if (isset($user_data['location'])) $user_data['country'] = self::getCountryName($user_data['location']['name']);
        if (isset($user_data['location'])) $user_data['city'] = self::getCityName($user_data['location']['name']);

        //получаем city_id
        if (isset($user_data['city'])) {
            $city_manager = new CityManager();
            $city = $city_manager->getOneByName($user_data['city']);
            $user_data['city_id'] = (isset($city) && $city->id) ? $city->id : '';
        } else {
            $user_data['city_id'] = '';
        }

        return ($user_data) ? $user_data : array();
    }

    public function getAllUserData($token)
    {
        if (!$token) return FALSE;

        $url = 'https://graph.facebook.com/me?access_token='.$token;
        $user_data = CurlRequestSender::get($url);
        $user_data = json_decode($user_data, TRUE);

        $user_data['hometown'] = (isset($user_data['hometown']['name'])) ? $user_data['hometown']['name'] : ''; // родной горож

        //партнер
        $user_data['relation_partner_uid'] = (isset($user_data['significant_other']['id'])) ? $user_data['significant_other']['id'] : '';
        $user_data['relation_partner_name'] = (isset($user_data['significant_other']['name'])) ? $user_data['significant_other']['name'] : '';

        return (isset($user_data) && $user_data) ? $user_data : array();
    }

    public function getUserFriends($token)
    {
        if (!$token) return FALSE;

        $url = 'https://graph.facebook.com/me/friends?fields=first_name,last_name,link&access_token='.$token;
        $user_data = CurlRequestSender::get($url);
        $user_data = json_decode($user_data, TRUE);

        return (isset($user_data['data'])) ? $user_data['data'] : array();

    }

    public function getUserGroups($token)
    {
        if (!$token) return FALSE;

        $url = 'https://graph.facebook.com/me/groups?access_token='.$token;
        $user_data = CurlRequestSender::get($url);
        $user_data = json_decode($user_data, true);

        return (isset($user_data['data'])) ? $user_data['data'] : array();
    }

    public function getUserInterests($token)
    {
        if (!$token) return FALSE;

        $url = 'https://graph.facebook.com/me/interests?access_token='.$token;
        $user_data = CurlRequestSender::get($url);
        $user_data = json_decode($user_data, true);

        return (isset($user_data['data'])) ? $user_data['data'] : array();
    }

    public function getUserLikes($token)
    {
        if (!$token) return FALSE;

        $url = 'https://graph.facebook.com/me/likes?access_token='.$token;
        $user_data = CurlRequestSender::get($url);
        $user_data = json_decode($user_data, true);

        return (isset($user_data['data'])) ? $user_data['data'] : array();
    }

    public function getUserBooks($token)
    {
        if (!$token) return FALSE;

        $url = 'https://graph.facebook.com/me/books?access_token='.$token;
        $user_data = CurlRequestSender::get($url);
        $user_data = json_decode($user_data, true);

        return (isset($user_data['data'])) ? $user_data['data'] : array();
    }

    public function getUserMovies($token)
    {
        if (!$token) return FALSE;

        $url = 'https://graph.facebook.com/me/movies?access_token='.$token;
        $user_data = CurlRequestSender::get($url);
        $user_data = json_decode($user_data, true);

        return (isset($user_data['data'])) ? $user_data['data'] : array();
    }

    public function getUserMusics($token)
    {
        if (!$token) return FALSE;

        $url = 'https://graph.facebook.com/me/music?access_token='.$token;
        $user_data = CurlRequestSender::get($url);
        $user_data = json_decode($user_data, true);

        return (isset($user_data['data'])) ? $user_data['data'] : array();
    }

    public function getUserTelevisions($token)
    {
        if (!$token) return FALSE;

        $url = 'https://graph.facebook.com/me/television?access_token='.$token;
        $user_data = CurlRequestSender::get($url);
        $user_data = json_decode($user_data, true);

        return (isset($user_data['data'])) ? $user_data['data'] : array();
    }

    public function getUserUid($token)
    {
        if (!$token) return FALSE;

        $url = 'https://graph.facebook.com/me?access_token='.$token;
        $userData = CurlRequestSender::get($url);
		$userData = json_decode($userData, true);

        return (isset($userData['id'])) ? $userData['id'] : false;
    }
    
    private function getCountryName($name)
    {
        if (preg_match('/^[^,]+,(.+)$/', $name, $matches))
        {
            $en_country_name = trim($matches[1]);

            switch($en_country_name)
            {
                case 'Belarus':
                    return 'Беларусь';
                case 'Russia':
                    return 'Россия';
            }

            return $en_country_name;
        } else {
            return '';
        }
    }

    private function getCityName($name)
    {
        if (preg_match('/^([^,]+),[^,]+$/', $name, $matches))
        {
            $en_city_name = trim($matches[1]);

            switch($en_city_name)
            {
                case 'Minsk':
                    return 'Минск';
                case 'Moscow':
                    return 'Москва';
            }
            return $en_city_name;
        } else {
            return '';
        }
    }
}