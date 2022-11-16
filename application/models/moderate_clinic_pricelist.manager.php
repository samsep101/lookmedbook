<?php
	class ModerateClinicPricelistManager extends ListModerateModelManager
	{
		protected $table_name = 'moderate_clinic_pricelist';
		protected $model_name = 'ModerateClinicPricelistModel';

        protected $revision_conditions = array(
            'clinic_id'
        );

		protected $moderated_list_name = 'clinic_pricelist';
		protected $moderated_entity_name = 'clinic';
		protected $fields = array('filename');

        /**
		 * @return ModerateClinicPricelistModel[]
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
