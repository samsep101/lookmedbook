<?php
	class ModeratePurposeOfVisitToDoctorManager extends ListModerateModelManager
	{

		protected $table_name = 'moderate_purpose_of_visit_to_doctor';
		protected $model_name = 'ModeratePurposeOfVisitToDoctorModel';

		protected $moderated_list_name = 'purpose_of_visit_to_doctor';
		protected $moderated_entity_name = 'doctor';
		protected $fields = array('specialty_id', 'purpose_of_visit_id', 'visit_price');

		protected $revision_conditions = array('doctor_id', 'clinic_id');


		/**
		 * @return ModeratePurposeOfVisitToDoctorModel[]
		 */
		public function getListByDoctorIdAndClinicIdAndSpecialtyId($doctor_id, $clinic_id, $specialty_id){
			$revision_condition = array(
				'doctor_id' => $doctor_id,
				'clinic_id' => $clinic_id,
			);
			$this->getCurrentRevision($revision_condition);
			$revision_number = $this->getLastRevisionNumberByRevisionCondition($revision_condition);

			$specialty_to_doctor_manager = new ModerateSpecialtyToDoctorManager();

			$revision_condition = array('doctor_id' => $doctor_id, 'clinic_id' => $clinic_id);
			$revision = $specialty_to_doctor_manager->getCurrentRevision($revision_condition);

			$purpose_of_visit_to_clinic = new ModeratePurposeOfVisitToClinicManager();

			$clinic_revision_condition = array(
				'clinic_id' => $clinic_id
			);
			$purpose_of_visit_to_clinic->getCurrentRevision($clinic_revision_condition);

			$clinic_purposes_revision_number = $purpose_of_visit_to_clinic->getLastRevisionNumberByRevisionCondition($revision_condition);

			$sql = 'SELECT * FROM
				(
					SELECT 	p.id,
							p.name,
							mpv2d.visit_price,
							mpv2d.specialty_id,
							mpv2d.clinic_id,
							1 is_selected,
							pv2s.is_main,
							pv2s.sort
					FROM moderate_purpose_of_visit_to_doctor mpv2d
					INNER JOIN purpose_of_visit p ON p.id = mpv2d.purpose_of_visit_id
					INNER JOIN purpose_of_visit_to_specialty pv2s ON p.id = pv2s.purpose_of_visit_id
					WHERE pv2s.specialty_id = ' . (int)$specialty_id . '
						AND pv2s.specialty_id = mpv2d.specialty_id
						AND mpv2d.revision_number = ' . (int)$revision_number . '
						AND mpv2d.doctor_id = ' . (int)$doctor_id . '
						AND mpv2d.clinic_id = ' . (int)$clinic_id . '
						AND pv2s.is_main != 1

					UNION

					SELECT 	p.id,
							p.name,
							(SELECT visit_price
							 FROM moderate_purpose_of_visit_to_doctor
							 WHERE doctor_id = ' . (int)$doctor_id . '
							 	AND clinic_id = ' . (int)$clinic_id . '
							 	AND specialty_id = ' . (int)$specialty_id . '
							 	AND revision_number = ' . (int)$revision_number . '
							 	AND purpose_of_visit_id = p.id
							 LIMIT 1
							 )
							 visit_price,
							' . $specialty_id . ' specialty_id,
							' . $clinic_id . ' clinic_id,
							1 is_selected,
							pv2s.is_main,
							pv2s.sort
					FROM purpose_of_visit p
					INNER JOIN purpose_of_visit_to_specialty pv2s ON p.id = pv2s.purpose_of_visit_id
					WHERE pv2s.specialty_id = ' . (int)$specialty_id . '
						AND pv2s.is_main = 1

					UNION

					SELECT 	p.id,
							p.name,
							NULL visit_price,
							mpv2c.specialty_id,
							mpv2c.clinic_id,
							0 is_selected,
							pv2s.is_main,
							pv2s.sort
					FROM moderate_purpose_of_visit_to_clinic mpv2c
					INNER JOIN purpose_of_visit p ON p.id = mpv2c.purpose_of_visit_id
					INNER JOIN purpose_of_visit_to_specialty pv2s ON p.id = pv2s.purpose_of_visit_id
					WHERE mpv2c.clinic_id = ' . (int)$clinic_id . '
						AND pv2s.specialty_id = mpv2c.specialty_id
						AND mpv2c.revision_number = ' . (int)$clinic_purposes_revision_number . '
						AND mpv2c.specialty_id = ' . (int)$specialty_id . '
						AND  (SELECT COUNT(*)
								FROM moderate_purpose_of_visit_to_doctor mpv2d
								WHERE mpv2d.specialty_id = ' . (int)$specialty_id . '
									AND mpv2d.revision_number = ' . (int)$revision_number . '
									AND mpv2d.doctor_id = ' . (int)$doctor_id . '
									AND mpv2d.clinic_id = ' . (int)$clinic_id . '
									AND mpv2d.purpose_of_visit_id = p.id) = 0
						AND (pv2s.is_main != 1)

				) a ORDER BY 	a.is_main DESC,
								a.sort ASC,
								name ASC';

			$data = $this->db->query($sql);

			$this->clearRegister();

			return $this->initList($data);
		}

        /**
         * @return ModeratePurposeOfVisitToDoctorModel[]
         */
        public function getListByDoctorIdAndClinicId($doctor_id, $clinic_id)
        {
            $sql = 'SELECT *
                    FROM '.$this->table_name.'
                    WHERE doctor_id = '.(int)$doctor_id.'
                    AND clinic_id = '.(int)$clinic_id;
            $data = $this->db->query($sql);

            return (count($data)) ? $this->initList($data) : array();
        }
	}
