<?php
class PurposeOfVisitApiController extends ApiController
{
    public $layout = 'ajax';

    public function getCachedMethods()
    {
        return array(
            'getList' => array(
                'purpose_of_visit:list',
                'purpose_of_visit'
            )
        );
    }

    // получение списка целей визита по специальности
    public function getList()
    {
        $specialty_id = $this->request('specialty_id');

        if (!$specialty_id)
            ApiHeader::error(ApiRequestErrors::INCORRECT_PARAMS);

        $purpose_of_visit_to_specialty_manager = new PurposeOfVisitToSpecialtyManager();
        $purposes_of_visit_to_specialty = $purpose_of_visit_to_specialty_manager->getListBySpecialtyId($specialty_id);

        if (!$purposes_of_visit_to_specialty)
            ApiHeader::error(ApiRequestErrors::PURPOSE_OF_VISIT_TO_SPECIALTY_NOT_EXIST);

        foreach ($purposes_of_visit_to_specialty as $purpose_of_visit_to_specialty) {
            $result[] = array(
                'purpose_of_visit_id' => ($purpose_of_visit_to_specialty->purpose_of_visit_id) ? $purpose_of_visit_to_specialty->purpose_of_visit_id : '',
                'name' => ($purpose_of_visit_to_specialty->purpose_of_visit_id) ? $purpose_of_visit_to_specialty->purpose_of_visit->name : '',
            );
        }

        ApiHeader::response($result, $this->e_tag);
    }
}