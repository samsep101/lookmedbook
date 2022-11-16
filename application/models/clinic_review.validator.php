<?php
	class ClinicReviewValidator extends ModelValidator
	{
		public function validate(ClinicReviewModel $clinic_review)
		{
			$validation_rules = Register::get('validation_rules');

			$validator = new Validator();
			$validator->validate($clinic_review->clinic_review_text, $validation_rules->get('clinic_review'), $clinic_review);

			if(!$validator->checkStatus())
			{
				$this->error_codes = $validator->getErrorCodes();
				$this->error_messages = $validator->getErrorMessages();
				return false;
			}

			return true;
		}
	}
 