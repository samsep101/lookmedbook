<?php
	class ModerateClinicLicenseManager extends EntityModerateModelManager
	{
		protected $table_name = 'moderate_clinic_license';
		protected $model_name = 'ModerateClinicLicenseModel';

		protected $moderated_entity_name = 'clinic';
		protected $fields = array('license_number', 'license_issue_date', 'license_validity_date',);

		protected $revision_conditions = array(

		);

		public function beforeSave(DynamicModel $model)
		{
			$model->license_issue_date = $model->license_issue_date ? date('Y-m-d', strtotime($model->license_issue_date)) : '';
			$model->license_validity_date = $model->license_validity_date ? date('Y-m-d', strtotime($model->license_validity_date)) : '';
		}
	}