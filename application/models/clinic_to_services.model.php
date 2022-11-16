<?php

/**
 * Class TestingModel
 *
 * @property int             $id
 * @property datetime        $clinic_id
 * @property int             $clinic_service_id
 */
class ClinicToServicesModel extends DynamicModel
{
    protected function _field_service()
    {
        $type = array();

        if ($this->clinic_service_id)
        {
            $clinic_type_manager = ModelManagerFactory::getByName('clinic_services');
            $type                = $clinic_type_manager->getOneById($this->clinic_service_id);
        }

        return $type ? $type : array();
    }
}