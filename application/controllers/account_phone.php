<?php
	class Account_phoneController extends BaseController
	{
		public function ajaxSendConfirmedCode()
		{
			$phone_id = $this->request('phone_id');
			$phone_number = $this->request('phone_number');

			if(!$phone_id && !StringHelper::isPhoneNumber($phone_number))
			{
				JsonResponse::error(ValidationErrorCodes::WRONG_DATA);
			}

			$account_phone_verification = new AccountPhoneVerification();
			$check_id = $account_phone_verification->createCode($phone_number, $phone_id);

			JsonResponse::result(array(
									  'check_id' => (int)$check_id
								 ));
		}

		public function ajaxConfirmPhoneCode()
		{
			$this->layout = 'ajax';

			$confirm_code = $this->request('confirm_code');
			$check_id = $this->request('check_id');

			$account_phone_verification = new AccountPhoneVerification();

			if($account_phone_verification->checkCode($check_id, $confirm_code))
			{
				JsonResponse::result(true);
			} else {
				JsonResponse::error(ValidationErrorCodes::ERROR);
			}
		}
	}