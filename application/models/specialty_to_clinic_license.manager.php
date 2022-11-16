<?php
	class SpecialtyToClinicLicenseManager extends ModelManager
	{
		protected $table_name = 'specialty_to_clinic_license';
		protected $model_name = 'SpecialtyToClinicLicenseModel';

    	/**
		 * @return SpecialtyToClinicLicenseModel[]
		 */
		public function getListBySpecialtyId($specialty_id){
			$data = $this->orm_model->select()->where('specialty_id = ?', $specialty_id)->fetchAll();
			return $this->initList($data);
		}

	    /**
		 * @return SpecialtyToClinicLicenseModel[]
		 */
		public function getListByClinicLicenseId($clinic_license_id){
			$data = $this->orm_model->select()->where('clinic_license_id = ?', $clinic_license_id)->fetchAll();
			return $this->initList($data);
		}

	}
