<?php
	class AccountPhoneValidator extends ModelValidator
	{
		public function validate(AccountPhoneModel $account_phone)
		{
			$validation_rules = Register::get('validation_rules');

			$validator = new Validator();
			$validator->validate($account_phone->phone, $validation_rules->get('phone'), $account_phone);

			if(!$validator->checkStatus())
			{
				$this->error_codes = $validator->getErrorCodes();
				$this->error_messages = $validator->getErrorMessages();
				return false;
			}

			return true;
		}

	}