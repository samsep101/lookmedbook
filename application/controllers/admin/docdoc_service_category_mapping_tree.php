<?php

/**
 * Назван так, потому что CMS требует именно такой формат имени контроллера, а разбираться дальше было лениво
 * Class docdoc_service_category_mapping_treeAdminController
 */
class docdoc_service_category_mapping_treeAdminController extends CmsGenerator
{
    public function delete_list()
    {
        $mappedServiceIds = $this->request("delete_list");
        if ($mappedServiceIds) {
            foreach ($mappedServiceIds as $mappedServiceId) {
                if ($mappedServiceId) {
                    /** @var DocdocServiceCategoryMappingTreeManager $manager */
                    $manager = ModelManagerFactory::getByName($this->modelName);
                    $manager->deleteByMappedId($mappedServiceId);
                }
            }
        }

        $destination = $this->request('destination', '');

        if ($destination)
            $this->redirectUrl($destination);

        $this->redirectUrl(ADMIN_FOLDER . '/' . $this->dataModel->getModelName());
    }


    public function edit_not_mapped()
    {
        $service_ids = $this->request->post('not_mapped_list');

        foreach ($service_ids as $service_id) {
            $mappingRule = new DocdocServiceCategoryMappingModel();
            $mappingRule->mapped_service_id = $service_id;
            $mappingRule->service_id = $service_id;
            $mappingRule->save();
        }

        $destination = $this->request('destination', '');

        if ($destination)
            $this->redirectUrl($destination);

        $this->redirectUrl(ADMIN_FOLDER . '/' . $this->dataModel->getModelName());
    }
}