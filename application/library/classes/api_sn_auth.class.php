<?php

class ApiSnAuth {

    protected $access_token;
    protected $uid;
    protected $sn_type;

    public function __construct($access_token,$uid)
    {
        $this->access_token = $access_token;
        $this->uid = $uid;
    }

    public function fb_auth()
    {
        $user_data = FacebookAuth::getUserDataForApi($this->access_token);

        $account_manager = new AccountManager();

        $fb_account = new FbAccountModel();
        $fb_account_manager = new FbAccountManager();

        $session_hash = md5(StringGeneratorHelper::generate(20));

        if ($fb_account_info = $fb_account_manager->getOneByUid($user_data['id'])) {
            $account = $account_manager->getOneById($fb_account_info->account_id);

            $this->setAccountSession($account->getId(), $session_hash);

            $result = array(
                'session' => $session_hash,
                'account_id' => $account->getId(),
            );

            return $result;

            //если такого пользователь нету - добавляем его в базу данных и авторизуем его
        } else {
            $email = (isset($user_data['email']) && $user_data['email']) ? $user_data['email'] : '';
            $login = (!$account_email = $account_manager->getOneByEmail($email)) ? $email : '';

            $first_name = (isset($user_data['first_name'])) ? $user_data['first_name'] : '';
            $last_name = (isset($user_data['last_name'])) ? $user_data['last_name'] : '';
            $middle_name = (isset($user_data['middle_name'])) ? $user_data['middle_name'] : '';
            $full_name = $last_name . ' ' . $first_name . ' ' . $middle_name;

            if (isset($user_data['photo']) && $user_data['photo']) {
                $image = new ImageUploader();
                $foto1 = $image->loadImage($user_data['photo'], 'account/1/');
            } else {
                $foto1 = '';
            }

            $account = new AccountModel();

            $account->email = $login;
            $account->login = $login;
            $account->first_name = $first_name;
            $account->last_name = $last_name;
            $account->full_name = trim($full_name);
            $account->birthday = (isset($user_data['birthday'])) ? date('Y-m-d', strtotime($user_data['birthday'])) : '';
            $account->sex_id = ($user_data['gender'] == 'male') ? 1 : 2;
            $account->city_id = $user_data['city_id'];
            $account->image_id = $foto1;
            $account->password_hash = PasswordHashGenerator::generate($login);

            if ($account->email)
                $account->is_confirm_email = 1;

            $account->setValidator(new WithoutValidator());
            $fb_account->setValidator(new WithoutValidator());

            if (!$account->save()) {
                $error_codes = $account->getValidator()->getErrorCodes();
                ApiHeader::error($error_codes[0]);
            }

            $profile_url = (isset($user_data['link']) && $user_data['link']) ? $user_data['link'] : '';

            $fb_account->account_id = $account->getId();
            $fb_account->uid = $user_data['id'];
            $fb_account->first_name = $first_name;
            $fb_account->last_name = $last_name;
            $fb_account->middle_name = $middle_name;
            $fb_account->profile_url = $profile_url;

            if (!$fb_account->save()) {
                $error_codes = $fb_account->getValidator()->getErrorCodes();
                ApiHeader::error($error_codes[0]);
            }

            $this->setAccountSession($account->getId(), $session_hash);

            $result = array(
                'session' => $session_hash,
                'account_id' => $account->getId(),
            );

            return $result;

        }
    }

    public function vk_auth()
    {
        $user_data = VkontakteAuth::getUserDataForApi($this->access_token, $this->uid);

        $account_manager = new AccountManager();

        $vk_account = new VkAccountModel();
        $vk_account_manager = new VkAccountManager();

        $session_hash = md5(StringGeneratorHelper::generate(20));

        //если пользователь уже есть, авторизуем его
        if ($vk_account_info = $vk_account_manager->getOneByUid($user_data['uid'])) {
            $account = $account_manager->getOneById($vk_account_info->account_id);

            $this->setAccountSession($account->getId(), $session_hash);

            $result = array(
                'session' => $session_hash,
                'account_id' => $account->getId(),
            );

            return $result;

        } else {

            $email = (isset($user_data['email'])) ? $user_data['email'] : '';
            $login = (!$account_manager->getOneByEmail($email)) ? $email : '';

            if (isset($user_data['photo']) && $user_data['photo']) {
                $image = new ImageUploader();
                $foto1 = $image->loadImage($user_data['photo'], 'account/1/');
            } else {
                $foto1 = null;
            }

            $account = new AccountModel();

            $account->email = $login;
            $account->login = $login;
            $account->first_name = (isset($user_data['first_name'])) ? $user_data['first_name'] : '';
            $account->last_name = (isset($user_data['last_name'])) ? $user_data['last_name'] : '';
            $account->full_name = $account->last_name . ' ' . $account->first_name;
            $account->birthday = (isset($user_data['bdate'])) ? date('Y-m-d', strtotime($user_data['bdate'])) : '';
            $account->sex_id = ($user_data['sex'] == '2') ? 1 : 2;
            $account->city_id = $user_data['city_id'];
            $account->image_id = $foto1;
            $account->password_hash = PasswordHashGenerator::generate($login);

            if ($account->email)
                $account->is_confirm_email = 1;

            $account->setValidator(new WithoutValidator());
            $vk_account->setValidator(new WithoutValidator());

            if (!$account->save()) {
                $error_codes = $account->getValidator()->getErrorCodes();
                ApiHeader::error($error_codes[0]);
            }

            $profile_url = (isset($user_data['domain']) && $user_data['domain']) ? 'http://vk.com/' . $user_data['domain'] : '';

            $vk_account->uid = $user_data['uid'];
            $vk_account->account_id = $account->getId();
            $vk_account->first_name = $user_data['first_name'];
            $vk_account->last_name = $user_data['last_name'];
            $vk_account->profile_url = $profile_url;

            if (!$vk_account->save()) {
                $error_codes = $vk_account->getValidator()->getErrorCodes();
                ApiHeader::error($error_codes[0]);
            }

            $this->setAccountSession($account->getId(), $session_hash);

            $result = array(
                'session' => $session_hash,
                'account_id' => $account->getId(),
            );

            return $result;
        }
    }

    public function mailru_auth()
    {
        $user_data = MailruAuth::getUserDataForApi($this->access_token);

        $account_manager = new AccountManager();
        $mailru_account_manager = new MailruAccountManager();

        $session_hash = md5(StringGeneratorHelper::generate(20));

        //если пользователь уже есть, авторизуем его
        if ($mailru_account_info = $mailru_account_manager->getOneByUid($user_data['uid'])) {
            $account = $account_manager->getOneById($mailru_account_info->account_id);

            $this->setAccountSession($account->getId(), $session_hash);

            $result = array(
                'session' => $session_hash,
                'account_id' => $account->getId(),
            );

            return $result;

        } else {

            if (isset($user_data['pic_big']) && $user_data['pic_big']) {
                $image = new ImageUploader();
                $foto1 = $image->loadImage($user_data['pic_big'], 'account/1/');
            } else {
                $foto1 = null;
            }

            $account = new AccountModel();

            $email = (isset($user_data['email'])) ? $user_data['email'] : '';
            $login = (!$account_manager->getOneByEmail($email)) ? $email : '';
            $account->email = $login;
            $account->login = $login;
            $account->first_name = (isset($user_data['first_name'])) ? $user_data['first_name'] : '';
            $account->last_name = (isset($user_data['last_name'])) ? $user_data['last_name'] : '';
            $account->full_name = $account->last_name . ' ' . $account->first_name;
            $account->sex_id = ($user_data['sex'] == '0') ? 1 : 2;
            $account->city_id = $user_data['city_id'];
            $account->birthday = (isset($user_data['birthday'])) ? date('Y-m-d', strtotime($user_data['birthday'])) : null;
            $account->image_id = $foto1;
            $account->password_hash = PasswordHashGenerator::generate($login);

            if ($account->email)
                $account->is_confirm_email = 1;

            $account->setValidator(new WithoutValidator());

            if (!$account->save()) {
                $error_codes = $account->getValidator()->getErrorCodes();
                ApiHeader::error($error_codes[0]);
            }

            if (isset($user_data['link']) && $user_data['link'])
                $profile_url = $user_data['link'];
            else
                $profile_url = '';

            $mailru_account = new MailruAccountModel();
            $mailru_account->uid = $user_data['uid'];
            $mailru_account->email = $account->login;
            $mailru_account->first_name = $user_data['first_name'];
            $mailru_account->last_name = $user_data['last_name'];
            $mailru_account->account_id = $account->getId();
            $mailru_account->profile_url = $profile_url;
            $mailru_account->setValidator(new WithoutValidator());

            if (!$mailru_account->save()) {
                $error_codes = $mailru_account->getValidator()->getErrorCodes();
                ApiHeader::error($error_codes[0]);
            }

            $this->setAccountSession($account->getId(), $session_hash);

            $result = array(
                'session' => $session_hash,
                'account_id' => $account->getId(),
            );

            return $result;
        }
    }

    public function ok_auth()
    {
        $user_data = OdnoklassnikiAuth::getUserDataForApi($this->access_token);

        $account_manager = new AccountManager();
        $ok_account_manager = new OkAccountManager();

        $session_hash = md5(StringGeneratorHelper::generate(20));

        //если пользователь уже есть, авторизуем его
        if ($ok_account_info = $ok_account_manager->getOneByUid($user_data['uid'])) {
            $account = $account_manager->getOneById($ok_account_info->account_id);

            $this->setAccountSession($account->getId(), $session_hash);

            $result = array(
                'session' => $session_hash,
                'account_id' => $account->getId(),
            );

            return $result;

        } else {

            $login = '';

            $first_name = (isset($user_data['first_name'])) ? $user_data['first_name'] : '';
            $last_name = (isset($user_data['last_name'])) ? $user_data['last_name'] : '';
            $full_name = $last_name . ' ' . $first_name;

            $image = new ImageUploader();
            if (isset($user_data['photo']) && $user_data['photo'])
                $foto1 = $image->loadImage($user_data['photo'], 'account/1/');
            else
                $foto1 = '';

            $account = new AccountModel();

            $account->email = $login;
            $account->login = $login;
            $account->first_name = $first_name;
            $account->last_name = $last_name;
            $account->full_name = trim($full_name);
            $account->sex_id = ($user_data['gender'] == 'male') ? 1 : 2;
            $account->birthday = (isset($user_data['birthday'])) ? date('Y-m-d', strtotime($user_data['birthday'])) : '';
            $account->password_hash = PasswordHashGenerator::generate($login);

            if ($foto1)
                $account->image_id = $foto1;

            $account->setValidator(new WithoutValidator());

            if (!$account->save()) {
                $error_codes = $account->getValidator()->getErrorCodes();
                ApiHeader::error($error_codes[0]);
            }

            $ok_account = new OkAccountModel();

            $ok_account->uid = $user_data['uid'];
            $ok_account->email = $account->login;
            $ok_account->first_name = $first_name;
            $ok_account->last_name = $last_name;
            $ok_account->account_id = $account->getId();
            $ok_account->profile_url = 'http://odnoklassniki.ru/profile/' . $user_data['uid'];

            if (!$ok_account->save()) {
                $error_codes = $ok_account->getValidator()->getErrorCodes();
                ApiHeader::error($error_codes[0]);
            }

            $this->setAccountSession($account->getId(), $session_hash);

            $result = array(
                'session' => $session_hash,
                'account_id' => $account->getId(),
            );

            return $result;
        }
    }

    private function setAccountSession($account_id, $session_hash)
    {
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
}