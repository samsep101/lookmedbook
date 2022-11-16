<?php
	class PrivateReviewValidator extends ModelValidator
	{
		public function validate(PrivateReviewModel $private_review)
		{
			$validation_rules = Register::get('validation_rules');

			$validator = new Validator();
			$validator->validate($private_review->text, $validation_rules->get('private_review'), $private_review);

			if(!$validator->checkStatus())
			{
				$this->error_codes = $validator->getErrorCodes();
				$this->error_messages = $validator->getErrorMessages();
				return false;
			}

			return true;
		}
	}
 