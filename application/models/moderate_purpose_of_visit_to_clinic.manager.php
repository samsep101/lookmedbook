<?php
	class ModeratePurposeOfVisitToClinicManager extends ListModerateModelManager
	{

		protected $table_name = 'moderate_purpose_of_visit_to_clinic';
		protected $model_name = 'ModeratePurposeOfVisitToClinicModel';

        protected $revision_conditions = array(
            'clinic_id',
        );

		protected $moderated_list_name = 'purpose_of_visit_to_clinic';
		protected $moderated_entity_name = 'clinic';
		protected $fields = array(
            'specialty_id',
            'purpose_of_visit_id',
            'visit_price'
        );

        /**
		 * @return ModeratePurposeOfVisitToClinicModel[]
		 */
		public function getListByClinicIdAndSpecialtyId($clinic_id, $specialty_id)
		{
            $revision_condition = array(
                'clinic_id' => $clinic_id
            );

			$this->getCurrentRevision($revision_condition);

			$revision_number = $this->getLastRevisionNumberByRevisionCondition($revision_condition);

			$sql = 'SELECT 	p.id, p.name,
							' . (int)$clinic_id . ' clinic_id,
							' . (int)$specialty_id . ' specialty_id,
							' . (int)$revision_number . ' revision_number,
							p2s.is_main,
							IF(	(SELECT COUNT(*)
							FROM ' . $this->table_name . ' ms
							WHERE clinic_id = ' . (int)$clinic_id . '
								AND revision_number = ' . (int)$revision_number . '
								AND ms.purpose_of_visit_id = p.id
								AND ms.specialty_id = ' . (int)$specialty_id . ') = 1, 1, 0) is_selected
					FROM purpose_of_visit p
					INNER JOIN purpose_of_visit_to_specialty p2s ON p2s.purpose_of_visit_id = p.id
					WHERE p2s.specialty_id = ' . (int)$specialty_id . '
					ORDER BY p2s.is_main DESC, p2s.sort DESC, name';
			$data = $this->db->query($sql);

			$this->clearRegister();
			return $this->initList($data);
		}

		public function getVisitPriceByPurposeOfVisitIdAndClinicIdAndSpecialtyId($purpose_of_visit_id, $clinic_id, $specialty_id)
		{
            $revision_condition = array(
                'clinic_id' => $clinic_id,
            );

			$this->getCurrentRevision($revision_condition);
			$revision_number = $this->getLastRevisionNumberByRevisionCondition($revision_condition);

			$sql = 'SELECT mp.visit_price
					FROM ' . $this->table_name . ' mp
					WHERE mp.purpose_of_visit_id = ' . (int)$purpose_of_visit_id . '
						AND mp.clinic_id = ' . (int)$clinic_id . '
						AND specialty_id = ' . (int)$specialty_id . '
						AND revision_number = ' . (int)$revision_number . ';';
			$data = $this->db->query($sql);

			$this->clearRegister();
			return (isset($data[0])) ? $data[0]['visit_price'] : null;
		}

        /**
		 * @return ModeratePurposeOfVisitToClinicModel
		 */
		public function getOneByPurposeOfVisitIdAndClinicIdAndSpecialtyId($purpose_of_visit_id, $clinic_id, $specialty_id)
		{
            $revision_condition = array(
                'clinic_id' => $clinic_id
            );
			$this->getCurrentRevision($revision_condition);
			$revision_number = $this->getLastRevisionNumberByRevisionCondition($revision_condition);

			$sql = 'SELECT *
					FROM ' . $this->table_name . '
					WHERE purpose_of_visit_id = ' . (int)$purpose_of_visit_id . '
					AND clinic_id = ' . (int)$clinic_id . '
					AND specialty_id = ' . (int)$specialty_id . '
					AND revision_number = ' . (int)$revision_number . ';';
			$data = $this->db->query($sql);
			$this->clearRegister();
			return (isset($data[0])) ? $this->initOne($data[0]) : null;
		}

	}
