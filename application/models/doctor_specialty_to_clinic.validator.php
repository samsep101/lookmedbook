<?php
	class DoctorSpecialtyToClinicValidator extends ModelValidator
	{

		public function validate(DoctorSpecialtyToClinicModel $doctor_specialty_to_clinic)
		{
			$validation_rules = Register::get('validation_rules');

			$validator = new Validator();

			$doctor_to_clinic_manager = new DoctorToClinicManager();

			$doctor_to_clinic = $doctor_to_clinic_manager->getOneByClinicIdAndDoctorId($doctor_specialty_to_clinic->clinic_id, $doctor_specialty_to_clinic->doctor_id);

			if(!$doctor_to_clinic)
			{
				$this->error_messages[] = 'Данный доктор не работает в выбранной клинике!';
				return false;
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