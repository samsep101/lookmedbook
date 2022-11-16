<?php
    class DoctorInfoManager extends ModelManager
    {
        protected $table_name = 'doctor_info';
        protected $model_name = 'DoctorInfoModel';

        /**
         * @return DoctorInfoModel
         */
        public function getOneByDoctorId($doctor_id)
        {
            $sql = 'SELECT *
                    FROM doctor_info
                    WHERE doctor_id = ' .(int)$doctor_id;

            $data = $this->db->query($sql);

            return ($data) ? $this->initOne($data[0]) : null;
        }
    }
