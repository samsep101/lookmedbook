<?php
	class DoctorReviewValidator extends ModelValidator
	{
		public function validate(DoctorReviewModel $doctor_review)
		{
			$validation_rules = Register::get('validation_rules');

			$validator = new Validator();
			$validator->validate($doctor_review->doctor_review_text, $validation_rules->get('doctor_review'), $doctor_review);
			$validator->validate($doctor_review->doctor_id, $validation_rules->get('doctor'), $doctor_review);

			if(!$validator->checkStatus())
			{
				$this->error_codes = $validator->getErrorCodes();
				$this->error_messages = $validator->getErrorMessages();
				return false;
			}

			return true;
		}
	}
