<?php
	class DoctorAcademicTitleManager extends ModelManager
	{
		protected $table_name = 'doctor_academic_title';
		protected $model_name = 'DoctorAcademicTitleModel';

        /**
		 * @return DoctorAcademicTitleModel[]
		 */
		public function getListByDoctorId($doctor_id){
			$data = $this->orm_model->select()->where('doctor_id = ?', $doctor_id)->fetchAll();
			return $this->initList($data);
		}

        /**
         * @return DoctorAcademicTitleModel
         */
        public function getOneByDoctorId($doctor_id)
        {
            $sql = 'SELECT *
                    FROM doctor_academic_title
                    WHERE doctor_id = ' .(int)$doctor_id;

            $data = $this->db->query($sql);

            return ($data) ? $this->initOne($data[0]) : null;
        }
	}
