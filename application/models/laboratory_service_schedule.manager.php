<?php
    class LaboratoryServiceScheduleManager extends ModelManager {
        protected $table_name = 'laboratory_service_schedule';
        protected $model_name = 'LaboratoryServiceScheduleModel';

                                        
        public function getListByLaboratoryId($laboratory_id){
            $data = $this->orm_model->select()->where('laboratory_id = ?', $laboratory_id)->fetchAll();
            return $this->initList($data);
        }
                                                        
        public function getListByServiceId($service_id){
            $data = $this->orm_model->select()->where('service_id = ?', $service_id)->fetchAll();
            return $this->initList($data);
        }

        public function getOneByLaboratoryIdAndServiceId($laboratory_id, $service_id)
        {
            $data = $this->orm_model->select()->where('laboratory_id = ? AND service_id = ?', $laboratory_id, $service_id)->fetchOne();
            return $this->initOne($data);
        }
                    
    }