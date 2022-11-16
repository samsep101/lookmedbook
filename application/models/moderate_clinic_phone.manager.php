<?php
	class ModerateClinicPhoneManager extends ListModerateModelManager
	{
		protected $table_name = 'moderate_clinic_phone';
		protected $model_name = 'ModerateClinicPhoneModel';

        protected $revision_conditions = array(
            'clinic_id'
        );

        public function __construct()
		{
			$this->moderated_list_name = 'clinic_phone';
			$this->moderated_entity_name = 'clinic';

			$this->fields = array('phone_number', 'is_use_to_distribution');

			parent::__construct();
		}

	}