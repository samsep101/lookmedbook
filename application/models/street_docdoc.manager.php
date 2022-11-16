<?php

class StreetDocdocManager extends ModelManager
{
    protected $table_name = 'street_docdoc';
    protected $model_name = 'StreetDocdocModel';

    public function deleteByDocdocId($docdocId)
    {
        $this->orm_model->delete('docdoc_id = ' . (int)$docdocId);
    }
}
