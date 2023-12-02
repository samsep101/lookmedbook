<?php
    class ModerateFeatureToClinicManager extends ListModerateModelManager {

        protected $table_name = 'moderate_feature_to_clinic';
        protected $model_name = 'ModerateFeatureToClinicModel';

        protected $moderated_list_name = 'feature_to_clinic';
        protected $moderated_entity_name = 'clinic';
        protected $fields = array(
            'feature_id',
        );

		protected $revision_conditions = array(
			'clinic_id'
		);


        /**
		 * @return ModerateFeatureToClinicModel[]
		 */
		public function getListByClinicId($clinic_id)
		{
			$revision_condition = array(
				'clinic_id' => $clinic_id,
			);

			$this->getCurrentRevision($revision_condition);

			$revision_number = $this->getLastRevisionNumberByRevisionCondition($revision_condition);
			$sql = 'SELECT 	f.id,
							f.name,
							' . (int)$clinic_id . ' clinic_id,
							' . (int)$revision_number . ' revision_number,
							IF(	(SELECT COUNT(*)
							FROM ' . $this->table_name . ' ms
							WHERE clinic_id = ' . (int)$clinic_id . '
								AND revision_number = ' . (int)$revision_number . '
								AND ms.feature_id = f.id) = 1, 1, 0) is_selected
					FROM feature f
					ORDER BY name';
			$data = $this->db->query($sql);

			$this->clearRegister();
			return $this->initList($data);
		}
    }
