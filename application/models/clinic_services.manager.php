<?php

class ClinicServicesManager extends ModelManager
{
    protected $table_name = 'clinic_services';
    protected $model_name = 'ClinicServicesModel';

    public function getItemByAlias($alias)
    {
        $service = array();

        if (is_string($alias))
        {
            $db = Register::get('db');

            $sql = 'SELECT *
                    FROM `' . $this->table_name . '`
                    WHERE `alias` = "' . $alias . '"
                    LIMIT 1';

            $data = $db->query($sql);

            if (!empty($data[0])) $service = $this->initOne($data[0]);
        }

        return $service ? $service : array();
    }
}