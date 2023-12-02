<?php
	class UserValidator extends ModelValidator
	{
		public function validate(UserModel $user)
		{
			$validation_rules = Register::get('validation_rules');

			$validator = new Validator();

			$validator->validate($user->login, $validation_rules->get('user_login'), $user);
			$validator->validate($user->password, $validation_rules->get('user_login'), $user);
			$validator->validate($user->role_id, $validation_rules->get('user_role'), $user);

			if(!$validator->checkStatus())
			{
				$this->error_messages = $validator->getErrorMessages();
				$this->error_codes = $validator->getErrorCodes();

				return false;
			}

			return true;
		}
	}