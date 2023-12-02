<?php
	class DoctorEducationManager extends ModelManager
	{
		protected $table_name = 'doctor_education';
		protected $model_name = 'DoctorEducationModel';

        /**
		 * @return DoctorEducationModel
		 */
		public function getOneByDoctorIdAndUniversityId($doctor_id, $university_id)
		{
			$sql = 'SELECT ' . $this->selected_fields . '
                    FROM `' . DB_PREFIX . $this->table_name . '`
                    WHERE `doctor_id` = ' . (int)$doctor_id . '
                    AND `university_id` = ' . (int)$university_id;

			$data = $this->db->query($sql);

			return (isset($data[0])) ? $this->initOne($data[0]) : null;
		}

		public function getListByDoctorId($doctor_id)
		{
			$sql = 'SELECT *
                    FROM ' . $this->table_name . '
                    WHERE `doctor_id` = ' . (int)$doctor_id . '
                    ORDER BY end_year ASC;';

			$data = $this->db->query($sql);

			return (isset($data)) ? $this->initList($data) : array();
		}

		public function deleteByDoctorId($doctor_id)
		{
			$sql = 'DELETE FROM ' . $this->table_name . '
                    WHERE  doctor_id=' . (int)$doctor_id;

			$this->db->query($sql);
		}

        public function getOneByDoctorId($doctor_id)
        {
            $sql = 'SELECT *
                    FROM ' . $this->table_name . '
                    WHERE `doctor_id` = ' . (int)$doctor_id;

            $data = $this->db->query($sql);

            return ($data) ? $this->initOne($data[0]) : null;
        }
	}
