<?php
	class ClinicValidator extends ModelValidator
	{
		public function validate(DynamicModel $model)
		{
			/**
			 * @var ClinicModel $model
			 */
			$validation_rules = Register::get('validation_rules');

			$validator = new Validator();
			$validator->validate($model->date_contract, $validation_rules->get('date'), $model);
			$validator->validate($model->name, $validation_rules->get('name'), $model);
            $validator->validate($model->city_id, $validation_rules->get('city_id'), $model);

			if(!$validator->checkStatus())
			{
				$this->error_codes = $validator->getErrorCodes();
				$this->error_messages = $validator->getErrorMessages();
				return false;
			}

			return true;
		}
	}
