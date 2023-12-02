<?php
    class DoctorValidator extends ModelValidator
    {
        public function validate(DynamicModel $model)
        {
            /**
             * @var ClinicModel $model
             */
            $validation_rules = Register::get('validation_rules');

            $validator = new Validator();

            if (!$model->getId() && ($model->first_name || $model->last_name || $model->middle_name) && $model->to_validate)
                $validator->validate($model->first_name.'|'.$model->second_name.'|'.$model->last_name, $validation_rules->get('existing_doctor_by_fio'), $model);

            if(!$validator->checkStatus())
            {
                $this->error_codes = $validator->getErrorCodes();
                $this->error_messages = $validator->getErrorMessages();
                return false;
            }

            return true;
        }
    }