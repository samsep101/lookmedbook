<?php
    class ModerateSpecializationToClinicManager extends ListModerateModelManager
    {
        protected $table_name = 'moderate_specialization_to_clinic';
        protected $model_name = 'ModerateSpecializationToClinicModel';

        protected $moderated_list_name = 'specialization_to_clinic';
        protected $moderated_entity_name = 'clinic';
        protected $fields = array(
            'specialization_id',
        );

		protected $revision_conditions = array(
			'clinic_id'
		);

		/**
		 * @return ModerateSpecializationToClinicModel[]
		 */
		public function getListByClinicIdAndRevisionNumber($clinic_id, $revision_number)
        {
            $sql = 'SELECT 	s.id,
						s.name,
						'.(int)$clinic_id.' clinic_id,
						'.(int)$revision_number.' revision_number,
						IF(	(SELECT COUNT(*)
							FROM '.$this->table_name.' ms
							WHERE clinic_id = '.$clinic_id.'
								AND revision_number = '.$revision_number.'
								AND ms.specialization_id = s.id) = 1, 1, 0) is_selected
				FROM specialization s
				ORDER BY s.name';

            $data = $this->db->query($sql);
            $this->clearRegister();
            return $this->initList($data);
        }


		protected function afterSave()
		{

		}


        /**
		 * @return ModerateSpecializationToClinicModel[]
		 */
        /**
		 * @return ModerateSpecializationToClinicModel[]
		 */
		public function getListByClinicId($clinic_id){
            $data = $this->orm_model->select()->where('clinic_id = ?', $clinic_id)->fetchAll();
            return $this->initList($data);
        }

        /**
		 * @return ModerateSpecializationToClinicModel[]
		 */
        /**
		 * @return ModerateSpecializationToClinicModel[]
		 */
		public function getListBySpecializationId($specialization_id){
            $data = $this->orm_model->select()->where('specialization_id = ?', $specialization_id)->fetchAll();
            return $this->initList($data);
        }

    }
