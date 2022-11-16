<?php
	class DoctorAcademicDegreeToDoctorManager extends ModelManager
	{
		protected $table_name = 'doctor_academic_degree_to_doctor';
		protected $model_name = 'DoctorAcademicDegreeToDoctorModel';

        protected function beforeSave(DynamicModel $model)
		{
			$model->dt = date('Y-m-d H:i:s');
		}

        /**
		 * @return DoctorAcademicDegreeToDoctorModel[]
		 */
		public function getListByDoctorId($doctor_id)
		{
			$data = $this->orm_model->select()->where('doctor_id = ?', (int)$doctor_id)->fetchAll();
			return count($data) ? $this->initList($data) : array();
		}
	}
