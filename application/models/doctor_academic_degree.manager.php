<?php
	class DoctorAcademicDegreeManager extends ModelManager
	{
		protected $table_name = 'doctor_academic_degree';
		protected $model_name = 'DoctorAcademicDegreeModel';

        /**
		 * @return DoctorAcademicDegreeModel[]
		 */
		public function getListByDoctorId($doctor_id){
			$data = $this->orm_model->select()->where('doctor_id = ?', $doctor_id)->fetchAll();
			return $this->initList($data);
		}

	}
