<?php
	class ModerateDoctorInformationValidator extends ModelValidator
	{
		public function validate(ModerateDoctorInformationModel $moderate_doctor_information)
		{
			$validation_rules = Register::get('validation_rules');

			$validator = new Validator();
			$validator->validate($moderate_doctor_information->first_name, $validation_rules->get('first_name'), $moderate_doctor_information);
			//$validator->validate($moderate_doctor_information->second_name, $validation_rules->get('second_name'), $moderate_doctor_information);
			$validator->validate($moderate_doctor_information->last_name, $validation_rules->get('last_name'), $moderate_doctor_information);
			$validator->validate($moderate_doctor_information->about, $validation_rules->get('about'), $moderate_doctor_information);

			if(!$validator->checkStatus())
			{
				$this->error_codes = $validator->getErrorCodes();
				$this->error_messages = $validator->getErrorMessages();
				return false;
			}

			return true;
		}
	}