<?php

    class MailruUserInfoSaver
    {

        public function save($token, $account_id)
        {
            $mailru_client_id = SettingsManager::get('mailru_client_id');
            $mailru_client_secret = SettingsManager::get('mailru_client_secret');
            $redirect_url = SITE_URL . '/account/joinMailruAccount';

            $mailru_auth = new MailruAuth ($mailru_client_id, $mailru_client_secret, $redirect_url);

            $user_data = $mailru_auth->getUserData($token);

            $mailru_account_manager = new MailruAccountManager();
            $mailru_account = $mailru_account_manager->getOneByUid($user_data['uid']);

            if (!$mailru_account)
                $mailru_account = new MailruAccountModel();

            if (!$mailru_account->is_account_connected || !isset($mailru_account->is_account_connected)) {

                $this->saveMainMailruInfo($mailru_account, $user_data, $account_id); //общая инфа
                $this->saveFriendsMailruInfo($mailru_account, $user_friends = $mailru_auth->getUserFriends($token)); // инфа о друзьях

                return TRUE;
            }
        }

        private function saveMainMailruInfo($mailru_account, $user_data, $account_id)
        {
            $mailru_account->account_id = $account_id;
            $mailru_account->uid = $user_data['uid'];
            $mailru_account->first_name = (isset($user_data['first_name'])) ? $user_data['first_name'] : '';
            $mailru_account->last_name = (isset($user_data['last_name'])) ? $user_data['last_name'] : '';
            $mailru_account->nick_name = (isset($user_data['nick'])) ? $user_data['nick'] : '';
            $mailru_account->profile_url = (isset($user_data['link'])) ? $user_data['link'] : '';
            $mailru_account->status_text = (isset($user_data['status_text'])) ? $user_data['status_text'] : '';
            $mailru_account->is_account_connected = 1;

            ModelManagerFactory::getByName('mailru_account')->save($mailru_account);
        }


        private function saveFriendsMailruInfo($mailru_account, $user_friends)
        {
            //друзья
            if (count($user_friends)) {
                foreach ($user_friends as $user_friend) {
                    $mailru_account_friend = new MailruAccountFriendModel();
                    $mailru_account_friend->mailru_account_id = $mailru_account->getId();
                    $mailru_account_friend->uid = (isset($user_friend['uid'])) ? $user_friend['uid'] : '';
                    $mailru_account_friend->first_name = (isset($user_friend['first_name'])) ? $user_friend['first_name'] : '';
                    $mailru_account_friend->last_name = (isset($user_friend['last_name'])) ? $user_friend['last_name'] : '';
                    $mailru_account_friend->profile_url = (isset($user_friend['link'])) ? $user_friend['link'] : '';

                    ModelManagerFactory::getByName('mailru_account_friend')->save($mailru_account_friend);
                }
            }
        }

    }