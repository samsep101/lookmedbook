<?php

/**
 * Class TestingModel
 *
 * @property int             $id
 * @property int             $clinic_id
 * @property int             $clinic_type_id
 */
class ClinicToTypesModel extends DynamicModel
{

    protected function _field_full_name()
    {
    }

    protected function _field_visit()
    {
    }

    protected function _field_target_call()
    {
    }

    protected function _field_type()
    {
        $type = array();

        if ($this->clinic_type_id)
        {
            $clinic_type_manager = ModelManagerFactory::getByName('clinic_type');
            $type                = $clinic_type_manager->getOneById($this->clinic_type_id);
        }

        return $type ? $type : array();
    }
}