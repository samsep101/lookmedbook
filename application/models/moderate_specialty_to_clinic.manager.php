<?php
	class ModerateSpecialtyToClinicManager extends ListModerateModelManager
	{
		protected $table_name = 'moderate_specialty_to_clinic';
		protected $model_name = 'ModerateSpecialtyToClinicModel';

        protected $revision_conditions = array(
            'clinic_id'
        );

		protected $moderated_list_name = 'specialty_to_clinic';
		protected $moderated_entity_name = 'clinic';
		protected $fields = array('specialty_id',);

		public function getSpecializationsListByClinicIdAndRevisionNumber($clinic_id, $revision_number)
		{
			$sql = 'SELECT 	s.id,
						s.name,
						' . (int)$clinic_id . ' clinic_id,
						' . (int)$revision_number . ' revision_number,
						IF(	(SELECT COUNT(*)
							FROM ' . $this->table_name . ' ms
							WHERE clinic_id = ' . $clinic_id . '
								AND revision_number = ' . $revision_number . '
								AND ms.specialty_id = s.id) = 1, 1, 0) is_selected
				FROM specialty s
				WHERE s.parent_id IS NULL
				ORDER BY s.name';


			$data = $this->db->query($sql);
			$this->clearRegister();
			return $this->initList($data);
		}

	/**
		 * @return ModerateSpecialtyToClinicModel[]
		 */
		public function getListByClinicIdAndSpecializationIdAndRevisionNumber($clinic_id, $specialization_id, $revision_number)
		{
			$sql = 'SELECT 	s.id,
						s.name,
						' . (int)$clinic_id . ' clinic_id,
						' . (int)$revision_number . ' revision_number,
						IF(	(SELECT COUNT(*)
							FROM ' . $this->table_name . ' ms
							WHERE clinic_id = ' . (int)$clinic_id . '
								AND revision_number = ' . (int)$revision_number . '
								AND ms.specialty_id = s.id) > 0, 1, 0) is_selected
				FROM specialty s
				WHERE EXISTS (  SELECT *
				                FROM specialty_to_specialization
				                WHERE specialty_id = s.id
				                    AND specialization_id = ' . (int)$specialization_id . ')
				ORDER BY s.name';
			$data = $this->db->query($sql);
			$this->clearRegister();
			return $this->initList($data);
		}

		public function getSpecialtyListByClinicId($clinic_id)
		{
			$revision_condition = array(
				'clinic_id' => $clinic_id
			);

			$this->getCurrentRevision($revision_condition);


			$revision_number = $this->getLastRevisionNumberByRevisionCondition($revision_condition);

			$sql = '
				SELECT * FROM(
					SELECT 	s.id,
							s.name,
							' . (int)$clinic_id . ' clinic_id,
							' . (int)$revision_number . ' revision_number,
							0 is_selected
					FROM specialty s
					INNER JOIN moderate_specialty_to_clinic s2c ON s2c.specialty_id = s.id
					WHERE  s2c.revision_number = ' . $this->getLastRevisionNumberByRevisionCondition($revision_condition) . '
						AND s2c.clinic_id = ' . (int)$clinic_id . '
				) a
				ORDER BY name';

			$data = $this->db->query($sql);
			$this->clearRegister();
			return $this->initList($data);
		}

        // Выборка специализаций для только детских и только взрослых врачей
        public function getSpecialtyListByClinicIdAndDoctorType($clinic_id, $doctorType)
        {
            $revision_condition = array(
                'clinic_id' => $clinic_id
            );

            $this->getCurrentRevision($revision_condition);


            $revision_number = $this->getLastRevisionNumberByRevisionCondition($revision_condition);

            $sql = '
				SELECT * FROM(
					SELECT 	s.id,
							s.name,
							' . (int)$clinic_id . ' clinic_id,
							' . (int)$revision_number . ' revision_number,
							0 is_selected
					FROM specialty s
					INNER JOIN moderate_specialty_to_clinic s2c ON s2c.specialty_id = s.id
					WHERE  s2c.revision_number = ' . $this->getLastRevisionNumberByRevisionCondition($revision_condition) . '
						AND s2c.clinic_id = ' . (int)$clinic_id .
                        ' AND s.for_whom != ' .(int)$doctorType .'
				) a
				ORDER BY name';

            $data = $this->db->query($sql);
            $this->clearRegister();
            return $this->initList($data);
        }
	}
