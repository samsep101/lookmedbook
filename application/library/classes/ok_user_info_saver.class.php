<?php

    class OkUserInfoSaver
    {

        public function save($token, $account_id)
        {
            $ok_client_id = SettingsManager::get('ok_client_id');
            $ok_client_secret = SettingsManager::get('ok_client_secret');
            $public_key = SettingsManager::get('ok_public_key');
            $redirect_url = SITE_URL . '/account/joinOkAccount';

            $ok_auth = new OdnoklassnikiAuth($ok_client_id, $ok_client_secret, $redirect_url, $public_key);

            $user_data = $ok_auth->getUserData($token);

            $ok_account_manager = new OkAccountManager();
            $ok_account = $ok_account_manager->getOneByUid($user_data['uid']);

            if (!$ok_account)
                $ok_account = new OkAccountModel();

            if (!$ok_account->is_account_connected || !isset($ok_account->is_account_connected)) {

                $this->saveMainOkInfo($ok_account, $user_data, $account_id); //общая инфа
                $this->saveFriendsOkInfo($ok_account, $user_friends = $ok_auth->getUserFriends($token)); // инфа о друзьях

                return TRUE;
            }
        }

        private function saveMainOkInfo($ok_account, $user_data, $account_id)
        {
            $ok_account->account_id = $account_id;
            $ok_account->uid = $user_data['uid'];
            $ok_account->first_name = (isset($user_data['first_name'])) ? $user_data['first_name'] : '';
            $ok_account->last_name = (isset($user_data['last_name'])) ? $user_data['last_name'] : '';
            $ok_account->profile_url = 'http://odnoklassniki.ru/profile/' . $user_data['uid'];
            $ok_account->age = (isset($user_data['age'])) ? $user_data['age'] : '';
            $ok_account->is_account_connected = 1;

            ModelManagerFactory::getByName('ok_account')->save($ok_account);
        }

        private function saveFriendsOkInfo($ok_account, $user_friends)
        {
            //друзья
            if (count($user_friends)) {
                foreach ($user_friends as $user_uid) {
                    $ok_account_friend = new OkAccountFriendModel();
                    $ok_account_friend->ok_account_id = $ok_account->getId();
                    $ok_account_friend->uid = (isset($user_uid)) ? $user_uid : '';
                    $ok_account_friend->profile_url = (isset($user_uid)) ? 'http://www.odnoklassniki.ru/profile/' . $user_uid : '';

                    ModelManagerFactory::getByName('ok_account_friend')->save($ok_account_friend);
                }
            }
        }

    }