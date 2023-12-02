<?php

    /**
     * Класс для авторизации пользователя на сайте через ВКонтакте
     */

    class VkontakteAuth extends OauthService
    {
        public function getToken($code)
        {
            $destination = (isset($_GET['destination'])) ? '?destination='.$_GET['destination'] : '';
            $url = 'https://oauth.vk.com/access_token?client_id=' . $this->client_id . '&client_secret=' . $this->client_secret . '&redirect_uri=' . $this->redirect_uri . urlencode($destination ). '&code=' . $code;
            $result = CurlRequestSender::get($url);
            $result = json_decode($result, TRUE);

            return $result;
        }

        public function getUserData($token)
        {
            $data = $token;

            $token = $data['access_token'];
            $user_id = $data['user_id'];

            $url = 'https://api.vk.com/method/users.get?uid=' . $user_id . '&fields=first_name,last_name,domain,sex,bdate,photo_max_orig,city,country,nickname,screen_name,rate,contacts,universities,schools,relation,relatives,interests,movies,tv,books,games,about,counters&access_token=' . $token;
            $user_data = CurlRequestSender::get($url);

            $user_data = json_decode($user_data, TRUE);
            $user_data = $user_data['response']['0'];

            $user_data['country'] = self::getCountryNameById($user_data['country'], $token);
            $user_data['city'] = self::getCityNameById($user_data['city'], $token);

            //получаем city_id
            if (isset($user_data['city'])) {
                $city_manager = new CityManager();
                $city = $city_manager->getOneByName($user_data['city']);
                $user_data['city_id'] = (isset($city) && $city->id) ? $city->id : '';
            } else {
                $user_data['city_id'] = '';
            }

            if (isset($user_data['relation']))
            {
                $user_data['relation'] = $this->getUserRelation($user_data['relation']);
            }

            // Получаем большую фотку
            $user_data['photo'] = self::getBigProfilePhoto($user_data['uid'], $token);

            return $user_data;
        }

        public static function getUserDataForApi($token,$userd_id)
        {
            $token = $token;
            $user_id = $userd_id;

            $url = 'https://api.vk.com/method/users.get?uid=' . $user_id . '&fields=first_name,last_name,domain,sex,bdate,photo_max_orig,city,country,nickname,screen_name,rate,contacts,universities,schools,relation,relatives,interests,movies,tv,books,games,about,counters&access_token=' . $token;
            $user_data = CurlRequestSender::get($url);

            $user_data = json_decode($user_data, TRUE);
            $user_data = $user_data['response']['0'];

            $user_data['country'] = self::getCountryNameById($user_data['country'], $token);
            $user_data['city'] = self::getCityNameById($user_data['city'], $token);

            //получаем city_id
            if (isset($user_data['city'])) {
                $city_manager = new CityManager();
                $city = $city_manager->getOneByName($user_data['city']);
                $user_data['city_id'] = (isset($city) && $city->id) ? $city->id : '';
            } else {
                $user_data['city_id'] = '';
            }

            if (isset($user_data['relation']))
            {
                $user_data['relation'] = self::getUserRelation($user_data['relation']);
            }

            // Получаем большую фотку
            $user_data['photo'] = self::getBigProfilePhoto($user_data['uid'], $token);

            return $user_data;
        }

        public function getUserFriends($token)
        {
            $data = $token;

            $token = $data['access_token'];
            $user_id = $data['user_id'];

            $url = 'https://api.vk.com/method/friends.get?uid=' . $user_id . '&fields=first_name,last_name,domain&access_token=' . $token;
            $user_data = CurlRequestSender::get($url);

            $user_data = json_decode($user_data, TRUE);
            $user_data = $user_data['response'];

            // большое фото для каждого из друзей
            /*foreach ($user_data as $user) {
                $user['photo'] = self::getBigProfilePhoto($user['uid'], $token);
            }*/

            return (isset($user_data)) ? $user_data : array();
        }

        public function getUserGroups($token)
        {
            $data = $token;

            $token = $data['access_token'];
            $user_id = $data['user_id'];

            $url = 'https://api.vk.com/method/groups.get?uid=' . $user_id . '&extended=1&fields=city,country,place,description,members_count,activity,can_post&access_token=' . $token;
            $user_data = CurlRequestSender::get($url);

            $user_data = json_decode($user_data, TRUE);
            $user_data = $user_data['response'];

            return (isset($user_data)) ? $user_data : array();
        }

        public static function getCountryNameById($id, $token)
        {
            $url = 'https://api.vk.com/method/places.getCountryById?cids=' . $id . '&access_token=' . $token;
            ;
            $info = CurlRequestSender::get($url);
            $info = json_decode($info, TRUE);

            return (isset($info['response'][0])) ? $info['response'][0]['name'] : null;
        }

        public static function getCityNameById($id, $token)
        {
            $url = 'https://api.vk.com/method/places.getCityById?cids=' . $id . '&access_token=' . $token;
            ;
            $info = CurlRequestSender::get($url);
            $info = json_decode($info, TRUE);

            return (isset($info['response'][0])) ? $info['response'][0]['name'] : null;
        }


        public static function getBigProfilePhoto($uid, $token)
        {
            $url = 'https://api.vk.com/method/photos.getProfile?uid=' . $uid . '&access_token=' . $token;
            $info = CurlRequestSender::get($url);
            $info = json_decode($info, TRUE);

            if (isset($info['response']) && count($info['response'])) {
                return $info['response'][count($info['response']) - 1]['src_big'];
            } else {
                return FALSE;
            }
        }

        private function getUserRelation($type)
        {
            switch($type)
            {
                case '0':
                    return 'не установлено';
                case '1':
                    return 'не женат/не замужем';
                case '2':
                    return 'есть друг/есть подруга';
                case '3':
                    return 'помолвлен/помолвлена';
                case '4':
                    return 'женат/замужем';
                case '5':
                    return 'всё сложно';
                case '6':
                    return 'в активном поиске';
                case '7':
                    return 'влюблён/влюблена';
            }

            return $type;
        }
    }
