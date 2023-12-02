<?php
    class ClinicCheckDateManager extends ModelManager {
        protected $table_name = 'clinic_check_date';
        protected $model_name = 'ClinicCheckDateModel';

        /**
         * @return string[]
         */
        public function getCheckedDaysByUserIdAndDatePeriod($user_id, $date_from, $date_to)
        {
            $sql = 'SELECT *
                    FROM '.$this->table_name.'
                    WHERE date BETWEEN "'.$this->db->escape($date_from).'"
                        AND "'.$this->db->escape($date_to).'"
                        AND user_id = '.(int)$user_id;

            $data = $this->db->query($sql);

            $result = array();

            if ($data)
            {
                foreach($data as $row)
                {
                    $result[] = $row['date'];
                }
            }

            return $result;
        }

        public function getOneByDateAndUserId($date, $user_id)
        {
            $data = $this->orm_model->select()->where('date = ? AND user_id = ?', $date, $user_id)->fetchOne();
            return $this->initOne($data);
        }

        public function deleteByDateAndUserId($date, $user_id)
        {
            $sql = 'DELETE FROM '.$this->table_name.'
                    WHERE date="'.$this->db->escape($date).'"
                        AND user_id = '.(int)$user_id;

            $this->db->query($sql);
        }
    }
