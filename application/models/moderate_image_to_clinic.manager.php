<?php
	class ModerateImageToClinicManager extends ListModerateModelManager
	{
		protected $table_name = 'moderate_image_to_clinic';
		protected $model_name = 'ModerateImageToClinicModel';

        protected $revision_conditions = array(
            'clinic_id'
        );

		protected $moderated_list_name = 'image_to_clinic';
		protected $moderated_entity_name = 'clinic';
		protected $fields = array('image_id',);
	}