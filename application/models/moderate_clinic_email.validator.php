<?php
	class ModerateClinicEmailValidator extends ModelValidator
	{
		public function validate(ModerateClinicEmailModel $moderate_clinic_email)
		{
			$validation_rules = Register::get('validation_rules');

			$validator = new Validator();
			$validator->validate($moderate_clinic_email->email, $validation_rules->get('clinic_email'), $moderate_clinic_email);

			if(!$validator->checkStatus())
			{
				$this->error_codes = $validator->getErrorCodes();
				$this->error_messages = $validator->getErrorMessages();
				return false;
			}

			return true;
		}
	}