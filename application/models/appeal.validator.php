<?php
    class AppealValidator extends ModelValidator
    {
        public function validate(DynamicModel $model)
        {
            /**
             * @var AppealModel $model
             * @var ValidationRules $validation_rules
             */

            $validation_rules = Register::get('validation_rules');

            $validator = new Validator();

            $validate_fields = array('first_name', 'phone_number', 'specialty_id', 'title');

            if(isset($model->do_not_check) && count($model->do_not_check))
            {
                foreach($validate_fields AS $vfValue)
                {
                    if(!in_array($vfValue, $model->do_not_check))
                    {
                        $validator->validate($model->$vfValue, $validation_rules->get('appeal_' . $vfValue), $model);
                    }
                }
            }
            else
            {
                $validator->validate($model->first_name, $validation_rules->get('appeal_first_name'), $model);
                $validator->validate($model->phone_number, $validation_rules->get('appeal_phone_number'), $model);
                $validator->validate($model->specialty_id, $validation_rules->get('appeal_specialty_id'), $model);
                $validator->validate($model->title, $validation_rules->get('appeal_title'), $model);
            }

            if (!$validator->checkStatus())
            {
                $this->error_codes = $validator->getErrorCodes();
                $this->error_messages = $validator->getErrorMessages();

                return false;
            }

            return true;
        }
    }