<?php
	class ModerateDoctorEducationManager extends ListModerateModelManager
	{
		protected $table_name = 'moderate_doctor_education';
		protected $model_name = 'ModerateDoctorEducationModel';

		protected $moderated_list_name = 'doctor_education';
		protected $moderated_entity_name = 'doctor';
		protected $fields = array('doctor_education_type_id', 'specialty_id', 'university_id', 'end_year');

	}