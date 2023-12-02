<?php
	class ModerateClinicPhoneValidator extends ModelValidator
	{
		public function validate(ModerateClinicPhoneModel $moderate_clinic_phone)
		{
			$validation_rules = Register::get('validation_rules');

			$validator = new Validator();
			$validator->validate($moderate_clinic_phone->phone_number, $validation_rules->get('clinic_phone'), $moderate_clinic_phone);

			if(!$validator->checkStatus())
			{
				$this->error_codes = $validator->getErrorCodes();
				$this->error_messages = $validator->getErrorMessages();
				return false;
			}

			return true;
		}
	}