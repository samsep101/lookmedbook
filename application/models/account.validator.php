<?php
	class AccountValidator extends ModelValidator
	{
		public function validate(AccountModel $account)
		{
			$validation_rules = Register::get('validation_rules');

			$validator = new Validator();

			$validator->validate($account->email, $validation_rules->get('clinic_email'), $account);

			if(!$account->getId() || $account->password)
			{
				$validator->validate($account->password, $validation_rules->get('password'), $account);
			}

			if(!$validator->checkStatus())
			{
				$this->error_codes = $validator->getErrorCodes();
				$this->error_messages = $validator->getErrorMessages();
				return false;
			}

			return true;
		}

	}