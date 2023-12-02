<?php

class SpecialtyToDiseaseDraftValidator extends ModelValidator
{
    public function validate(SpecialtyToDiseaseDraftModel $model)
    {
        $validationRules = Register::get('validation_rules');
        $validator = new Validator();

        $validator->validate($model->specialty_id, $validationRules->get('specialty_id'), $model);
        $validator->validate($model->disease_draft_id, $validationRules->get('disease_draft_id'), $model);

        if($validator->checkStatus())
        {
            return true;
        }

        $this->error_codes = $validator->getErrorCodes();
        $this->error_messages = $validator->getErrorMessages();
        return false;
    }
}
