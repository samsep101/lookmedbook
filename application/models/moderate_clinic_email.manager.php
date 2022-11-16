<?php
	class ModerateClinicEmailManager extends ListModerateModelManager
	{
		protected $table_name = 'moderate_clinic_email';
		protected $model_name = 'ModerateClinicEmailModel';

        protected $revision_conditions = array(
            'clinic_id'
        );

		public function __construct()
		{
			$this->moderated_entity_name = 'clinic';
			$this->moderated_list_name = 'clinic_email';

			$this->fields = array('email', 'is_use_to_distribution');

			parent::__construct();
		}
	}