<?php

class DocdocServiceCategoryManager extends ModelManager
{
    protected $table_name = 'docdoc_service_category';
    protected $model_name = 'DocdocServiceCategoryModel';


    /**
     * @return ControllerModel
     */
    public function getOneByDocDocId($docDocId)
    {
        $sql = 'SELECT *
                    FROM docdoc_service_category
                    WHERE `docdoc_id` = ?';

        $data = $this->db->query($sql, [$docDocId]);
        return (isset($data[0])) ? $this->initOne($data[0]) : null;
    }
}
