<?php

    class ApiSession
    {
        public function createSession($account_id)
        {
            $session_hash = StringGeneratorHelper::generate(32);

            $account_session = new AccountSessionModel();
            $account_session->account_id = $account_id;
            $account_session->session_hash = $session_hash;
            $account_session->dt_start = date('Y-m-d H:i:s');
            $account_session->dt_end = date("Y-m-d H:i:s", mktime(0, 0, 0, date("m") + 1, date("d"), date("Y")));

            if (!$account_session->save()) {
                $error_codes = $account_session->getValidator()->getErrorCodes();
                ApiHeader::error($error_codes[0]);
            }

            return true;
        }

        public function getAuthorizedAccount($session_hash)
        {
            /**
             * @var AccountManager $account_manager
             */
            $account_manager = ModelManagerFactory::getByName('account');
            return $account_manager->getOneByApiSessionHash($session_hash);
        }
    }