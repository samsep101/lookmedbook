<?php

class DocdocServiceCategoryAdminController extends AdminBaseController
{
    public function ajaxDontMap()
    {
        $service_ids = $this->request->post('not_mapped_list');

        foreach ($service_ids as $service_id) {
            $mappingRule = new DocdocServiceCategoryMappingModel();
            $mappingRule->mapped_service_id = $service_id;
            $mappingRule->service_id = $service_id;
            $mappingRule->save();
        }

        JsonResponse::result([]);
    }
}
