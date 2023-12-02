<?php
	class ModerateSpecialtyToDoctorManager extends ListModerateModelManager
	{
		protected $table_name = 'moderate_specialty_to_doctor';
		protected $model_name = 'ModerateSpecialtyToDoctorModel';

		protected $moderated_list_name = 'doctor_specialty_to_clinic';
		protected $moderated_entity_name = 'doctor';
        protected $revision_conditions = array(
            'doctor_id',
            'clinic_id'
        );

        protected $fields = array('specialty_id', 'clinic_id');


		public function getSpecialtiesListByDoctorIdAndClinicId($doctor_id, $clinic_id)
		{
			$specialties = array();

			$revision_condition = array('doctor_id' => $doctor_id, 'clinic_id' => $clinic_id);
			$revision = $this->getCurrentRevision($revision_condition);


			if($revision->elements)
			{
				foreach($revision->elements as $element)
				{
					$specialties[] = $element->specialty;
				}
			}

			return $specialties;
		}

		public function deleteByClinicIdAndDoctorIdAndSpecialtyId($clinic_id, $doctor_id, $specialty_id)
		{
			$sql = 'DELETE FROM ' . $this->table_name . '
					WHERE specialty_id = ' . (int)$specialty_id . '
					AND doctor_id = ' . (int)$doctor_id . '
					AND clinic_id = ' . (int)$clinic_id;

			$this->db->query($sql);
		}
	}