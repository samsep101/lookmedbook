<?php
	class AccountLandingValidator extends ModelValidator
	{
		public function validate(AccountModel $account)
		{
			$validation_rules = Register::get('validation_rules');

			$validator = new Validator();

			$validator->validate($account->email, $validation_rules->get('email'), $account);

			if(!$validator->checkStatus())
			{
				$this->error_codes = $validator->getErrorCodes();
				$this->error_messages = $validator->getErrorMessages();
				return false;
			}

			return true;
		}

	}