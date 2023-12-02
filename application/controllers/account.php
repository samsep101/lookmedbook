<?php

class AccountController extends BaseController
{
  public $layout = 'home';

  public function ajaxRegistration()
  {
    if (Acc::isAuthed())
      $this->redirectUrl('/');

    $email = $this->request->post('email');
    $password = $this->request->post('password');
    $hash = generateCode(40);

    $account = new AccountModel();
    $account->email = $email;
    $account->password = $password;
    $account->email_confirm_code = $hash;
    $account->is_confirmed = 1;

    $geo = new Geo();
    $ip = $geo->get_ip();
    $city = $geo->getLocation($ip);
    if ($city) {
      $account->city_id = $city->getId();
    }

    $account_manager = new AccountManager();

    if ($account_manager->save($account)) {
      if (!SetCookie("already_registred_account", "1", time() + 60 * 60 * 24 * 365, '/'))
        JsonResponse::error(ValidationErrorCodes::ERROR);

      // почта пользователю при регистрации
      $tokens = array(
        'link' => SITE_URL . '/account/confirmEmail?mail=' . $account->email . '&hash=' . $hash . '&rg=1'
      );
      $code = 'confirm_reg';
      $template_data = MailTemplateDataHelper::getTemplateByCodeAndTokens($code, $tokens);
      EmailSenderHelper::sendMessage($account->email, $template_data->title, $template_data->text);

      $this->checkExistingNotificationSettings();
      Acc::login($account);

      JsonResponse::result(TRUE);
    } else {
      JsonResponse::error(ValidationErrorCodes::ERROR);
    }
  }

  public function ajaxSimpleRegistration()
  {
    if (Acc::isAuthed())
      JsonResponse::error(ValidationErrorCodes::ALREADY_REGISTERED);

    $email = $this->request->post('email');
    $url = $this->request->post('url');
    $hash = generateCode(40);

    $account = new AccountModel();
    $account->email = $email;
    $account->email_confirm_code = $hash;
    $account->is_confirmed = 1;
    $account_manager = new AccountManager();

    $account->setValidator(new AccountLandingValidator());

    if ($account_manager->save($account)) {

      $landing_account = new LandingAccountModel();
      $landing_account->account_id = $account->getId();
      $landing_account->url = $url;
      $landing_account->is_confirmed = 0;
      $landing_account->hash = $hash;
      $landing_account_manager = new LandingAccountManager();

      $landing_account_manager->save($landing_account);

      if (!SetCookie("already_registred_account", "1", time() + 60 * 60 * 24 * 365, '/'))
        JsonResponse::error(ValidationErrorCodes::COOKIE_NOT_SET);

      $mail_sender = new EmailSenderHelper();
      if (!$mail_sender->sendLandingConfirmEmailMessage($account->email, $hash))
        JsonResponse::error(ValidationErrorCodes::EMAIL_NOT_SEND);

      $this->checkExistingNotificationSettings();
      Acc::login($account);
      JsonResponse::result($url);
    } else {
      JsonResponse::error(ValidationErrorCodes::ERROR);
    }
  }

  public function landing()
  {
    if (Acc::isAuthed())
      $this->redirectUrl('/account');

    $hash = trim($this->request('hash', ''));

    if ($hash) {
      $account_landing_manager = new LandingAccountManager();
      if ($landing_account = $account_landing_manager->getOneByHash($hash)) {

        if ($landing_account->is_confirmed == 1)
          $this->redirectUrl('/');

        $this->view->email = $landing_account->account->email;
        $this->view->url = $landing_account->url;
      } else {
        $this->redirectUrl('/');
      }
    } else {
      $this->redirectUrl('/');
    }
  }

  public function setLandingRegistrationPassword()
  {
    if (Acc::isAuthed())
      $this->redirectUrl('/account');

    $email = $this->request->post('email');
    $password = $this->request->post('password');

    $account_manager = new AccountManager();

    if ($account = $account_manager->getOneByEmail($email)) {
      $account_manager->setNewPasswordHashByEmail(PasswordHashGenerator::generate($password), $email);

      $landing_account_manager = new LandingAccountManager();
      $landing_account_manager->setIsConfirmedByAccountId($account->getId());
      if ($account->is_confirmed == 1) {
        Acc::login($account);
        JsonResponse::result(TRUE);
      } else {
        JsonResponse::error(ValidationErrorCodes::EMAIL_NOT_CONFIRMED);
      }
    } else {
      JsonResponse::error(ValidationErrorCodes::INVALID_EMAIL);
    }
  }

  public function confirmEmail()
  {
    $email = $this->request('mail', '');
    $hash = $this->request('hash', '');
    $this->checkExistingNotificationSettings();
    if ($email && $hash) {
      $account_manager = new AccountManager();
      $account_data = $account_manager->getOneByEmailAndEmailConfirmCode($email, $hash);
      if (!$account_data) {
        $this->logout();
      } else {
        if ($account_data->is_confirm_email == 1) {
          if ($account_data->is_confirmed == 1)
            Acc::login($account_data);
          $this->redirectUrl('/?email_is_confirmed=1');
        } else {
          $account_manager->confirmEmail($account_data->id);
          if ($account_data->is_confirmed == 1)
            Acc::login($account_data);
          $this->redirectUrl('/?email_is_confirmed=1');
        }
      }
    }
  }

  public function ajaxSendEmailConfirmationMessage()
  {
    if (!Acc::isAuthed())
      $this->redirectUrl('/');

    $mail = $this->request->post('email', '');

    $account_manager = new AccountManager();
    $account = $account_manager->getOneByEmail($mail);

    if (!$account || $account->is_confirm_email) {
      JsonResponse::error(2);
    }

    /*$mail_sender = new EmailSenderHelper();
    $mail_sender->sendConfirmEmailMessage($account->email, $account->email_confirm_code);*/

    // почта пользователю при регистрации
    $tokens = array(
      'link' => SITE_URL . '/account/confirmEmail?mail=' . $account->email . '&hash=' . $account->email_confirm_code . '&rg=1'
    );
    $code = 'confirm_reg';
    $template_data = MailTemplateDataHelper::getTemplateByCodeAndTokens($code, $tokens);
    EmailSenderHelper::sendMessage($account->email, $template_data->title, $template_data->text);

    JsonResponse::result(TRUE);
  }

  // Вход на сайт
  public function ajaxLogin()
  {
    /**
     * @var AccountManager $account_manager
     * @var AccountModel $account
     */

    $email = $this->request('email');
    $password = $this->request('password');

    if ($email) {
      $account_manager = ModelManagerFactory::getByName('account');

      if ($phone = PhoneLoginHelper::checkPhone($email)) {
        $account = $account_manager->getOneByPhoneNumber($phone);
      } else {
        $account = $account_manager->getOneByEmail($email);
      }

      $password_hash = PasswordHashGenerator::generate($password);

      if ($account && ($account->password_hash === $password_hash || $account->password_hash_temp === $password_hash)) {
        Acc::login($account);
        $this->checkExistingNotificationSettings();
        $data = array(
          'email' => $account->email
        );

        JsonResponse::result($data);
      } else {
        if ($phone) {
          JsonResponse::error(ValidationErrorCodes::INVALID_PHONE);
        } else {
          JsonResponse::error(ValidationErrorCodes::INVALID_EMAIL);
        }
      }
    } else {
      JsonResponse::error(ValidationErrorCodes::BLANK_NAME_PASSWORD);
    }
  }

  public function ajaxPasswordRecovery()
  {
    /**
     * @var AccountManager $account_manager
     * @var AccountModel $account
     */

    if (Acc::isAuthed())
      $this->redirectUrl('/account');

    $email = $this->request('email');

    if ($email) {
      $account_manager = ModelManagerFactory::getByName('account');

      if ($phone = PhoneLoginHelper::checkPhone($email)) {
        if ($account = $account_manager->getOneByPhoneNumber($phone)) {
          $temp_password = rand(100000, 999999);
          $account->password_hash = null;
          $account->password_hash_temp = PasswordHashGenerator::generate($temp_password);
          $account->save($account);

          $tokens = array(
            'temp_password' => $temp_password
          );

          $code = 'sms_temp_password';
          $template_data = MailTemplateDataHelper::getTemplateByCodeAndTokens($code, $tokens);
          SmsSender::sendMessage($phone, $template_data->text);

          JsonResponse::result(true);
        }
      } else {
        if ($account = $account_manager->getOneByEmail($email)) {
          /*if (!$account->is_confirm_email) {
              $tokens = array(
                  'link' => SITE_URL.'/account/confirmEmail?mail='.$account->email.'&hash='.$account->email_confirm_code.'&rg=1'
              );
              $code = 'confirm_reg';
              $template_data = MailTemplateDataHelper::getTemplateByCodeAndTokens($code, $tokens);
              EmailSenderHelper::sendMessage($account->email, $template_data->title, $template_data->text);

              //$mail_sender = new EmailSenderHelper();
              //$mail_sender->sendConfirmEmailMessage($account->email, $account->email_confirm_code);

              JsonResponse::result(TRUE);
          }*/

          $tokens = array(
            'link' => '<a href="' . SITE_URL . '/account/passwordNew?mail=' . $account->email . '&hash=' . $account->password_hash . '">' . SITE_URL . '/account/passwordNew?mail=' . $account->email . '&hash=' . $account->password_hash . '</a> '
          );
          $code = 'password_recovery';
          $template_data = MailTemplateDataHelper::getTemplateByCodeAndTokens($code, $tokens);
          EmailSenderHelper::sendMessage($account->email, $template_data->title, $template_data->text);

          /*
          $mail_sender = new EmailSenderHelper();
          $mail_sender->sendRecoveryPasswordEmail($account->email, $account->password_hash);
          */
          JsonResponse::result(TRUE);
        } else {
          JsonResponse::error(ValidationErrorCodes::INVALID_EMAIL);
        }
      }
    }
  }

  public function passwordNew()
  {
    if (Acc::isAuthed())
      $this->redirectUrl('/account');

    $email = trim($this->request('mail', ''));
    $password_hash = trim($this->request('hash', ''));

    if ($email && $password_hash) {
      $account_manager = new AccountManager();
      if ($account = $account_manager->getOneByEmailAndPasswordHash($email, $password_hash)) {

        $this->view->email = $account->email;
        $this->view->not_confirm_email = (!$account->is_confirm_email) ? TRUE : FALSE;

      } else {
        $this->redirectUrl('/');
      }
    } else {
      $this->redirectUrl('/');
    }

    $this->view->page_title = 'Смена пароля';
  }

  public function setNewPassword()
  {
    if (Acc::isAuthed())
      $this->redirectUrl('/account');

    $email = $this->request->post('email');
    $password = $this->request->post('password');
    $is_confirm_email = $this->request->post('is_confirm_email');

    $account_manager = new AccountManager();

    if ($account = $account_manager->getOneByEmail($email)) {
      $account_manager->setNewPasswordHashByEmail(PasswordHashGenerator::generate($password), $email);

      if ($is_confirm_email) {
        $account->is_confirm_email = 1;
        $account_manager->save($account);
      }


      Acc::login($account);
      JsonResponse::result(TRUE);

    } else {
      JsonResponse::error(ValidationErrorCodes::INVALID_EMAIL);
    }
  }

  // Установка нового пароля после получения временного пароля по телефону
  public function setNewPasswordPhone()
  {
    /**
     * @var AccountManager $account_manager
     * @var AccountModel $account
     */

    $password = $this->request('password');
    $password_repeat = $this->request('password_repeat');

    if ($password && $password_repeat && $password === $password_repeat) {
      $account_manager = ModelManagerFactory::getByName('account');

      $account = $account_manager->getOneById(Acc::accountId());
      $account->password_hash = PasswordHashGenerator::generate($password);
      $account->password_hash_temp = null;
      $account_manager->save($account);

      Acc::login($account);
      JsonResponse::result(true);
    } else {
      JsonResponse::error(ValidationErrorCodes::WRONG_PASSWORD);
    }
  }

  public function mailru_login()
  {
    if (isset($_GET['code'])) {

      $destination = $this->request('destination');

      $mailru_client_id = SettingsManager::get('mailru_client_id');
      $mailru_client_secret = SettingsManager::get('mailru_client_secret');

      $mailru_auth = new MailruAuth ($mailru_client_id, $mailru_client_secret, SITE_URL . '/account/mailru_login');

      $token = $mailru_auth->getToken($_GET['code']);

      if (!$token) {
        $this->redirectUrl('/');
      }
      $user_data = $mailru_auth->getUserData($token);

      $account = new AccountModel();
      $account_manager = new AccountManager();

      $mailru_account = new MailruAccountModel();
      $mailru_account_manager = new MailruAccountManager();

      $mailru_account->uid = $user_data['uid'];

      //если пользователь уже есть, авторизуем его
      if ($account_info = $mailru_account_manager->getOneByUid($mailru_account->uid)) {
        $account_data = ModelManagerFactory::getByName('account')->getOneById($account_info->account_id);
        Acc::login($account_data);
        $this->checkExistingNotificationSettings();

        if ($destination)
          $this->redirectUrl($destination);
        else
          $this->redirectUrl('/account');

        //если такого пользователь нету - добавляем его в базу данных и авторизуем его
      } else {

        $image = new ImageUploader();

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
        $account->image_id = (isset($user_data['pic_big']) && $user_data['pic_big']) ? $image->loadImage($user_data['pic_big'], 'account/1/') : null;
        $account->password_hash = PasswordHashGenerator::generate($login);

        if ($account->email)
          $account->is_confirm_email = 1;

        $account->setValidator(new WithoutValidator());
        $mailru_account->setValidator(new WithoutValidator());

        if ($account_manager->save($account)) {

          if (isset($user_data['link']) && $user_data['link'])
            $profile_url = $user_data['link'];
          else
            $profile_url = '';

          $mailru_account->email = $account->login;
          $mailru_account->first_name = $user_data['first_name'];
          $mailru_account->last_name = $user_data['last_name'];
          $mailru_account->account_id = $account->getId();
          $mailru_account->profile_url = $profile_url;

          if ($mailru_account_manager->save($mailru_account)) {

            $sn_tokens = new SnTokensModel();
            $sn_tokens->account_id = $account->getId();
            $sn_tokens->sn_name = SnTokensModel::MAILRU_NAME;
            $sn_tokens->token = $token;
            $sn_tokens->task_status_id = TaskStatusModel::IN_QUEUE;

            $sn_tokens->save();

            Acc::login($account);

            $this->checkExistingNotificationSettings();

            if (!$account->email) {
              if ($destination)
                $this->redirectUrl('/account/newEmail?destination=' . urlencode($destination));
              else
                $this->redirectUrl('/account/newEmail');
            } else {
              if ($destination)
                $this->redirectUrl($destination);
              else
                $this->redirectUrl('/account');
            }
          }
        } else {
          $this->redirectUrl('/');
        }
      }
    } else {
      $this->redirectUrl('/');
    }
  }

  public function index()
  {
    AuthHelper::checkAuth();

    $account_manager = new AccountManager();
    $specialty_manager = new SpecialtyManager();

    $account = $account_manager->getOneById(Acc::accountId());
    $this->view->account = $account;

    if ($account->city_id) {
      $specialties = $specialty_manager->getRootListToSearchDoctorsByCityId($account->city_id);
    } else {
      $city_manager = new CityManager();
      $city_id = $city_manager->getIdByName('Москва');
      $specialties = $specialty_manager->getRootListToSearchDoctorsByCityId($city_id);
    }

    if (!$specialties) {
      $city_manager = new CityManager();
      $city_id = $city_manager->getIdByName('Москва');
      $specialties = $specialty_manager->getRootListToSearchDoctorsByCityId($city_id);
    }

    $this->view->specialties = $specialties;

    $this->view->page_title = 'Главная страница';
  }

  public function logout()
  {
    Acc::logout();
    $this->redirectUrl('/');
  }

  public function vk_login()
  {
    if (isset($_GET['code'])) {

      $destination = $this->request('destination');

      $vk_client_id = SettingsManager::get('vk_client_id');
      $vk_client_secret = SettingsManager::get('vk_client_secret');

      $vk_auth = new VkontakteAuth($vk_client_id, $vk_client_secret, SITE_URL . '/account/vk_login');

      $token = $vk_auth->getToken($_GET['code']);

      if (!$token) {
        $this->redirectUrl('/');
      }

      $user_data = $vk_auth->getUserData($token); //инфа о текущем пользователе

      $account = new AccountModel();
      $account_manager = new AccountManager();

      $vk_account = new VkAccountModel();
      $vk_account_manager = new VkAccountManager();

      $vk_account->uid = $user_data['uid'];

      //если пользователь уже есть, авторизуем его
      if ($account_info = $vk_account_manager->getOneByUid($vk_account->uid)) {
        $account_data = ModelManagerFactory::getByName('account')->getOneById($account_info->account_id);
        Acc::login($account_data);
        $this->checkExistingNotificationSettings();

        if ($destination)
          $this->redirectUrl($destination);
        else
          $this->redirectUrl('/account');

        //если такого пользователь нету - добавляем его в базу данных и авторизуем его
      } else {

        $image = new ImageUploader();

        $email = (isset($user_data['email'])) ? $user_data['email'] : '';
        $login = (!$account_manager->getOneByEmail($email)) ? $email : '';

        $account->email = $login;
        $account->login = $login;
        $account->first_name = (isset($user_data['first_name'])) ? $user_data['first_name'] : '';
        $account->last_name = (isset($user_data['last_name'])) ? $user_data['last_name'] : '';
        $account->full_name = $account->last_name . ' ' . $account->first_name;
        $account->birthday = (isset($user_data['bdate'])) ? date('Y-m-d', strtotime($user_data['bdate'])) : '';
        $account->sex_id = ($user_data['sex'] == '2') ? 1 : 2;
        $account->city_id = $user_data['city_id'];
        $account->image_id = (isset($user_data['photo']) && $user_data['photo']) ? $image->loadImage($user_data['photo'], 'account/1/') : null;
        $account->password_hash = PasswordHashGenerator::generate($login);

        if ($account->email)
          $account->is_confirm_email = 1;

        $account->setValidator(new WithoutValidator());
        $vk_account->setValidator(new WithoutValidator());

        if ($account_manager->save($account)) {

          $vk_account->account_id = $account->getId();
          $vk_account->first_name = $user_data['first_name'];
          $vk_account->last_name = $user_data['last_name'];
          $vk_account->profile_url = (isset($user_data['domain']) && $user_data['domain']) ? 'http://vk.com/' . $user_data['domain'] : '';

          if ($vk_account_manager->save($vk_account)) {

            $sn_tokens = new SnTokensModel();
            $sn_tokens->account_id = $account->getId();
            $sn_tokens->sn_name = SnTokensModel::VK_NAME;
            $sn_tokens->token = $token['access_token'];
            $sn_tokens->uid = $token['user_id'];
            $sn_tokens->task_status_id = TaskStatusModel::IN_QUEUE;

            $sn_tokens->save();

            Acc::login($account);

            $this->checkExistingNotificationSettings();

            if (!$account->email) {
              if ($destination)
                $this->redirectUrl('/account/newEmail?destination=' . urlencode($destination));
              else
                $this->redirectUrl('/account/newEmail');
            } else {
              if ($destination)
                $this->redirectUrl($destination);
              else
                $this->redirectUrl('/account');
            }
          }
        } else {
          $this->redirectUrl('/');
        }
      }
    } else {
      $this->redirectUrl('/');
    }
  }

  public function fb_login()
  {
    if (isset($_GET['code'])) {

      $destination = (isset($_COOKIE['fb_redirect_url'])) ? $_COOKIE['fb_redirect_url'] : '';

      $facebook_client_id = SettingsManager::get('facebook_client_id');
      $facebook_client_secret = SettingsManager::get('facebook_client_secret');

      $FAuth = new FacebookAuth($facebook_client_id, $facebook_client_secret, SITE_URL . '/account/fb_login');
      $token = $FAuth->getToken($_GET['code']);

      if (!$token) {
        $this->redirectUrl('/');
        return;
      }

      $user_data = $FAuth->getUserData($token);

      $account = new AccountModel();
      $account_manager = new AccountManager();

      $fb_account = new FbAccountModel();
      $fb_account_manager = new FbAccountManager();

      //если пользователь уже есть, авторизуем его
      if ($account_info = $fb_account_manager->getOneByUid($user_data['id'])) {
        $account_data = ModelManagerFactory::getByName('account')->getOneById($account_info->account_id);
        Acc::login($account_data);
        $this->checkExistingNotificationSettings();

        if ($destination)
          $this->redirectUrl($destination);
        else
          $this->redirectUrl('/account');
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

        if ($account_manager->save($account)) {

          $profile_url = (isset($user_data['link']) && $user_data['link']) ? $user_data['link'] : '';

          $fb_account->account_id = $account->getId();
          $fb_account->uid = $user_data['id'];
          $fb_account->first_name = $first_name;
          $fb_account->last_name = $last_name;
          $fb_account->middle_name = $middle_name;
          $fb_account->profile_url = $profile_url;

          if ($fb_account_manager->save($fb_account)) {

            $sn_tokens = new SnTokensModel();
            $sn_tokens->account_id = $account->getId();
            $sn_tokens->sn_name = SnTokensModel::FB_NAME;
            $sn_tokens->token = $token;
            $sn_tokens->task_status_id = TaskStatusModel::IN_QUEUE;

            $sn_tokens->save();

            Acc::login($account);

            $this->checkExistingNotificationSettings();

            if (!$login) {
              if ($destination)
                $this->redirectUrl('/account/newEmail?destination=' . urlencode($destination));
              else
                $this->redirectUrl('/account/newEmail');
            } else {
              if ($destination)
                $this->redirectUrl($destination);
              else
                $this->redirectUrl('/account');
            }
          }
        } else {
          $this->redirectUrl('/');
        }
      }
    } else {
      $this->redirectUrl('/');
    }
  }

  public function ok_login()
  {

    if (isset($_GET['code'])) {

      $destination = $this->request('destination');

      $ok_client_id = SettingsManager::get('ok_client_id');
      $ok_client_secret = SettingsManager::get('ok_client_secret');
      $public_key = SettingsManager::get('ok_public_key');

      $ok_auth = new OdnoklassnikiAuth($ok_client_id, $ok_client_secret, SITE_URL . '/account/ok_login', $public_key);

      $token = $ok_auth->getToken($_GET['code']);

      if (!$token) {
        $this->redirectUrl('/');
      }

      $user_data = $ok_auth->getUserData($token);

      $account = new AccountModel();
      $account_manager = new AccountManager();

      $ok_account = new OkAccountModel();
      $ok_account_manager = new OkAccountManager();


      $ok_account->uid = $user_data['uid'];

      if ($account_info = $ok_account_manager->getOneByUid($ok_account->uid)) {
        $account_data = ModelManagerFactory::getByName('account')->getOneById($account_info->account_id);
        Acc::login($account_data);
        $this->checkExistingNotificationSettings();

        if ($destination)
          $this->redirectUrl($destination);
        else
          $this->redirectUrl('/account');

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
        $ok_account->setValidator(new WithoutValidator());

        if ($account_manager->save($account)) {

          $ok_account->email = $account->login;
          $ok_account->first_name = $first_name;
          $ok_account->last_name = $last_name;
          $ok_account->account_id = $account->getId();
          $ok_account->profile_url = 'http://odnoklassniki.ru/profile/' . $user_data['uid'];

          if ($ok_account_manager->save($ok_account)) {

            $sn_tokens = new SnTokensModel();
            $sn_tokens->account_id = $account->getId();
            $sn_tokens->sn_name = SnTokensModel::OK_NAME;
            $sn_tokens->token = $token;
            $sn_tokens->task_status_id = TaskStatusModel::IN_QUEUE;

            $sn_tokens->save();

            Acc::login($account);

            $this->checkExistingNotificationSettings();

            if (!$account->email) {
              if ($destination)
                $this->redirectUrl('/account/newEmail?destination=' . urlencode($destination));
              else
                $this->redirectUrl('/account/newEmail');
            } else {
              if ($destination)
                $this->redirectUrl($destination);
              else
                $this->redirectUrl('/account');
            }
          }
        } else {
          $this->redirectUrl('/');
        }
      }
    } else {
      $this->redirectUrl('/');
    }
  }

  public function newEmail()
  {
    if (!Acc::isAuthed())
      $this->redirectUrl('/');

    $dest = $this->request('destination');

    if ($dest)
      $this->view->destinaton = $dest;
  }

  public function setEmail()
  {
    if (!Acc::isAuthed())
      $this->redirectUrl('/');

    $email = $this->request->post('email', '');

    if (!$email) {
      JsonResponse::error(ValidationErrorCodes::INVALID_EMAIL);
    }

    $account_manager = new AccountManager();

    if ($account_manager->getOneByEmail($email))
      JsonResponse::error(ValidationErrorCodes::INVALID_EMAIL);

    $account = $account_manager->getOneById(Acc::accountId());

    if (!$account || $account->is_confirm_email || $account->email) {
      JsonResponse::error(2);
    }

    $email_confirm_code = generateCode(20);
    $password_hash = generateCode(35);
    $account_manager->setEmailAndEmailConfirmCodeAndPasswordHashByAccountId($email, $email_confirm_code, Acc::accountId(), $password_hash);

    $mail_sender = new EmailSenderHelper();
    $mail_sender->sendConfirmEmailMessage($email, $email_confirm_code);

    JsonResponse::result(TRUE);

  }

  public function about()
  {
    AuthHelper::checkAuth();

    $this->view->account = ModelManagerFactory::getByName('account')->getOneById(Acc::accountId());

    $this->view->cities = ModelManagerFactory::getByName('city')->getList('sort DESC, name ASC');
    //social network data
    $vk_account_manager = new VkAccountManager();
    $this->view->vk_account = $vk_account_manager->getOneByAccountId(Acc::accountId());

    $fb_account_manager = new FbAccountManager();
    $this->view->fb_account = $fb_account_manager->getOneByAccountId(Acc::accountId());

    $mailru_account_manager = new MailruAccountManager();
    $this->view->mailru_account = $mailru_account_manager->getOneByAccountId(Acc::accountId());

    $ok_account_manager = new OkAccountManager();
    $this->view->ok_account = $ok_account_manager->getOneByAccountId(Acc::accountId());

    $this->view->page_title = 'Профиль - личные данные - «' . SITE_NAME . '»';
  }

  public function ajaxSaveAbout()
  {
    if (!Acc::isAuthed())
      $this->redirectUrl('/');

    $account = ModelManagerFactory::getByName('account')->getOneById(Acc::accountId());

    $account->nick = strip_tags($this->request->post('nick'));
    $account->first_name = strip_tags($this->request->post('first_name'));
    $account->last_name = strip_tags($this->request->post('last_name'));
    $account->middle_name = strip_tags($this->request->post('middle_name'));
    $account->sex_id = strip_tags($this->request->post('sex_id'));
    $account->birthday = strip_tags($this->request->post('birthday_date'));
    $account->city_id = strip_tags($this->request->post('city'));
    $phone_numbers = $this->request->post('phone_numbers');
    $email = strip_tags($this->request->post('email'));

    trim($account->first_name);
    trim($account->last_name);
    trim($account->middle_name);

    if ($account->first_name & $account->last_name && $account->middle_name)
      $account->full_name = $account->last_name . ' ' . $account->middle_name . ' ' . $account->first_name;
    else $account->full_name = '';

    if ($phone_numbers) {
      foreach ($phone_numbers as $phone_number)
        $this->setPersonalPhone($phone_number);
    }

    if ($email) {
      if ($email != $account->email) {
        $account->email = $email;
        $account->is_confirm_email = null;
        $account->email_confirm_code = generateCode(20);
        $mail_sender = new EmailSenderHelper();
        $mail_sender->sendConfirmEmailMessage($account->email, $account->email_confirm_code);
      }
    }

    $account->setValidator(new WithoutValidator());

    if (ModelManagerFactory::getByName('account')->save($account)) {
      JsonResponse::result(TRUE);
    } else {
      JsonResponse::error(15);
    }
  }

  private function setPersonalPhone($phone)
  {
    $account_phone_manager = new AccountPhoneManager();

    if ($telephone = $account_phone_manager->getOneByPhone($phone))
      JsonResponse::error(ValidationErrorCodes::ALREADY_REGISTERED);

    $account_phone = new AccountPhoneModel();
    $account_phone->phone = $phone;
    $account_phone->account_id = Acc::accountId();
    $account_phone->code = StringGeneratorHelper::generateNumbers(5);
    $account_phone->dt = date('Y-m-d H:i:s');
    $account_phone->is_confirmed = 0;

    if ($account_phone_manager->save($account_phone)) {
      return TRUE;
    } else {
      JsonResponse::error(ValidationErrorCodes::ERROR);
    }
  }

  public function ajaxConfirmPhoneCode()
  {
    if (!Acc::isAuthed())
      $this->redirectUrl('/');

    $this->layout = 'ajax';

    $confirm_code = $this->request->post('confirm_code');
    $phone_id = $this->request->post('phone_id');

    $account_phone_manager = new AccountPhoneManager();
    $account_phone = $account_phone_manager->getOneById($phone_id);

    if ($account_phone) {
      if ($account_phone->account_id != Acc::accountId()) {
        JsonResponse::error(ValidationErrorCodes::WRONG_ACCOUNT);
      }

      if ($confirm_code == $account_phone->code) {
        $account_phone->is_confirmed = 1;
        $account_phone->save();

        JsonResponse::result(TRUE);
      } else {
        JsonResponse::error(ValidationErrorCodes::WRONG_CONFIRM_CODE);
      }
    } else {
      JsonResponse::error(ValidationErrorCodes::ERROR);
    }
  }

  public function ajaxSaveVkAccountInfo()
  {
    if (!Acc::isAuthed())
      $this->redirectUrl('/');

    $vk_account_manager = new VkAccountManager();
    $vk_account = $vk_account_manager->getOneByAccountId(Acc::accountId());

    $vk_account->profile_url = (isset($_POST['profile_url'])) ? ($_POST['profile_url']) : '';
    $vk_account->home_phone = (isset($_POST['home_phone'])) ? $_POST['home_phone'] : '';
    $vk_account->activity = (isset($_POST['activity'])) ? $_POST['activity'] : '';
    $vk_account->relation_type = (isset($_POST['relation_type'])) ? $_POST['relation_type'] : '';
    $vk_account->interests = (isset($_POST['interests'])) ? $_POST['interests'] : '';
    $vk_account->movies = (isset($_POST['movies'])) ? $_POST['movies'] : '';
    $vk_account->tv = (isset($_POST['tv'])) ? $_POST['tv'] : '';
    $vk_account->books = (isset($_POST['books'])) ? $_POST['books'] : '';
    $vk_account->games = (isset($_POST['games'])) ? $_POST['games'] : '';
    $vk_account->about = (isset($_POST['about'])) ? $_POST['about'] : '';

    if (ModelManagerFactory::getByName('vk_account')->save($vk_account)) {
      JsonResponse::result(TRUE);
    } else {
      JsonResponse::error(40);
    }
  }

  public function ajaxSaveFbAccountInfo()
  {
    if (!Acc::isAuthed())
      $this->redirectUrl('/');

    $fb_account_manager = new FbAccountManager();
    $fb_account = $fb_account_manager->getOneByAccountId(Acc::accountId());

    $fb_account->profile_url = (isset($_POST['profile_url'])) ? ($_POST['profile_url']) : '';
    $fb_account->user_name = (isset($_POST['user_name'])) ? $_POST['user_name'] : '';
    $fb_account->hometown = (isset($_POST['hometown'])) ? $_POST['hometown'] : '';
    $fb_account->bio = (isset($_POST['bio'])) ? $_POST['bio'] : '';
    $fb_account->quotes = (isset($_POST['quotes'])) ? $_POST['quotes'] : '';
    $fb_account->political_view = (isset($_POST['political_view'])) ? $_POST['political_view'] : '';
    $fb_account->is_interested_in_male = (isset($_POST['is_interested_in_male'])) ? $_POST['is_interested_in_male'] : null;
    $fb_account->is_interested_in_female = (isset($_POST['is_interested_in_female'])) ? $_POST['is_interested_in_female'] : null;
    $fb_account->relationship_status = (isset($_POST['relationship_status'])) ? $_POST['relationship_status'] : '';
    $fb_account->religion = (isset($_POST['religion'])) ? $_POST['religion'] : '';
    $fb_account->web_sites = (isset($_POST['web_sites'])) ? $_POST['web_sites'] : '';

    if (ModelManagerFactory::getByName('fb_account')->save($fb_account)) {
      JsonResponse::result(TRUE);
    } else {
      JsonResponse::error(41);
    }
  }

  public function ajaxSaveMailruAccountInfo()
  {
    if (!Acc::isAuthed())
      $this->redirectUrl('/');

    $mailru_account_manager = new MailruAccountManager();
    $mailru_account = $mailru_account_manager->getOneByAccountId(Acc::accountId());

    $mailru_account->nick_name = (isset($_POST['nick_name'])) ? ($_POST['nick_name']) : '';
    $mailru_account->profile_url = (isset($_POST['profile_url'])) ? $_POST['profile_url'] : '';
    $mailru_account->status_text = (isset($_POST['status_text'])) ? $_POST['status_text'] : '';

    if (ModelManagerFactory::getByName('mailru_account')->save($mailru_account)) {
      JsonResponse::result(TRUE);
    } else {
      JsonResponse::error(42);
    }
  }

  public function ajaxSaveOkAccountInfo()
  {
    if (!Acc::isAuthed())
      $this->redirectUrl('/');

    $ok_account_manager = new OkAccountManager();
    $ok_account = $ok_account_manager->getOneByAccountId(Acc::accountId());

    $ok_account->profile_url = (isset($_POST['profile_url'])) ? ($_POST['profile_url']) : '';
    $ok_account->age = (isset($_POST['age']) && $_POST['age']) ? $_POST['age'] : null;

    if (ModelManagerFactory::getByName('ok_account')->save($ok_account)) {
      JsonResponse::result(TRUE);
    } else {
      JsonResponse::error(43);
    }
  }

  public function doctorsVisitsComing()
  {
    AuthHelper::checkAuth();

    $visit_manager = new VisitManager();
    $visits = $visit_manager->getAllComingListByAccountId(Acc::accountId());
    $this->view->visits = $visits;

    $this->view->page_title = 'Профиль - предстоящие визиты к врачу - «' . SITE_NAME . '»';
  }

  public function doctorsVisitsPast()
  {
    AuthHelper::checkAuth();

    $visit_manager = new VisitManager();
    $visits = $visit_manager->getPastListByAccountId(Acc::accountId());
    $this->view->visits = $visits;

    $this->view->page_title = 'Профиль - прошедшие визиты к врачу - «' . SITE_NAME . '»';
  }

  public function ajaxCancelVisitToDoctor()
  {
    if (!Acc::isAuthed())
      $this->redirectUrl('/');

    $visit_id = $this->request->post('visit_id');

    if ($visit_id) {
      //$visit = ModelManagerFactory::getByName('visit')->getOneById($visit_id);
      //$schedule_manager = new ScheduleManager();
      //$schedule_manager->setNotBusyStatusByVisitId($visit_id);
      //ModelManagerFactory::getByName('visit')->deleteById($visit_id);

      /**
       * @var VisitModel $visit
       */

      $visit = ModelManagerFactory::getByName('visit')->getOneById($visit_id);
      $visit->status_id = VisitModel::CANCELLED;
      $visit->setWhenceCanceled('из личного кабинета');
      $visit->save();

      $visit_mail = new VisitMailModel();
      $visit_mail->visit_id = $visit->getId();
      $visit_mail->visit_mail_type_id = VisitMailTypeModel::VISIT_CANCEL_TO_ADMIN;
      $visit_mail->send();

      $visit_mail_2 = new VisitMailModel();
      $visit_mail_2->visit_id = $visit->getId();
      $visit_mail_2->visit_mail_type_id = VisitMailTypeModel::VISIT_CANCEL_TO_USER;
      $visit_mail_2->send();

      $tokens = array(
        'visit_id' => $visit->getId()
      );
      $code = 'sms_record_cancel';
      $template_data = MailTemplateDataHelper::getTemplateByCodeAndTokens($code, $tokens);
      SmsSender::sendMessage($visit->phone, $template_data->text);

      JsonResponse::result(TRUE);
    } else {
      JsonResponse::error(30);
    }

  }

  // todo: изменения записи к врачу (Личный кабинет->Записи к врачу)
  public function changeVisitToDoctor()
  {

  }

  public function family()
  {
    AuthHelper::checkAuth();

    $account_info = ModelManagerFactory::getByName('account')->getOneById(Acc::accountId());
    $this->view->account = $account_info;

    $relations = ModelManagerFactory::getByName('family_relation_status')->getList();
    $this->view->relations = $relations;

    $this->view->page_title = 'Профиль - Семья - «' . SITE_NAME . '»';
  }

  public function ajaxSaveFamilyRelation()
  {
    if (!Acc::isAuthed()) $this->redirectUrl('/');

    $family_relation_status_id = $this->request->post('family_relation_status_id');
    $first_name = trim($this->request->post('first_name'));
    $last_name = trim($this->request->post('last_name'));
    $middle_name = trim($this->request->post('middle_name'));
    $phone = trim($this->request->post('phone'));
    $email = trim($this->request->post('email'));

    $phone = str_replace('+', '', $phone);
    $phone = str_replace('-', '', $phone);

    $relation_account = ModelManagerFactory::getByName('account')->getOneByFirstNameAndLastNameAndMiddleNameAndEmail($first_name, $last_name, $middle_name, $email);
    if ($relation_account) {
      if ($phone != '') $final_relation_account = ModelManagerFactory::getByName('account_phone')->getConfirmedOneByAccountIdAndPhone($relation_account->id, $phone);
      if (isset($final_relation_account) || $phone == '') {
        if ($relation_account->id != Acc::accountId()) {
          $family_relation_moderate_manager = new FamilyRelationModerateManager();
          $family_relation_manager = new FamilyRelationManager();

          if ($family_relation_manager->checkExistsByAccount1IdAndAccount2IdAndIsConfirmed(Acc::accountId(), $relation_account->id) == FALSE &&
            $family_relation_manager->checkExistsByAccount1IdAndAccount2IdAndIsConfirmed($relation_account->id, Acc::accountId()) == FALSE &&
            $family_relation_moderate_manager->checkExistsByAccountIdAndToAccountIdAndIsConfirmed(Acc::accountId(), $relation_account->id) == FALSE &&
            $family_relation_moderate_manager->checkExistsByAccountIdAndToAccountIdAndIsConfirmed($relation_account->id, Acc::accountId()) == FALSE
          ) {
            $family_relation = new FamilyRelationModerateModel();
            $family_relation->account_id = Acc::accountId();
            $family_relation->to_account_id = $relation_account->id;
            $family_relation->family_relation_status_id = $family_relation_status_id;
            //$family_relation->full_name = $name;

            if (ModelManagerFactory::getByName('family_relation_moderate')->save($family_relation)) {
              JsonResponse::result(array('add_relation' => TRUE));
            } else {
              JsonResponse::result(array('add_relation' => FALSE, 'warning' => 'Не сохранено!'));
            }
          } else {
            JsonResponse::result(array('add_relation' => FALSE, 'warning' => 'Данная связь уже существует!'));
          }
        } else {
          JsonResponse::result(array('add_relation' => FALSE, 'warning' => 'Вы не можете отправить запрос самому себе!'));
        }
      } else {
        JsonResponse::result(array('add_relation' => FALSE, 'warning' => 'Пользователя с указанным именем, почтой и телефоном в клубе не существует!'));
      }
    } else {
      JsonResponse::result(array('add_relation' => FALSE, 'warning' => 'Пользователя с указанным именем и почтой в клубе не существует!'));
    }
  }

  public function ajaxDeleteFamilyRelation()
  {
    if (!Acc::isAuthed()) $this->redirectUrl('/');

    $relation_id = $this->request->post('relation_id');
    if ($this->request->post('relation_table') == 'delete-relation') $table = 'family_relation';
    else if ($this->request->post('relation_table') == 'delete-relation-moderate') $table = 'family_relation_moderate';

    if ($table) {
      ModelManagerFactory::getByName($table)->deleteById($relation_id);
      JsonResponse::result(TRUE);
    } else {
      JsonResponse::result(FALSE);
    }
  }

  public function ajaxConfirmFamilyRelation()
  {
    if (!Acc::isAuthed()) $this->redirectUrl('/');

    $relation_id = $this->request->post('relation_id');

    $moderate_relation = ModelManagerFactory::getByName('family_relation_moderate')->getOneById($relation_id);

    $family_relation = new FamilyRelationModel();
    $family_relation->account1_id = $moderate_relation->to_account_id;
    $family_relation->account2_id = $moderate_relation->account_id;
    $family_relation->family_relation_status_id = $moderate_relation->family_relation_status_id;
    $family_relation->is_confirmed = 1;
    if (ModelManagerFactory::getByName('family_relation')->save($family_relation)) {
      ModelManagerFactory::getByName('family_relation_moderate')->deleteById($relation_id);
      JsonResponse::result(TRUE);
    } else
      JsonResponse::result(FALSE);
  }

  public function ajaxGetAccountRelations()
  {
    if (!Acc::isAuthed()) $this->redirectUrl('/');

    $this->layout = 'ajax';
    $relations_string = '';

    $family_relation_manager = new FamilyRelationManager();
    $account_relations = $family_relation_manager->getConfirmedListByAccountId(Acc::accountId());
    $reversed_account_relations = $family_relation_manager->getConfirmedListByToAccountId(Acc::accountId());

    $family_relation_moderate_manager = new FamilyRelationModerateManager();
    $account_relations_moderate = $family_relation_moderate_manager->getListByToAccountIdAndIsConfirmed(Acc::accountId());

    if ($account_relations || $reversed_account_relations || $account_relations_moderate) {

      if ($account_relations_moderate) {

        $this->view->relations_moderate = $account_relations_moderate;
        $relations_string .= $this->renderInString('account/blocks/family_relation_query_list');

      }
      if ($account_relations || $reversed_account_relations) {
        $this->view->relations = $account_relations;
        $this->view->reversed_relations = $reversed_account_relations;
        $relations_string .= $this->renderInString('account/blocks/family_relation_list');

      }

      JsonResponse::result(array('account_relations' => $relations_string));
    } else {
      JsonResponse::error(ValidationErrorCodes::NO_ACCOUNT_RELATIONS);
    }

  }

  public function joinVkAccount()
  {
    if (!Acc::isAuthed()) $this->redirectUrl('/');

    if (isset($_GET['code'])) {
      $vk_client_id = SettingsManager::get('vk_client_id');
      $vk_client_secret = SettingsManager::get('vk_client_secret');
      $redirect_url = SITE_URL . '/account/joinVkAccount';

      $vk_auth = new VkontakteAuth($vk_client_id, $vk_client_secret, $redirect_url);

      $data = $vk_auth->getToken($_GET['code']);
      $token = $data['access_token'];
      $uid = $data['user_id'];

      if (!$token) {
        $this->redirectUrl('/account/trust');
      }

      $user_data = $vk_auth->getUserData($data); //инфа о текущем пользователе

      //если аккаунт уже есть для связи - уведомляем
      $vk_account_manager = new VkAccountManager();
      if (!$account_info = $vk_account_manager->getOneByUid($user_data['uid'])) {
        $vk_account = new VkAccountModel();
        $vk_account->uid = $user_data['uid'];
        $vk_account->account_id = Acc::accountId();
        $vk_account->first_name = $user_data['first_name'];
        $vk_account->last_name = $user_data['last_name'];
        $vk_account->profile_url = (isset($user_data['domain']) && $user_data['domain']) ? 'http://vk.com/' . $user_data['domain'] : '';
        $vk_account->is_account_connected = 1;
        $vk_account_manager->save($vk_account);

        $sn_tokens = new SnTokensModel();
        $sn_tokens->account_id = Acc::accountId();
        $sn_tokens->sn_name = SnTokensModel::VK_NAME;
        $sn_tokens->token = $token;
        $sn_tokens->uid = $uid;
        $sn_tokens->task_status_id = TaskStatusModel::IN_QUEUE;

        $sn_tokens->save();
        $this->redirectUrl('/account/trust');

      } else {
        $this->redirectUrl('/account/trust?is_conected=1');
      }
    } else {
      $this->redirectUrl('/account/trust');
    };
  }

  public function joinFbAccount()
  {
    if (!Acc::isAuthed()) $this->redirectUrl('/');

    if (isset($_GET['code'])) {
      $facebook_client_id = SettingsManager::get('facebook_client_id');
      $facebook_client_secret = SettingsManager::get('facebook_client_secret');
      $redirect_url = SITE_URL . $_SERVER['REQUEST_URI'];

      $fb_auth = new FacebookAuth($facebook_client_id, $facebook_client_secret, $redirect_url);

      $token = $fb_auth->getToken($_GET['code']);

      if (!$token) {
        $this->redirectUrl('/account/trust');
        return;
      }

      $user_data = $fb_auth->getUserData($token);

      $fb_account_manager = new FbAccountManager();
      if (!$account_info = $fb_account_manager->getOneByUid($user_data['id'])) {
        $fb_account = new FbAccountModel();

        $first_name = (isset($user_data['first_name'])) ? $user_data['first_name'] : '';
        $last_name = (isset($user_data['last_name'])) ? $user_data['last_name'] : '';
        $middle_name = (isset($user_data['middle_name'])) ? $user_data['middle_name'] : '';
        $profile_url = (isset($user_data['link']) && $user_data['link']) ? $user_data['link'] : '';

        $fb_account->uid = $user_data['id'];
        $fb_account->account_id = Acc::accountId();
        $fb_account->first_name = $first_name;
        $fb_account->last_name = $last_name;
        $fb_account->middle_name = $middle_name;
        $fb_account->profile_url = $profile_url;
        $fb_account_manager->save($fb_account);

        $sn_tokens = new SnTokensModel();
        $sn_tokens->account_id = Acc::accountId();
        $sn_tokens->sn_name = SnTokensModel::FB_NAME;
        $sn_tokens->token = $token;
        $sn_tokens->task_status_id = TaskStatusModel::IN_QUEUE;

        $sn_tokens->save();
        $this->redirectUrl('/account/trust');
      } else {
        $this->redirectUrl('/account/trust?is_conected=1');
      }
    } else {
      $this->redirectUrl('/account/trust');
    }
  }

  public function joinMailruAccount()
  {
    if (!Acc::isAuthed()) $this->redirectUrl('/');

    if (isset($_GET['code'])) {
      $mailru_client_id = SettingsManager::get('mailru_client_id');
      $mailru_client_secret = SettingsManager::get('mailru_client_secret');

      $mailru_auth = new MailruAuth ($mailru_client_id, $mailru_client_secret, SITE_URL . '/account/joinMailruAccount');

      $token = $mailru_auth->getToken($_GET['code']);

      if (!$token) {
        $this->redirectUrl('/account/trust');
      }

      $user_data = $mailru_auth->getUserData($token);

      $mailru_account_manager = new MailruAccountManager();
      if (!$account_info = $mailru_account_manager->getOneByUid($user_data['uid'])) {
        $mailru_account = new MailruAccountModel();

        $mailru_account->uid = $user_data['uid'];
        $mailru_account->first_name = $user_data['first_name'];
        $mailru_account->last_name = $user_data['last_name'];
        $mailru_account->account_id = Acc::accountId();
        $mailru_account->profile_url = (isset($user_data['link']) && $user_data['link']) ? $user_data['link'] : '';
        $mailru_account->save();

        $sn_tokens = new SnTokensModel();
        $sn_tokens->account_id = Acc::accountId();
        $sn_tokens->sn_name = SnTokensModel::MAILRU_NAME;
        $sn_tokens->token = $token;
        $sn_tokens->task_status_id = TaskStatusModel::IN_QUEUE;

        $sn_tokens->save();
        $this->redirectUrl('/account/trust');
      } else {
        $this->redirectUrl('/account/trust?is_conected=1');
      }
    } else {
      $this->redirectUrl('/account/trust');
    }
  }

  public function joinOkAccount()
  {
    if (!Acc::isAuthed()) $this->redirectUrl('/');

    if (isset($_GET['code'])) {

      $ok_client_id = SettingsManager::get('ok_client_id');
      $ok_client_secret = SettingsManager::get('ok_client_secret');
      $public_key = SettingsManager::get('ok_public_key');

      $ok_auth = new OdnoklassnikiAuth($ok_client_id, $ok_client_secret, SITE_URL . '/account/joinOkAccount', $public_key);

      $token = $ok_auth->getToken($_GET['code']);

      if (!$token) {
        $this->redirectUrl('/account/trust');
      }

      $user_data = $ok_auth->getUserData($token);
      $ok_account_manager = new OkAccountManager();

      if (!$account_info = $ok_account_manager->getOneByUid($user_data['uid'])) {
        $ok_account = new OkAccountModel();

        $first_name = (isset($user_data['first_name'])) ? $user_data['first_name'] : '';
        $last_name = (isset($user_data['last_name'])) ? $user_data['last_name'] : '';
        $ok_account->account_id = Acc::accountId();
        $ok_account->uid = $user_data['uid'];
        $ok_account->first_name = $first_name;
        $ok_account->last_name = $last_name;
        $ok_account->profile_url = 'http://odnoklassniki.ru/profile/' . $user_data['uid'];
        $ok_account->save();

        $sn_tokens = new SnTokensModel();
        $sn_tokens->account_id = Acc::accountId();
        $sn_tokens->sn_name = SnTokensModel::OK_NAME;
        $sn_tokens->token = $token;
        $sn_tokens->task_status_id = TaskStatusModel::IN_QUEUE;

        $sn_tokens->save();
        $this->redirectUrl('/account/trust');
      } else {
        $this->redirectUrl('/account/trust?is_conected=1');
      }

    } else {
      $this->redirectUrl('/account/trust');
    }
  }


  public function trust()
  {
    AuthHelper::checkAuth();

    $vk_account_manager = new VkAccountManager();
    $this->view->vk_account = $vk_account_manager->getOneByAccountId(Acc::accountId());

    $fb_account_manager = new FbAccountManager();
    $this->view->fb_account = $fb_account_manager->getOneByAccountId(Acc::accountId());

    $mailru_account_manager = new MailruAccountManager();
    $this->view->mailru_account = $mailru_account_manager->getOneByAccountId(Acc::accountId());

    $ok_account_manager = new OkAccountManager();
    $this->view->ok_account = $ok_account_manager->getOneByAccountId(Acc::accountId());

    $this->view->page_title = 'Профиль - Доверие - «' . SITE_NAME . '»';
  }

  public function options()
  {
    AuthHelper::checkAuth();

    // todo: уточнить что происходит по клику на ссылке "Редактировать номера телефонов"
    $account_info = ModelManagerFactory::getByName('account')->getOneById(Acc::accountId());
    $this->view->account = $account_info;

    $this->view->page_title = 'Профиль - Настройки - «' . SITE_NAME . '»';
  }

  public function ajaxEditPersonalNotificationSettings()
  {
    if (!Acc::isAuthed()) $this->redirectUrl('/');

    $phone_id = $_POST['phone_id'];
    $sms_checkbox_main = $_POST['sms_checkbox_main'];
    $sms_checkbox_1 = $_POST['sms_checkbox_1'];
    $sms_checkbox_2 = $_POST['sms_checkbox_2'];
    $sms_checkbox_3 = $_POST['sms_checkbox_3'];
    $mail_checkbox_1 = $_POST['mail_checkbox_1'];
    $mail_checkbox_2 = $_POST['mail_checkbox_2'];
    $mail_checkbox_3 = $_POST['mail_checkbox_3'];

    if (ModelManagerFactory::getByName('notification_settings')->setNotificationSettingsByAccountId(Acc::accountId(), $phone_id, $sms_checkbox_main, $sms_checkbox_1, $sms_checkbox_2, $sms_checkbox_3, $mail_checkbox_1, $mail_checkbox_2, $mail_checkbox_3))
      JsonResponse::result(TRUE);
    else
      JsonResponse::result(FALSE);
  }

  public function message()
  {
    AuthHelper::checkAuth();

    $page = $this->request('page', 1);
    $per_page = 4;

    $message_manager = new MessageManager();
    $messages = $message_manager->getListByToAccountIdWithPadding(Acc::accountId(), $page, $per_page);
    $count = count($message_manager->getListByToAccountId(Acc::accountId()));

    $this->view->messages = $messages;
    $this->view->page = $page;
    $this->view->pages_num = (int)(($count - 1) / $per_page) + 1;

    $this->view->page_title = 'Профиль - Сообщения - «' . SITE_NAME . '»';

  }

  public function checkExistingNotificationSettings()
  {
    $account_manager = new AccountManager();

    if ($account_manager->checkExistsById(Acc::accountId())) {
      if (!ModelManagerFactory::getByName('notification_settings')->checkExistsByAccountId(Acc::accountId())) {
        $notification_settings = new NotificationSettingsModel();
        $notification_settings->account_id = Acc::accountId();
        $notification_settings->sms_notify = 1;
        $notification_settings->sms_notify_visit = 1;
        $notification_settings->sms_notify_change = 1;
        $notification_settings->sms_notify_news = 1;
        $notification_settings->email_notify_visit = 1;
        $notification_settings->email_notify_change = 1;
        $notification_settings->email_notify_bonus = 1;
        ModelManagerFactory::getByName('notification_settings')->save($notification_settings);
      }
    }
  }

  public function my_doctor()
  {
    AuthHelper::checkAuth();

    $specialty_manager = new SpecialtyManager();
    $this->view->specialties = $specialty_manager->getRootListByPastVisitAndMyDoctorsByAccountId(Acc::accountId());

    $clinic_manager = new ClinicManager();
    $this->view->clinics = $clinic_manager->getListByPastVisitAndMyDoctorsByAccountId(Acc::accountId());

    $this->view->page_title = 'Профиль - Мои врачи - «' . SITE_NAME . '»';
  }

  public function ajaxGetDoctorsListByPastVisit()
  {
    if (!Acc::isAuthed()) {
      JsonResponse::error(ValidationErrorCodes::NOT_AUTHED);
    }

    $this->layout = 'ajax';

    $clinic_id = $this->request('clinic_id', 0);
    $specialty_id = $this->request('specialty_id', 0);
    $purpose_of_visit_id = $this->request('purpose_of_visit_id', 0);
    $time_of_visit = $this->request('time_of_visit', '');

    $page = $this->request('page', 1);

    $params = new PastVisitSearchParams();
    $params->clinic_id = $clinic_id;
    $params->specialty_id = $specialty_id;
    $params->purpose_of_visit_id = $purpose_of_visit_id;
    $params->time_of_visit = $time_of_visit;
    $params->account_id = Acc::accountId();

    $params->offset = ($page == 1) ? 0 : (4 + ($page - 2) * 10);
    $params->limit = ($page == 1) ? 4 : 10;

    $doctor_manager = new VisitedDoctorManager();
    $doctors = $doctor_manager->getListByPastVisitSearchParams($params);
    $this->view->doctors = $doctors;

    $doctors_count = count($doctors);
    if ($doctors_count == 0)
      JsonResponse::error(2);

    $params->limit = ($page == 1) ? 5 : 11;
    $more_doctors = $doctor_manager->getListByPastVisitSearchParams($params);
    $more_count = count($more_doctors);
    if ($more_count == 5 || $more_count == 11)
      $more_button = 1;
    else
      $more_button = 0;

    $html = $this->renderInString('doctor/card_big_list');
    JsonResponse::result(array(
      'html' => $html,
      'count' => $doctors_count,
      'more_button' => $more_button
    ));
  }

  public function ajaxGetMyDoctors()
  {
    if (!Acc::isAuthed()) {
      JsonResponse::error(ValidationErrorCodes::NOT_AUTHED);
    }

    $this->layout = 'ajax';

    $clinic_id = $this->request('clinic_id', 0);
    $specialty_id = $this->request('specialty_id', 0);
    $purpose_of_visit_id = $this->request('purpose_of_visit_id', 0);
    $time_of_visit = $this->request('time_of_visit', '');
    $account_id = $this->request('account_id', 0);

    $page = $this->request('page', 1);
    $reload = $this->request('reload', 0);

    $params = new MyDoctorsSearchParams();
    $params->account_id = $account_id;
    $params->clinic_id = $clinic_id;
    $params->purpose_of_visit_id = $purpose_of_visit_id;
    $params->specialty_id = $specialty_id;
    $params->time_of_visit = $time_of_visit;

    if ($reload == 1) {
      $params->offset = 0;
      $params->limit = 10 + ($page - 1) * 10;
    } else {
      $params->offset = ($page == 1) ? 0 : (10 + ($page - 2) * 10);
      $params->limit = 10;
    }

    $doctor_manager = new DoctorManager();
    $doctors = $doctor_manager->getFavoriteListBySearchParams($params);
    $this->view->is_close_card = 1;
    $this->view->doctors = $doctors;
    $count = count($doctors);

    if ($count == 0)
      JsonResponse::error(2);

    if ($reload == 1) {
      $params->offset = 0;
      $params->limit = 10 + ($page - 1) * 10;
    } else {
      $params->offset = ($page == 1) ? 0 : (10 + ($page - 2) * 10);
      $params->limit = 11;
    }
    $more_doctors = $doctor_manager->getFavoriteListBySearchParams($params);
    $more_count = count($more_doctors);
    if ($more_count == 11)
      $more_button = 1;
    else
      $more_button = 0;

    $html = $this->renderInString('doctor/card_big_list');
    JsonResponse::result(array('html' => $html, 'count' => $count, 'more_button' => $more_button));
  }

  public function my_disease()
  {
    AuthHelper::checkAuth();

    $letter = $this->request('letter');
    $page = $this->request('page', 1);
    $per_page = 4;

    $my_disease_manager = new MyDiseaseManager();
    if ($letter) {
      $diseases = $my_disease_manager->getDiseaseNamesByFirstLetterWithPadding(Acc::accountId(), $letter, $page, $per_page);
      $count = count($my_disease_manager->getDiseaseNamesByFirstLetter(Acc::accountId(), $letter));
    } else {
      $diseases = $my_disease_manager->getListByAccountIdWithPagging(Acc::accountId(), $page, $per_page);
      $count = count($my_disease_manager->getListByAccountId(Acc::accountId()));
    }

    $this->view->diseases = $diseases;
    $this->view->page = $page;
    $this->view->pages_num = (int)(($count - 1) / $per_page) + 1;

    $this->view->page_title = 'Профиль - Мои заболевания - «' . SITE_NAME . '»';
  }

  public function my_disease_archive()
  {
    AuthHelper::checkAuth();

    $letter = $this->request('letter');
    $page = $this->request('page', 1);
    $per_page = 4;

    $my_disease_manager = new MyDiseaseManager();
    if ($letter) {
      $diseases = $my_disease_manager->getDiseaseNamesByFirstLetterAndIsArchiveWithPadding(Acc::accountId(), $letter, $page, $per_page);
      $count = count($my_disease_manager->getDiseaseNamesByFirstLetterAndIsArchive(Acc::accountId(), $letter));
    } else {
      $diseases = $my_disease_manager->getListByAccountIdAndIsArchiveWithPaging(Acc::accountId(), $page, $per_page);
      $count = count($my_disease_manager->getListByAccountIdAndIsArchive(Acc::accountId()));
    }

    $this->view->diseases = $diseases;
    $this->view->page = $page;
    $this->view->pages_num = (int)(($count - 1) / $per_page) + 1;

    $this->view->page_title = 'Профиль - Заболевания - Архив - «' . SITE_NAME . '»';
  }

  public function my_clinic()
  {
    AuthHelper::checkAuth();

    $clinic_manager = new ClinicManager();
    $clinics = $clinic_manager->getListByPastVisitAndMyDoctorsByAccountId(Acc::accountId());

    if (count($clinics)) {
      $types = array();
      foreach ($clinics as $clinic) {
        if ($clinic->is_pregnant)
          $types['pregnant'] = '<option value="pregnant">Для беременных</option>';

        if ($clinic->is_handicapped)
          $types['handicapped'] = '<option value="handicapped">Для инвалидов</option>';

        if ($clinic->is_children)
          $types['children'] = '<option value="children">Детская</option>';

        if ($clinic->is_day_and_night)
          $types['day_night'] = '<option value="day_night">Круглосуточная</option>';
      }
      $this->view->clinic_types = $types;
    }

    $purpose_of_visit_manager = new PurposeOfVisitManager();
    $purpose_of_visit_names = $purpose_of_visit_manager->getListByAccountIdAndPastVisit(Acc::accountId());
    $this->view->purpose_of_visit_names = $purpose_of_visit_names;

    $this->view->page_title = 'Профиль - Мои клиники - «' . SITE_NAME . '»';
  }

  public function ajaxGetClinicsListByPastVisit()
  {
    if (!Acc::isAuthed()) {
      JsonResponse::error(ValidationErrorCodes::NOT_AUTHED);
    }

    $this->layout = 'ajax';

    $type_of_clinic = $this->request('type_of_clinic', '');
    $purpoise_of_visit = $this->request('purpoise_of_visit', '');
    $page = $this->request('page', 1);
    $account_id = $this->request('account_id', 0);

    $params = new PastClinicVisitSearchParams();
    $params->type_of_clinic = $type_of_clinic;
    $params->purpoise_of_visit = $purpoise_of_visit;
    $params->account_id = $account_id;

    $params->offset = ($page == 1) ? 0 : (4 + ($page - 2) * 10);
    $params->limit = ($page == 1) ? 4 : 10;

    $clinic_manager = new ClinicManager();
    $clinics = $clinic_manager->getListByPastVisitSearchParams($params);
    $this->view->clinics = $clinics;

    $clinics_count = count($clinics);
    if ($clinics_count == 0)
      JsonResponse::error(2);

    $params->limit = ($page == 1) ? 5 : 11;
    $more_clinics = $clinic_manager->getListByPastVisitSearchParams($params);
    $more_count = count($more_clinics);
    if ($more_count == 5 || $more_count == 11)
      $more_button = 1;
    else
      $more_button = 0;

    $html = $this->renderInString('clinic/card_small_list');
    JsonResponse::result(array(
      'html' => $html,
      'count' => $clinics_count,
      'more_button' => $more_button
    ));
  }

  public function ajaxGetMyClinics()
  {
    if (!Acc::isAuthed()) {
      JsonResponse::error(ValidationErrorCodes::NOT_AUTHED);
    }

    $this->layout = 'ajax';

    $type_of_clinic = $this->request('type_of_clinic', '');
    $purpoise_of_visit = $this->request('purpoise_of_visit', '');
    $account_id = $this->request('account_id', 0);
    $page = $this->request('page', 1);
    $reload = $this->request('reload', 0);

    $params = new MyClinicSearchParams();
    $params->type_of_clinic = $type_of_clinic;
    $params->purpoise_of_visit = $purpoise_of_visit;
    $params->account_id = $account_id;

    if ($reload == 1) {
      $params->offset = 0;
      $params->limit = 10 + ($page - 1) * 10;
    } else {
      $params->offset = ($page == 1) ? 0 : (10 + ($page - 2) * 10);
      $params->limit = 10;
    }

    $clinic_manager = new ClinicManager();
    $clinics = $clinic_manager->getMyClinicListBySearchParams($params);
    $this->view->is_close_card = 1;
    $this->view->clinics = $clinics;

    $count = count($clinics);
    if ($count == 0)
      JsonResponse::error(2);


    if ($reload == 1) {
      $params->offset = 0;
      $params->limit = 10 + ($page - 1) * 10;
    } else {
      $params->offset = ($page == 1) ? 0 : (10 + ($page - 2) * 10);
      $params->limit = 11;
    }
    $more_clinics = $clinic_manager->getMyClinicListBySearchParams($params);
    $more_count = count($more_clinics);
    if ($more_count == 11)
      $more_button = 1;
    else
      $more_button = 0;

    $html = $this->renderInString('clinic/card_small_list');
    JsonResponse::result(array('html' => $html, 'count' => $count, 'more_button' => $more_button));
  }

  public function reviews()
  {
    AuthHelper::checkAuth();

    $visit_manager = new VisitManager();
    $this->view->visits = $visit_manager->getPastListByAccountId(Acc::accountId());

    $clinic_review_manager = new ClinicReviewManager();
    $doctor_review_manager = new DoctorReviewManager();
    $clinic_review = $clinic_review_manager->getListByAccountId(Acc::accountId());
    $doctor_review = $doctor_review_manager->getListByAccountId(Acc::accountId());
    $this->view->review_tabs = (bool)($clinic_review && $doctor_review);

    $this->view->page_title = 'Профиль - Отзывы - «' . SITE_NAME . '»';
  }

  public function ajaxGetDoctorsReviews()
  {
    if (!Acc::isAuthed()) {
      JsonResponse::error(ValidationErrorCodes::NOT_AUTHED);
    }

    $this->layout = 'ajax';

    $account_id = $this->request('account_id', 0);
    $page = $this->request('page', 1);

    $offset = ($page == 1) ? 0 : (10 + ($page - 2) * 10);
    $limit = 10;

    $doctor_review_manager = new DoctorReviewManager();
    $last_reviews = $doctor_review_manager->getListByAccountIdWithPadding($account_id, $offset, $limit);

    $visit_rating_manager = new VisitRatingManager();
    $visit_rating = $visit_rating_manager->getOneByAccountId($account_id);

    if ($page == 1) {
      $more_last_reviews = $doctor_review_manager->getListByAccountIdWithPadding($account_id, 0, 11);
    } else {
      $offset = (10 + ($page - 2) * 10);
      $more_last_reviews = $doctor_review_manager->getListByAccountIdWithPadding($account_id, $offset, 11);
    }

    $more_count = count($more_last_reviews);
    $more_button = ($more_count == 11) ? 1 : 0;

    $per_page = 10;
    $this->view->per_page = $per_page;
    $this->view->visit_rating = $visit_rating;
    $this->view->last_reviews = $last_reviews;
    $count = count($last_reviews);

    $html = $this->renderInString('account/blocks/card_list_last_doctor_review');
    JsonResponse::result(array('html' => $html, 'count' => $count, 'more_button' => $more_button));
  }

  public function ajaxGetClinicsReviews()
  {
    if (!Acc::isAuthed()) {
      JsonResponse::error(ValidationErrorCodes::NOT_AUTHED);
    }

    $this->layout = 'ajax';

    $account_id = $this->request('account_id', 0);
    $page = $this->request('page', 1);

    $offset = ($page == 1) ? 0 : (10 + ($page - 2) * 10);
    $limit = 11;

    $clinic_review_manager = new ClinicReviewManager();
    $last_reviews = $clinic_review_manager->getListByAccountIdWithPadding($account_id, $offset, $limit);

    $visit_rating_manager = new VisitRatingManager();
    $visit_rating = $visit_rating_manager->getOneByAccountId($account_id);

    if ($page == 1) {
      $more_last_reviews = $clinic_review_manager->getListByAccountIdWithPadding($account_id, 0, 11);
    } else {
      $offset = (10 + ($page - 2) * 10);
      $more_last_reviews = $clinic_review_manager->getListByAccountIdWithPadding($account_id, $offset, 11);
    }

    $more_count = count($more_last_reviews);
    $more_button = ($more_count == 11) ? 1 : 0;

    $per_page = 10;
    $this->view->per_page = $per_page;
    $this->view->visit_rating = $visit_rating;
    $this->view->last_reviews = $last_reviews;
    $count = count($last_reviews);

    $html = $this->renderInString('account/blocks/card_list_last_clinic_review');
    JsonResponse::result(array('html' => $html, 'count' => $count, 'more_button' => $more_button));
  }

  public function exportCSVWithAccountActivity()
  {
    header('Content-type: text/csv; charset=Windows-1251');
    header("Content-Disposition: attachment;filename=account_activity_log.csv");

    $log_manager = new LogManager();
    $logs = $log_manager->getListLogs();

    $f = fopen(PUBLIC_ROOT . '/file.csv', 'w+');
    $str = '№;Код события;Срока запроса;Аккаунт;Дата;Врачи в городе' . "\r\n";

    fputs($f, iconv('utf-8', 'cp1251', $str));

    $number = 1;

    foreach ($logs as $log) {

      $log['id'] = $number;

      switch ($log['is_city_without_doctor']) {
        case '0':
          $log['is_city_without_doctor'] = 'да';
          break;
        case '1':
          $log['is_city_without_doctor'] = 'нет';
          break;
        case null:
          $log['is_city_without_doctor'] = '-';
          break;
      };

      $line_str = '';
      foreach ($log as $val) {
        $line_str .= iconv('utf-8', 'cp1251', $val) . ';';
      }
      $line_str = trim($line_str, ';');
      $line_str .= "\r\n";
      fputs($f, $line_str);
      $number++;
    }
    fclose($f);
    echo file_get_contents(PUBLIC_ROOT . '/file.csv');
    exit();
  }

  public function orders()
  {
    AuthHelper::checkAuth();

    $this->view->page_title = 'Профиль - Заказы лекарств - "' . SITE_NAME . '"';
    $this->render('shop/orders/orders');
  }

  public function ajaxGetOrders()
  {
    /**
     * @var OrderManager $order_manager
     * @var OrderModel[] $orders
     * @var OrderStatusManager $order_status_manager
     * @var OrderStatusModel $status
     */

    $this->layout = 'ajax';

    $offset = 0;
    $year = 0;

    if (isset($_POST['page']) && (int)$_POST['page'] > 0) {
      $offset = ((int)$_POST['page'] - 1) * 3;
    }

    if (isset($_POST['year']) && (int)$_POST['year'] > 0) {
      $year = (int)$_POST['year'];
    }

    $order_manager = ModelManagerFactory::getByName('order');
    $orders = $order_manager->getListByAccountIdOrderByDateWithLimit(Acc::accountId(), $offset);

    $html = '';
    $button_more_enable = 0;
    $last_year = 0;

    if (count($orders) > 0) {
      if (count($orders) == 4) {
        $button_more_enable = 1;
        unset($orders[3]);

        $last_year = DateViewHelper::date($orders[2]->dt_order, 'only_year');
      }

      $this->view->orders = $orders;
      $this->view->year = $year;
      $this->view->offset = $offset;

      $html = $this->renderInString('shop/blocks/order_block');
    }

    $result = array('html' => $html, 'button_more_enable' => $button_more_enable, 'year' => $last_year);

    JsonResponse::result($result);
  }

  public function ajaxChangeOrderStatus()
  {
    /**
     * @var OrderManager $order_manager
     */

    $this->layout = 'ajax';

    if (isset($_POST['id']) && (int)$_POST['id'] > 0 && isset($_POST['status']) && (int)$_POST['status'] > 0) {
      // Изменится при добавлении большего количества состоний заказов
      if ((int)$_POST['status'] == 1) {
        $updated_status = 2;
        $value = 'Восстановить заказ';
      } else {
        $updated_status = 1;
        $value = 'Отменить заказ';
      }

      $order_manager = ModelManagerFactory::getByName('order');
      $order_manager->updateStateById($_POST['id'], $updated_status);

      $result = array('status' => $updated_status, 'value' => $value);

      JsonResponse::result($result);
    }
  }

  // Можно будет использовать для корзины. В истории заказов нет очистки
  public function ajaxClearOrder()
  {
    /**
     * @var ProductToOrderManager $product_to_order_manager
     * @var OrderManager $order_manager
     */

    $this->layout = 'ajax';

    if (isset($_POST['id']) && (int)$_POST['id'] > 0) {
      $product_to_order_manager = ModelManagerFactory::getByName('product_to_order');
      $product_to_order_manager->deleteListByOrderId($_POST['id']);
      $order_manager = ModelManagerFactory::getByName('order');
      $order_manager->deleteOneByOrderId($_POST['id']);

      $result = array('state_name' => 'Товары из заказа удалены');

      JsonResponse::result($result);
    }
  }

  public function exportCSVSearchLog()
  {
    /**
     * @var SearchLogManager $search_log_manager
     * @var SearchLogModel[] $search_log
     */

    $date_start = $this->request('date_start');
    $date_start = DateHelper::changeFormat($date_start);
    $date_finish = $this->request('date_finish');
    $date_finish = DateHelper::changeFormat($date_finish);

    $search_log_manager = ModelManagerFactory::getByName('search_log');
    $search_log = $search_log_manager->getListByDates($date_start, $date_finish);

    $result = array();
    $result[] = array('№', 'Текст запроса', 'Запрос успешный', 'Время выполнения запроса');

    $number = 1;
    foreach ($search_log as $log) {
      $result[] = array(
        $number,
        $log->query,
        ($log->is_successful) ? 'Да' : 'Нет',
        date('d.m.Y H:i:s', strtotime($log->time)),
      );

      $number++;
    }

    $csv_generator = new CsvGenerator();
    $csv = $csv_generator->generateFromArray($result);
    PhpHeaderHelper::csv('search_log.csv');
    echo $csv;

    exit();
  }
}

