<?php
	class DoctorCourseManager extends ModelManager
	{
		protected $table_name = 'doctor_course';
		protected $model_name = 'DoctorCourseModel';

    /**
		 * @return DoctorCourseModel[]
		 */
		public function getListByDoctorId($doctor_id)
		{
			$sql = 'SELECT *
                FROM doctor_course
                WHERE doctor_id = ' .(int)$doctor_id;

			$data = $this->db->query($sql);

			return (count($data)) ? $this->initList($data) : array();
		}

        public function getOneByDoctorId($doctor_id)
        {
            $sql = 'SELECT *
                FROM doctor_course
                WHERE doctor_id = ' .(int)$doctor_id;

            $data = $this->db->query($sql);

            return ($data) ? $this->initOne($data[0]) : null;
        }
	}
