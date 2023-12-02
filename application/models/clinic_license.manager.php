<?php
	class ClinicLicenseManager extends ModelManager
	{
		protected $table_name = 'clinic_license';
		protected $model_name = 'ClinicLicenseModel';

        /**
		 * @return ClinicLicenseModel[]
		 */
		public function getListByClinicId($clinic_id)
		{
			$data = $this->orm_model->select()->where('clinic_id = ?', $clinic_id)->fetchAll();
			return (isset($data)) ? $this->initList($data) : array();
		}
	}
