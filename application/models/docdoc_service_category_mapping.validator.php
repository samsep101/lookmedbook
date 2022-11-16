<?php

class DocdocServiceCategoryMappingValidator extends ModelValidator
{
    public function validate(DocdocServiceCategoryMappingModel $model)
    {
        $validation_rules = Register::get('validation_rules');

        $validator = new Validator();
        $validator->validate($model->service_id, $validation_rules->get('docdoc_service_category_mapping_service_service_id'), $model);
        $validator->validate($model->mapped_service_id, $validation_rules->get('docdoc_service_category_mapping_service_mapped_service_id'), $model);

        if(!$validator->checkStatus())
        {
            $this->error_codes = $validator->getErrorCodes();
            $this->error_messages = $validator->getErrorMessages();
            return false;
        }

        return true;
    }
}
