<?php
	class ModerateClinicLicenseImageManager extends ListModerateModelManager
	{
		protected $table_name = 'moderate_clinic_license_image';
		protected $model_name = 'ModerateClinicLicenseImageModel';

        protected $revision_conditions = array(
            'clinic_id'
        );

		protected $moderated_list_name = 'clinic_license_image';
		protected $moderated_entity_name = 'clinic';
		protected $fields = array('image_id');

        /**
		 * @return ModerateClinicLicenseImageModel[]
		 */
		public function getListByClinicId($clinic_id)
		{
			$this->getCurrentRevision($clinic_id);
			$revision_number = $this->getLastRevisionNumberByRevisionCondition($clinic_id);
			$sql = 'SELECT *
					FROM ' . $this->table_name . '
					WHERE revision_number = ' . (int)$revision_number . '
					    AND clinic_id = ' . (int)$clinic_id;
			$data = $this->db->query($sql);

			$this->clearRegister();
			return $this->initList($data);
		}
	}
