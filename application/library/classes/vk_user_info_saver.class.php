<?php

    class VkUserInfoSaver
    {

        public function save($token,$account_id, $uid)
        {
            $vk_client_id = SettingsManager::get('vk_client_id');
            $vk_client_secret = SettingsManager::get('vk_client_secret');
            $redirect_url = SITE_URL . '/account/joinVkAccount';

            $vk_auth = new VkontakteAuth($vk_client_id, $vk_client_secret, $redirect_url);

            $data = array(
                'access_token' => $token,
                'user_id'      => $uid
            );
            $user_data = $vk_auth->getUserData($data); //инфа о текущем пользователе

            $user_friends = $vk_auth->getUserFriends($data); //инфа о друзьях текущего пользователя
            $user_groups = $vk_auth->getUserGroups($data); //инфа о группах текущего пользователя

            if (count($user_groups)) {
                array_splice($user_groups, 0, 1); // убираем 1 элемень массива, содержащим кол-во групп
            }

            $vk_account_manager = new VkAccountManager();

            $vk_account = $vk_account_manager->getOneByUid($user_data['uid']);

            if (!$vk_account)
                $vk_account = new VkAccountModel();

            if (!$vk_account->is_account_connected || !isset($vk_account->is_account_connected)) {

                $this->saveMainVkInfo($vk_account, $user_data, $account_id); //общая инфа

                $this->saveUniverseVkInfo($vk_account, $user_data); //инфо о университетах

                $this->saveSchoolVkInfo($vk_account, $user_data); // инфо о школах

                $this->saveRelativeVkInfo($vk_account, $user_data); // инфо о родственниках

                $this->saveFriendsVkInfo($vk_account, $user_friends); // инфо о друзьях

                $this->saveGroupsVkInfo($vk_account, $user_groups); //инфо о группах

                return TRUE;
            }
        }

        private function saveMainVkInfo($vk_account, $user_data, $account_id)
        {
            $vk_account->uid = $user_data['uid'];
            $vk_account->account_id = $account_id;
            $vk_account->first_name = $user_data['first_name'];
            $vk_account->last_name = $user_data['last_name'];
            $vk_account->profile_url = (isset($user_data['domain']) && $user_data['domain']) ? 'http://vk.com/' . $user_data['domain'] : '';
            $vk_account->home_phone = (isset($user_data['home_phone'])) ? $user_data['home_phone'] : '';
            $vk_account->activity = (isset($user_data['activity'])) ? $user_data['activity'] : '';
            $vk_account->count_groups = (isset($user_data['counters']['groups'])) ? $user_data['counters']['groups'] : null;
            $vk_account->count_friends = (isset($user_data['counters']['friends'])) ? $user_data['counters']['friends'] : null;
            $vk_account->relation_type = (isset($user_data['relation'])) ? $user_data['relation'] : '';
            $vk_account->relation_partner_uid = (isset($user_data['relation_partner']['id'])) ? $user_data['relation_partner']['id'] : '';
            $vk_account->relation_partner_first_name = (isset($user_data['relation_partner']['first_name'])) ? $user_data['relation_partner']['first_name'] : '';
            $vk_account->relation_partner_last_name = (isset($user_data['relation_partner']['last_name'])) ? $user_data['relation_partner']['last_name'] : '';
            $vk_account->interests = (isset($user_data['interests'])) ? $user_data['interests'] : '';
            $vk_account->movies = (isset($user_data['movies'])) ? $user_data['movies'] : '';
            $vk_account->tv = (isset($user_data['tv'])) ? $user_data['tv'] : '';
            $vk_account->books = (isset($user_data['books'])) ? $user_data['books'] : '';
            $vk_account->games = (isset($user_data['games'])) ? $user_data['games'] : '';
            $vk_account->about = (isset($user_data['about'])) ? $user_data['about'] : '';
            $vk_account->is_account_connected = 1;

            ModelManagerFactory::getByName('vk_account')->save($vk_account);
        }

        //todo: city_id
        private function saveUniverseVkInfo($vk_account, $user_data)
        {
            //университеты
            if (isset($user_data['universities']) && count($user_data['universities'])) {
                foreach ($user_data['universities'] as $user_university) {
                    $vk_account_university = new VkAccountUniversityModel();
                    $vk_account_university->vk_account_id = $vk_account->getId();
                    $vk_account_university->city_id = null;
                    $vk_account_university->name = (isset($user_university['name'])) ? $user_university['name'] : '';
                    $vk_account_university->faculty_name = (isset($user_university['faculty_name'])) ? $user_university['faculty_name'] : '';
                    $vk_account_university->chair_name = (isset($user_university['chair_name'])) ? $user_university['chair_name'] : '';
                    $vk_account_university->year_graduation = (isset($user_university['graduation'])) ? $user_university['graduation'] : null;

                    ModelManagerFactory::getByName('vk_account_university')->save($vk_account_university);
                }
            }
        }

        //todo: city_id
        private function saveSchoolVkInfo($vk_account, $user_data)
        {
            //школы и колледжы
            if (isset($user_data['schools']) && count($user_data['schools'])) {
                foreach ($user_data['schools'] as $user_school) {
                    $vk_account_school = new VkAccountSchoolModel();
                    $vk_account_school->vk_account_id = $vk_account->getId();
                    $vk_account_school->city_id = null;
                    $vk_account_school->name = (isset($user_school['name'])) ? $user_school['name'] : '';
                    $vk_account_school->year_start = (isset($user_school['year_from'])) ? $user_school['year_from'] : null;
                    $vk_account_school->year_end = (isset($user_school['year_to'])) ? $user_school['year_to'] : null;
                    $vk_account_school->year_graduated = (isset($user_school['year_graduated'])) ? $user_school['year_graduated'] : null;
                    $vk_account_school->speciality = (isset($user_school['speciality'])) ? $user_school['speciality'] : '';
                    $vk_account_school->class = (isset($user_school['class'])) ? $user_school['class'] : '';

                    ModelManagerFactory::getByName('vk_account_school')->save($vk_account_school);
                }
            }
        }

        private function saveRelativeVkInfo($vk_account, $user_data)
        {
            //родственники
            if (isset($user_data['relatives']) && count($user_data['relatives'])) {
                foreach ($user_data['relatives'] as $user_relative) {
                    $vk_account_relative = new VkAccountRelativeModel();
                    $vk_account_relative->vk_account_id = $vk_account->getId();
                    $vk_account_relative->relative_uid = $user_relative['uid'];
                    $vk_account_relative->relative_type = $user_relative['type'];

                    ModelManagerFactory::getByName('vk_account_relative')->save($vk_account_relative);
                }
            }
        }

        private function saveFriendsVkInfo($vk_account, $user_friends)
        {
            //друзья
            if (count($user_friends)) {
                foreach ($user_friends as $user_friend) {
                    $vk_account_friend = new VkAccountFriendModel();
                    $vk_account_friend->vk_account_id = $vk_account->getId();
                    $vk_account_friend->uid = (isset($user_friend['uid'])) ? $user_friend['uid'] : '';
                    $vk_account_friend->first_name = (isset($user_friend['first_name'])) ? $user_friend['first_name'] : '';
                    $vk_account_friend->last_name = (isset($user_friend['last_name'])) ? $user_friend['last_name'] : '';
                    $vk_account_friend->profile_url = (isset($user_friend['domain']) && $user_friend['domain']) ? 'http://vk.com/' . $user_friend['domain'] : '';

                    ModelManagerFactory::getByName('vk_account_friend')->save($vk_account_friend);
                }
            }
        }

        private function saveGroupsVkInfo($vk_account, $user_groups)
        {
            //группы
            if (count($user_groups)) {
                foreach ($user_groups as $user_group) {
                    $vk_account_group = new VkAccountGroupModel();
                    $vk_account_group->vk_account_id = $vk_account->getId();
                    $vk_account_group->gid = (isset($user_group['gid'])) ? $user_group['gid'] : '';
                    $vk_account_group->name = (isset($user_group['name'])) ? $user_group['name'] : '';
                    $vk_account_group->group_url = (isset($user_group['screen_name'])) ? 'http://vk.com/' . $user_group['screen_name'] : '';
                    $vk_account_group->description = (isset($user_group['description'])) ? $user_group['description'] : '';
                    $vk_account_group->type = (isset($user_group['type'])) ? $user_group['type'] : '';
                    $vk_account_group->members_count = (isset($user_group['members_count'])) ? $user_group['members_count'] : '';
                    $vk_account_group->is_closed = (isset($user_group['is_closed'])) ? $user_group['is_closed'] : null;
                    $vk_account_group->is_member = (isset($user_group['is_member'])) ? $user_group['is_member'] : null;
                    $vk_account_group->is_admin = (isset($user_group['is_admin'])) ? $user_group['is_admin'] : null;
                    $vk_account_group->is_can_post = (isset($user_group['can_post'])) ? $user_group['can_post'] : null;

                    ModelManagerFactory::getByName('vk_account_group')->save($vk_account_group);
                }
            }
        }


    }