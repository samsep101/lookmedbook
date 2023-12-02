<?php
    class LaboratoryServiceManager extends ModelManager {
        protected $table_name = 'laboratory_service';
        protected $model_name = 'LaboratoryServiceModel';

        public function getOneByName($name)
        {
            $data = $this->orm_model->select()->where('name = ?', $name)->fetchOne();
            return $this->initOne($data);
        }
    }