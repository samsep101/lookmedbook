<?php
	class ModerateDoctorUniversityManager extends EntityModerateModelManager
	{
		protected $table_name = 'moderate_doctor_university';
		protected $model_name = 'ModerateDoctorUniversityModel';

		protected $moderated_entity_name = 'doctor';
		protected $fields = array('high_education_end_year', 'high_education_specialty_id', 'high_education_university_id', 'secondary_education_end_year', 'secondary_education_specialty_id', 'secondary_education_university_id',);
	}