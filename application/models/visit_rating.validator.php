<?php
	class VisitRatingValidator extends ModelValidator
	{
		public function validate(VisitRatingModel $visit_review)
		{
			$validation_rules = Register::get('validation_rules');

			$validator = new Validator();
			/*$validator->validate($visit_review->cabinet, $validation_rules->get('cabinet'), $visit_review);
$validator->validate($visit_review->waiting_time, $validation_rules->get('waiting_time'), $visit_review);
$validator->validate($visit_review->relationship, $validation_rules->get('relationship'), $visit_review);
$validator->validate($visit_review->value_for_money, $validation_rules->get('value_for_money'), $visit_review);
$validator->validate($visit_review->diagnosis_is_clear, $validation_rules->get('diagnosis_is_clear'), $visit_review);
$validator->validate($visit_review->service_at_the_reception, $validation_rules->get('service_at_the_reception'), $visit_review);
$validator->validate($visit_review->is_doctor_advice, $validation_rules->get('is_doctor_advice'), $visit_review);
$validator->validate($visit_review->is_clinic_advice, $validation_rules->get('is_clinic_advice'), $visit_review);*/

			if(!$validator->checkStatus())
			{
				$this->error_codes = $validator->getErrorCodes();
				$this->error_messages = $validator->getErrorMessages();
				return false;
			}

			return true;
		}
	}
 