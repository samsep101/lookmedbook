<?php

class ClinicTypeManager extends ModelManager
{
    protected $table_name = 'clinic_type';
    protected $model_name = 'ClinicTypeModel';

    public function getItemByAlias($alias)
    {
        $type = array();

        if (is_string($alias))
        {
            $db = Register::get('db');

            $sql = 'SELECT *
                    FROM `' . $this->table_name . '`
                    WHERE `alias` = "' . $alias . '"
                    LIMIT 1';

            $data = $db->query($sql);

            if (!empty($data[0])) $type = $this->initOne($data[0]);
        }

        return $type ? $type : array();
    }
}