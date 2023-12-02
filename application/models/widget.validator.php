<?php
    class WidgetValidator extends ModelValidator
    {
        public function validate(WidgetModel $model)
        {
            $validation_rules = Register::get('validation_rules');

            $validator = new Validator();

            $validator->validate($model->name, $validation_rules->get('widget_name'), $model);
            $validator->validate($model->folder, $validation_rules->get('widget_folder'), $model);
            $validator->validate($model->element_id, $validation_rules->get('widget_element'), $model);
            $validator->validate($model->specialty_id, $validation_rules->get('widget_specialty'), $model);

            if(!$validator->checkStatus())
            {
                $this->error_codes = $validator->getErrorCodes();
                $this->error_messages = $validator->getErrorMessages();
                return false;
            }

            return true;
        }
    }