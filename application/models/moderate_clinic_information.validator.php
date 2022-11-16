<?php
	class ModerateClinicInformationValidator extends ModelValidator
	{
		public function validate(ModerateClinicInformationModel $moderate_clinic_information)
		{
			$validation_rules = Register::get('validation_rules');

			$validator = new Validator();

			//if ($moderate_clinic_information->director_fio) {
			//$validator->validate($moderate_clinic_information->director_fio, $validation_rules->get('director_fio'), $moderate_clinic_information);
			//}

			//$validator->validate($moderate_clinic_information->full_name, $validation_rules->get('clinic_full_name'), $moderate_clinic_information);
			//$validator->validate($moderate_clinic_information->city_id, $validation_rules->get('required'), $moderate_clinic_information);
			//$validator->validate($moderate_clinic_information->address, $validation_rules->get('required'), $moderate_clinic_information);
//			$validator->validate($moderate_clinic_information->clinic_type_id, $validation_rules->get('clinic_type_id'), $moderate_clinic_information);

			if(!$validator->checkStatus())
			{
				$this->error_codes = $validator->getErrorCodes();
				$this->error_messages = $validator->getErrorMessages();
				return false;
			}

			return true;
		}

	}