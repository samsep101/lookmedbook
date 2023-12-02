<?php
	class ModerateClinicRequisitesManager extends EntityModerateModelManager
	{
		protected $table_name = 'moderate_clinic_requisites';
		protected $model_name = 'ModerateClinicRequisitesModel';

		protected $moderated_entity_name = 'clinic';
		protected $fields = array('name_of_bank', 'bank_bik', 'bank_inn', 'bank_kpp', 'current_account', 'correspondent_account', 'ogrn', 'legal_address', 'fact_address');

        /**
		 * @return ModerateClinicRequisitesModel[]
		 */
		public function getListByClinicId($clinic_id)
		{
			$data = $this->orm_model->select()->where('clinic_id = ?', $clinic_id)->fetchAll();
			return $this->initList($data);
		}

        /**
		 * @return ModerateClinicRequisitesModel[]
		 */
		public function getListByModerateStatusId($moderate_status_id)
		{
			$data = $this->orm_model->select()->where('moderate_status_id = ?', $moderate_status_id)->fetchAll();
			return $this->initList($data);
		}
	}
