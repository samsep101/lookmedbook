<?php
    class TargetCallValidator extends ModelValidator
    {
        public function validate(DynamicModel $model)
        {
            /**
             * @var TargetCallModel $model
             * @var ValidationRules $validation_rules
             */

            $validation_rules = Register::get('validation_rules');

            $validator = new Validator();
            $validator->validate($model->phone, $validation_rules->get('target_call_phone'), $model);

            if (!$validator->checkStatus())
            {
                $this->error_codes = $validator->getErrorCodes();
                $this->error_messages = $validator->getErrorMessages();

                return false;
            }

            return true;
        }
    }