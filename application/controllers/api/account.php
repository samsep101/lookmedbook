<?php
	class AccountApiController extends ApiController
	{
		public $layout = 'ajax';

		/**
		 * @return array
		 */
		public function getCachedMethods()
		{
			return array(
				'getOneById' => array(
					'tags' => array(
						'account:%user_id%',
						'account',
					),
				),
			);
		}


		// авторизация пользователя
		public function auth()
		{
			$email = $this->request('email');
			$password_hash = $this->request('password');

			if (!$email || !$password_hash)
				ApiHeader::error(ApiRequestErrors::INCORRECT_PARAMS);

			$account_manager = new AccountManager();
			$account = $account_manager->getOneByEmailAndPasswordHash($email, $password_hash);

			if (!$account)
				ApiHeader::error(ApiRequestErrors::INCORRECT_LOGIN_OR_PASSWORD);

			$session_hash = md5(StringGeneratorHelper::generate(20));
			ApiController::setAccountSession($account->getId(), $session_hash);

			$result = array(
				'session' => $session_hash,
				'account_id' => $account->getId(),
			);

			ApiHeader::response($result);
		}

		// регистрация пользователя
		public function register()
		{
			$email = $this->request('email');
			$password_hash = $this->request('password');
			$first_name = $this->request('first_name');
			$last_name = $this->request('last_name');
			$middle_name = $this->request('middle_name');
			$phone = $this->request('phone');

			if (!$email || !$password_hash)
				ApiHeader::error(ApiRequestErrors::INCORRECT_PARAMS);

			$account_manager = new AccountManager();
			if ($already_email = $account_manager->getOneByEmail($email))
				ApiHeader::error(ApiRequestErrors::EMAIL_ALREADY_REGISTRED);

			$account = new AccountModel();
			$account->email = $email;
			$account->password_hash = $password_hash;
			$account->first_name = $first_name;
			$account->last_name = $last_name;
			$account->middle_name = $middle_name;
			$account->setValidator(new WithoutValidator());

			if (!$account->save()) {
				$error_codes = $account->getValidator()->getErrorCodes();
				ApiHeader::error($error_codes[0]);
			}

			$session_hash = md5(StringGeneratorHelper::generate(20));
			ApiController::setAccountSession($account->getId(), $session_hash);

			if (isset($phone)) {
				$account_phone_manager = new AccountPhoneManager();

				if (!$account_phone_manager->getOneByPhone($phone)) {
					$account_phone = new AccountPhoneModel();
					$account_phone->phone = $phone;
					$account_phone->account_id = $account_manager->getOneByEmail($email);
					$account_phone->code = StringGeneratorHelper::generateNumbers(3);
					$account_phone->dt = date('Y-m-d H:i:s');
					$account_phone->is_confirmed = 0;

					$account_phone->save();

					$account_phone = $account_phone_manager->getOneByPhone($phone);

					if ($account_phone) {
						$sms_sender = new SmsSender();

						$sms_sender->send('+' . $account_phone->phone, $account_phone->code);
					}
				}
			}

			$result = array(
				'session' => $session_hash,
				'account_id' => $account->getId(),
			);
			ApiHeader::response($result);
		}

		// редактирование профиля
		public function edit()
		{
			$account_id = $this->request('account_id');
			$session = $this->request('session');

			if (!$account_id || !$session)
				ApiHeader::error(ApiRequestErrors::INCORRECT_PARAMS);

			$account_manager = new AccountManager();
			/**
			 * @var AccountModel $account
			 */
			if (!$account = $account_manager->getOneById($account_id))
				ApiHeader::error(ApiRequestErrors::ACCOUNT_NOT_EXIST);

			$account_session_manager = new AccountSessionManager();
			if (!$account_session = $account_session_manager->getOneBySessionHashAndAccountId($session, $account->getId()))
				ApiHeader::error(ApiRequestErrors::WRONG_SESSION_KEY);

			$email = $this->request('email');
			$first_name = $this->request('first_name');
			$last_name = $this->request('last_name');
			$middle_name = $this->request('middle_name');
			$sex = $this->request('sex');
			$birthday = $this->request('birthday');
			$city = $this->request('city');
			$phone = $this->request('phone');
			$new_password_hash = $this->request('new_password');

			$account_phone_manager = new AccountPhoneManager();
			if (isset($phone) && !$old_phone = $account_phone_manager->getOneByAccountIdAndPhone($account_id, $phone)) {
				$account_phone = new AccountPhoneModel();
				$account_phone->phone = $phone;
				$account_phone->account_id = $account_id;
				$account_phone->code = StringGeneratorHelper::generateNumbers(3);
				$account_phone->dt = date('Y-m-d H:i:s');
				$account_phone->is_confirmed = 0;

				$account_phone->save();

				$account_phone = $account_phone_manager->getOneByPhone($phone);

				if ($account_phone) {
					$sms_sender = new SmsSender();

					$sms_sender->send('+' . $account_phone->phone, $account_phone->code);
				}
			}

            if ($email) {
                if ($account_manager->getOneByEmail($email))
                    ApiHeader::error(ApiRequestErrors::EMAIL_ALREADY_REGISTRED);
            }

			$account->email = ($email) ? $email : $account->email;
			$account->first_name = ($first_name) ? $first_name : $account->first_name;
			$account->last_name = ($last_name) ? $last_name : $account->last_name;
			$account->middle_name = ($middle_name) ? $middle_name : $account->middle_name;
			$account->sex_id = ($sex) ? $sex : $account->sex_id;
			$account->birthday = ($birthday) ? $birthday : $account->birthday;
			$account->city_id = ($city) ? $city : $account->city_id;
			$account->password_hash = ($new_password_hash) ? $new_password_hash : $account->password_hash;

			if ($account->save()) {
				ApiHeader::response(1, $this->e_tag);
			} else {
				$error_codes = $account->getValidator()->getErrorCodes();
				ApiHeader::error($error_codes[0]);
			}
		}

		// авторизация пользователя через соц. сети
		public function sn_auth()
		{
			$access_token = $this->request('access_token');
			$type = $this->request('type');
			$uid = $this->request('uid');

			if (!$access_token || !$type)
				ApiHeader::error(ApiRequestErrors::INCORRECT_PARAMS);

			$types['fb'] = 'fb';
			$types['vk'] = 'vk';
			$types['mailru'] = 'mailru';
			$types['ok'] = 'ok';

			if (!array_key_exists($type, $types))
				ApiHeader::error(ApiRequestErrors::INCORRECT_SN_TYPE);

			$result = array();

			$api_sn_auth = new ApiSnAuth($access_token, $uid, '');

			switch ($type) {
				case 'fb':
					$fb_auth = $api_sn_auth->fb_auth();
					$result['session'] = $fb_auth['session'];
					$result['account_id'] = $fb_auth['account_id'];
					break;
				case 'vk':
					$vk_auth = $api_sn_auth->vk_auth();
					$result['session'] = $vk_auth['session'];
					$result['account_id'] = $vk_auth['account_id'];
					break;
				case 'mailru':
					$mailru_auth = $api_sn_auth->mailru_auth();
					$result['session'] = $mailru_auth['session'];
					$result['account_id'] = $mailru_auth['account_id'];
					break;
				case 'ok':
					$ok_auth = $api_sn_auth->ok_auth();
					$result['session'] = $ok_auth['session'];
					$result['account_id'] = $ok_auth['account_id'];
					break;
			}

			ApiHeader::response($result);
		}

		public function getOneById()
		{
			$user_id = $this->request('user_id');

			if (!$user_id)
				ApiHeader::error(ApiRequestErrors::INCORRECT_PARAMS);

			/**
			 * @var AccountModel $account
			 */
			$account_manager = new AccountManager();
			$account = $account_manager->getOneById($user_id);

			if (!$account)
				ApiHeader::error(ApiRequestErrors::ACCOUNT_NOT_EXIST);

			$phones = array();
			foreach ($account->phones as $phone) {
				$phones[] = '+' . $phone->phone;
			}

			/**
			 * @var CityManager $city_manager
			 */
			$city_manager = ModelManagerFactory::getByName('city');

			//$city = array();
			if ($account->city) {
				$city = array(
					'id' => $account->city->getId(),
					'name' => $account->city->name,
					'prepositional_name' => $account->city->prepositional_name
				);
			} else {
				$moscow = $city_manager->getOneByName('Москва');

				/**
				 * @var CityModel $moscow
				 */
				$city = array(
					'id' => $moscow->getId(),
					'name' => $moscow->name,
					'prepositional_name' => $moscow->prepositional_name
				);
			}

			$result = array(
				'account_id' => $user_id,
				'email' => $account->email,
				'first_name' => $account->first_name,
				'last_name' => $account->last_name,
				'middle_name' => $account->middle_name,
				'full_name' => $account->full_name,
				'nick' => $account->nick,
				'sex' => array(
					'id' => $account->sex_id,
					'name' => (($account->sex_id) ? true : false) ? (($account->sex_id == 1) ? 'Мужской' : 'Женский') : '',
				),
				'birthday' => $account->birthday,
				'city' => $city,
				'phone_numbers' => $phones,
				'image' => array(
					'id' => $account->image_id,
					'url' => ($account->image) ? $account->image->path : '',
				),
			);

			ApiHeader::response($result, $this->e_tag);
		}

		public function isEmailUnique()
		{
			$email = $this->request('email');

			if (!$email)
				ApiHeader::error(ApiRequestErrors::INCORRECT_PARAMS);

			$account_manager = new AccountManager();

			if ($account_manager->getOneByEmail($email)) {
				ApiHeader::response(0);
			} else {
				ApiHeader::response(1);
			}
		}

		public function isPhoneUnique()
		{
			$phone = $this->request('phone');

			if (!$phone)
				ApiHeader::error(ApiRequestErrors::INCORRECT_PARAMS);

			$account_phone_manager = new AccountPhoneManager();

			if ($account_phone_manager->getOneByPhone($phone)) {
				ApiHeader::response(0);
			} else {
				ApiHeader::response(1);
			}
		}

		public function setToken()
		{
			if (!$this->isAuthorizationUser()) {
				ApiHeader::error(ApiRequestErrors::WRONG_SESSION_KEY);
			}

			$token = $_GET['notification_token'];
			$timezone = $_GET['timezone'];

			if(!$token || !$timezone)
			{
				ApiHeader::error(ApiRequestErrors::INVALID_PARAMS);
			}

			/**
			 * @var MobileNotificationTokenManager $mobile_notification_token_manager
			 */
			$mobile_notification_token_manager = ModelManagerFactory::getByName('mobile_notification_token');

			$mobile_notification_token = $mobile_notification_token_manager->getOneByAccountId($this->authorized_account->getId());

			if (!$mobile_notification_token) {
				$mobile_notification_token = new MobileNotificationTokenModel();
			}

			$mobile_notification_token->account_id = $this->authorized_account->getId();
			$mobile_notification_token->token = $token;
			$mobile_notification_token->timezone = $timezone;
			$mobile_notification_token->save();

			ApiHeader::response(1);
		}

        public function confirmPhone()
        {
            /**
             * @var AccountPhoneManager $account_phone_manager
             */

            $phone = $this->request('phone');
            $code = $this->request('code');

            if (!$phone || !$code)
                ApiHeader::error(ApiRequestErrors::INCORRECT_PARAMS);

            $account_phone_manager = ModelManagerFactory::getByName('account_phone');
            $account_phone = $account_phone_manager->getOneByPhone($phone);

            if ($account_phone) {
                if ($code == $account_phone->code) {
                    $account_phone->is_confirmed = 1;
                    $account_phone->save();

                    if ($account_phone->account_id) {
                        $session = StringGeneratorHelper::generate(20);
                        ApiController::setAccountSession($account_phone->account_id, $session);

                        $result = array(
                            'account_id' => $account_phone->account_id,
                            'session' => $session
                        );
                        ApiHeader::response($result, $this->e_tag);
                    } else {
                        ApiHeader::error(ApiRequestErrors::ACCOUNT_NOT_EXIST);
                    }
                } else {
                    ApiHeader::error(ApiRequestErrors::WRONG_CODE);
                }
            } else {
                ApiHeader::error(ApiRequestErrors::PHONE_NOT_EXIST);
            }
        }

	}

