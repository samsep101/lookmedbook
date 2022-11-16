<?php

class DocdocServiceCategoryMappingTreeValidator extends ModelValidator
{
    public function validate(DocdocServiceCategoryMappingTreeModel $model)
    {
        $validation_rules = Register::get('validation_rules');

        $validator = new Validator();
        $validator->validate($model->service_id, $validation_rules->get('docdoc_service_category_mapping_service_service_id'), $model);
        $validator->validate($model->mapped_service_id, $validation_rules->get('docdoc_service_category_mapping_service_mapped_service_id'), $model);

        /** @var ServiceCategoryManager $manager */
        $manager = ModelManagerFactory::getByName('service_category');

        /** @var ServiceCategoryModel $serviceCategory */
        $serviceCategory = $manager->getOneByDocDocId($model->mapped_service_id, ServiceCategoryModel::TYPE_SERVICE);
        $ignoreService = $manager->getOneByDocDocId($model->service_id, ServiceCategoryModel::TYPE_SERVICE);

        $mappedToChild = false;

        while ($serviceCategory && $serviceCategory->parent_id) {
            if ($serviceCategory->parent_id == $ignoreService->id) {
                $mappedToChild = true;
                break;
            }
            $serviceCategory = $manager->getOneById($serviceCategory->parent_id);
        }

        if ($mappedToChild) {
            $this->error_messages[] = 'Родительская услуга не может быть замэпплена на своего ребёнка';
            return false;
        }

        if(!$validator->checkStatus())
        {
            $this->error_codes = $validator->getErrorCodes();
            $this->error_messages = $validator->getErrorMessages();
            return false;
        }

        return true;
    }
}
