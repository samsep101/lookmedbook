<?php
	class ModerateClinicUserManager extends EntityModerateModelManager
	{
		protected $table_name = 'moderate_clinic_user';
		protected $model_name = 'ModerateClinicUserModel';

		protected $moderated_entity_name = 'user';

		protected $fields = array(
            'fio',
            'phone',
            'email'
        );
	}