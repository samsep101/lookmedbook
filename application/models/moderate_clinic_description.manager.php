<?php
	class ModerateClinicDescriptionManager extends EntityModerateModelManager
	{
		protected $table_name = 'moderate_clinic_description';
		protected $model_name = 'ModerateClinicDescriptionModel';

		protected $moderated_entity_name = 'clinic';
		protected $fields = array('about');

	}