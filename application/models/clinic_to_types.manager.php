<?php

class ClinicToTypesManager extends ModelManager
{
    protected $table_name = 'clinic_to_types';
    protected $model_name = 'ClinicToTypesModel';

    public function beforeSave(DynamicModel $model)
    {
    }

    public function afterSave(DynamicModel $model)
    {
    }

    public function getTypesToIdentifyTypesTheCurrentClinic($clinic_id = 0)
    {
        $clinic_type_manager = ModelManagerFactory::getByName('clinic_type');
        $total_types         = $clinic_type_manager->getList();
        $types_for_clinic    = $this->getListTypesForClinic($clinic_id);

        if (count($types_for_clinic) > 0)
        {
            $types_for_clinic_ids = array();
            foreach ($types_for_clinic AS $tfcValue) $types_for_clinic_ids[] = $tfcValue->id;

            foreach ($total_types AS &$ttValue)
            {
                if (in_array($ttValue->id, $types_for_clinic_ids))
                {
                    $ttValue->selected_for_clinic = 1;
                }
            }
        }

        return $total_types;
    }

    public function getListTypesForClinic($clinic_id)
    {
        $types = array();

        if ($clinic_id > 0)
        {
            $db = Register::get('db');

            $sql = 'SELECT *
                    FROM `' . $this->table_name . '`
                    WHERE `clinic_id` = ' . $clinic_id;

            $data = $db->query($sql);

            if (count($data) > 0)
            {
                $data = $this->initList($data);

                foreach ($data AS $dKey => $dValue)
                {
                    $types[] = $dValue->type;
                }
            }
        }

        return count($types) > 0 ? $types : array();
    }


}