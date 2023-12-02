<?php
	class ModerateDoctorCertificateManager extends ListModerateModelManager
	{
		protected $table_name = 'moderate_doctor_certificate';
		protected $model_name = 'ModerateDoctorCertificateModel';

		protected $moderated_list_name = 'doctor_certificate';
		protected $moderated_entity_name = 'doctor';
		protected $fields = array('date', 'specialty_id', 'university_id', 'duration');

	}