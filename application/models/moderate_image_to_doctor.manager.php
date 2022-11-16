<?php
	class ModerateImageToDoctorManager extends ListModerateModelManager
	{
		protected $table_name = 'moderate_image_to_doctor';
		protected $model_name = 'ModerateImageToDoctorModel';

        protected $revision_conditions = array(
            'doctor_id'
        );

		protected $moderated_list_name = 'image_to_doctor';
		protected $moderated_entity_name = 'doctor';
		protected $fields = array('image_id',);
	}