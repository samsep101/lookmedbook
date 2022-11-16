<?php

    class Acc
    {

        private $userId = NULL;
        private $db = NULL;


        public static function login(AccountModel $account)
        {
            $account_info = array(
                'id'    => $account->getId(),
                'login' => $account->login,
                'is_system_access'  =>  $account->is_system_access,
                'is_temp' => ($account->password_hash_temp) ? 1 : null
            );
            $_SESSION['__acc']['account'] = $account_info;
        }

        public static function isAuthed()
        {
            if (!empty($_SESSION['__acc']['account']))
                return TRUE;
            return FALSE;
        }

        public static function accountId()
        {
            if (!empty($_SESSION['__acc']['account']['id']))
                return $_SESSION['__acc']['account']['id'];
            return FALSE;
        }

        public static function accountLogin()
        {
            if (!empty($_SESSION['__acc']['account']['login']))
                return $_SESSION['__acc']['account']['login'];
            return FALSE;
        }

        public static function logout()
        {
            unset($_SESSION['__acc']['account']);
        }

        public static function isSystemAccess()
        {
            if (!empty($_SESSION['__acc']['account']['is_system_access'])) {
                return TRUE;
            }
            return FALSE;
        }

        public static function isTemp()
        {
            if (!empty($_SESSION['__acc']['account']['is_temp']))
                return $_SESSION['__acc']['account']['is_temp'];
            return FALSE;
        }
    }