<?php

class DiseaseDraftValidator extends ModelValidator
{
    public function validate(DiseaseDraftModel $model)
    {
        $validationRules = Register::get('validation_rules');
        $validator = new Validator();

        $validator->validate($model->name, $validationRules->get('name'), $model);
        $validator->validate($model->genitive_name, $validationRules->get('genitive_name'), $model);
        $validator->validate($model->prepositional_name, $validationRules->get('prepositional_name'), $model);

        if($validator->checkStatus())
        {
            return true;
        }

        $this->error_codes = $validator->getErrorCodes();
        $this->error_messages = $validator->getErrorMessages();
        return false;
    }
}