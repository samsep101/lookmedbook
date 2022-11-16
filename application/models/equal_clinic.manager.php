<?php
    class EqualClinicManager extends ModelManager
    {
        protected $table_name = 'equal_clinic';
        protected $model_name = 'EqualClinicModel';

        /**
         * @param int $clinic_id
         * @return EqualClinicModel[]
         */
        public function getListByClinicId($clinic_id)
        {
            $sql = 'SELECT *
                    FROM equal_clinic
                    WHERE clinic_id = ' .(int)$clinic_id;

            $data = $this->db->query($sql);

            return $this->initList($data);
        }

        /**
         * @param int $clinic_id
         */
        public function deleteListByClinicId($clinic_id)
        {
            $sql = 'DELETE
                    FROM equal_clinic
                    WHERE clinic_id = ' .(int)$clinic_id;

            $this->db->query($sql);
        }

        public function deleteListOfNotActiveClinics()
        {
            $sql = 'DELETE ec.*
                    FROM equal_clinic ec
                    INNER JOIN clinic c ON c.id = ec.equal_clinic_id
                    WHERE c.is_active IS NULL
                    OR c.is_active != 1';

            $this->db->query($sql);
        }
    }
