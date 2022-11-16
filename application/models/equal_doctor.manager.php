<?php
    class EqualDoctorManager extends ModelManager
    {
        protected $table_name = 'equal_doctor';
        protected $model_name = 'EqualDoctorModel';

        /**
         * @param int $doctor_id
         * @return EqualDoctorModel[]
         */
        public function getListByDoctorId($doctor_id)
        {
            $sql = 'SELECT *
                    FROM equal_doctor
                    WHERE doctor_id = ' .(int)$doctor_id;

            $data = $this->db->query($sql);

            return $this->initList($data);
        }

        /**
         * @param int $doctor_id
         */
        public function deleteListByDoctorId($doctor_id)
        {
            $sql = 'DELETE
                    FROM equal_doctor
                    WHERE doctor_id = ' .(int)$doctor_id;

            $this->db->query($sql);
        }

        /**
         * Удаляет ссылки на неактивных врачей.
         */
        public function deleteInactiveDoctors()
        {
            $this->db->query("
                DELETE ed.*
                FROM equal_doctor ed
                  LEFT JOIN doctor d
                    ON ed.equal_doctor_id = d.id
                  LEFT JOIN doctor_specialty_to_clinic ds2c
                    ON d.id = ds2c.doctor_id
                  LEFT JOIN clinic c
                    ON ds2c.clinic_id = c.id
                WHERE d.is_active IS NULL OR d.is_active = 0 
                      OR (d.full_lower_name = '' 
                         AND (d.is_virtual IS NULL OR d.is_virtual = 0))  
                      OR c.is_active IS NULL OR c.is_active = 0
            ");
        }
    }
