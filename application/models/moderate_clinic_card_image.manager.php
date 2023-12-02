<?php
	class ModerateClinicCardImageManager extends EntityModerateModelManager
	{
		protected $table_name = 'moderate_clinic_card_image';
		protected $model_name = 'ModerateClinicCardImageModel';

		protected $moderated_entity_name = 'clinic';

		protected $fields = array('card_image_id');

	}