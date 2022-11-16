<?php
    class AccountPhoneApiController extends ApiController
    {
        public $layout = 'ajax';

        public function confirm()
        {
            $phone = $this->request('phone');
            $code = $this->request('code');

            if (!$phone || !$code)
                ApiHeader::error(ApiRequestErrors::INCORRECT_PARAMS);

            $account_phone_manager = new AccountPhoneManager();
            $account_phone = $account_phone_manager->getOneByPhone($phone);

            if (!$account_phone)
                ApiHeader::error(ApiRequestErrors::PHONE_NOT_EXIST);

            if ($account_phone->is_confirmed == 1)
                ApiHeader::error(ApiRequestErrors::PHONE_CONFIRMED);

            $confirm_flag = false;

            if ($code == $account_phone->code){
                $account_phone->is_confirmed = 1;
                $confirm_flag = true;
            }

            if ($confirm_flag && $account_phone->save()){
                ApiHeader::response(1);
            } else {
                ApiHeader::response(0);
            }
        }

        public function checkPhone()
        {
            /**
            * @var AccountPhoneManager $account_phone_manager
            * @var AccountPhoneModel $account_phone
            */

            $phone = $this->request('phone');

            if (!$phone)
                ApiHeader::error(ApiRequestErrors::INCORRECT_PARAMS);

            $account_phone_manager = ModelManagerFactory::getByName('account_phone');
            $account_phone = $account_phone_manager->getOneByPhone($phone);

            if (!$account_phone)
                ApiHeader::error(ApiRequestErrors::PHONE_NOT_EXIST);

            $no_code = SettingsManager::get('no_code');

            if (!$no_code){
                $sms_sender = new SmsSender();
                $sms_sender->send('+' . $account_phone->phone, $account_phone->code);
            }

            $result = array(
                'checkId' => (int)$account_phone->getId(),
                'noCode' => ($no_code) ? $no_code : 0,
            );

            ApiHeader::response($result, $this->e_tag);
        }

        public function sendCode()
        {
            $phone_number = $this->request('phone');

            /**
             * @var AccountPhoneManager $account_phone_manager
             */
            $phone_verification = new AccountPhoneVerification();
            $check_id = $phone_verification->createCode($phone_number);

            $result = array(
                'check_id' => $check_id
            );

            ApiHeader::response($result);
        }

    }

