<?php
    class AuthHelper
    {
        public static function checkAuth()
        {
	        $account_manager = new AccountManager();
	        if (!$account_manager->checkExistsById(Acc::accountId()))
	        {
		        Acc::logout();
	        }

            if (!Acc::isAuthed())
                RedirectManager::redirect('/?destination='.$_SERVER['REQUEST_URI']);

        }
    }