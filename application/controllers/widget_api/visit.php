<?php

    class VisitWidgetApiController extends ApiController
    {
        public function add()
        {
            $full_name = $this->request('full_name');
            $phone = $this->request('phone');
            $comment = $this->request('comment');
            $city_id = $this->request->get('city_id');
            $specialty_id = $this->request('specialty_id');
            $email = $this->request('email');
            $check_id = $this->request('check_id');
            $code = $this->request('code');

            $identifier = $this->request('widget_identifier');

            if (!$full_name || !$phone)
			{
				ApiHeader::error(ApiRequestErrors::INCORRECT_PARAMS);
			}

            /**
             * @var WidgetSiteManager $widget_site_manager
             */

            $widget_site_manager = ModelManagerFactory::getByName('widget_site');
            $widget_site = $widget_site_manager->getOneByIdentifier($identifier);
            if(!$widget_site)
            {
                ApiHeader::error(ApiRequestErrors::INVALID_WIDGET_IDENTIFIER);
            }

			if($widget_site->require_sms)
			{
				if(!$check_id || !$code)
				{
					ApiHeader::error(ApiRequestErrors::INCORRECT_PARAMS);
				}
			}

            // Для успешного прохождения заявки необходимо получить
            // check_id подтвежденного номера телефона и код
			if($widget_site->require_sms)
			{
				$account_phone_verification = new AccountPhoneVerification();
				if (!$account_phone_verification->checkCode($check_id, $code)) {
					ApiHeader::error(ApiRequestErrors::INVALID_CONFIRM_CODE);
				}

				if  (!$account_phone_verification->checkStatusByCheckIdAndPhoneNumber($check_id, $phone)) {
					ApiHeader::error(ApiRequestErrors::INVALID_CONFIRM_CODE);
				}
			}

            /**
             * @var AccountPhoneManager $account_phone_manager
             */
            $account_phone_manager = ModelManagerFactory::getByName('account_phone');
            $account_phone = $account_phone_manager->getOneConfirmedByPhoneNumber($phone);
            if(!$account_phone)
            {
                $account = new AccountModel();
                $account->disableValidation();
                $account->save();

                $account_phone = new AccountPhoneModel();
                $account_phone->account_id = $account->getId();
                $account_phone->phone = $phone;
                $account_phone->is_confirmed = 1;
                $account_phone->dt = DateHelper::now();
                $account_phone->save();

                $account_id = $account->getId();
            } else {
                $account_id = $account_phone->account_id;
            }

            $visit = new VisitModel();
            $visit->full_name = $full_name;
            $visit->phone = $phone;
            $visit->comment = $comment;
            $visit->city_id = $city_id;
            $visit->specialty_id = $specialty_id;
            $visit->account_id = $account_id;
            $visit->email = $email;
            $visit->visit_channel_id = VisitChannelModel::WIDGET;
            $visit->widget_site_id = $widget_site->getId();

            if (!$visit->save()) {
                $error_code = $visit->getValidator()->getErrorCodes();
                ApiHeader::error($error_code[0]);
            }

            ApiHeader::response(1);
        }

    }