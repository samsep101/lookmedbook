<?php
	class ModerateDoctorCardImageManager extends EntityModerateModelManager
	{
		protected $table_name = 'moderate_doctor_card_image';
		protected $model_name = 'ModerateDoctorCardImageModel';

		protected $moderated_entity_name = 'doctor';
		protected $fields = array('card_image_id',);


	}