<?php

	/**
	 * @property int $id
	 * @property int $revision_number
	 * @property int $moderate_status_id
	 * @property ModerateStatusModel $moderate_status
	 * @property int $card_image_id
	 * @property ImageModel $card_image
	 * @property int $doctor_id
	 * @property DoctorModel $doctor
	 * @property string $dt
	 */
    class ModerateDoctorCardImageModel extends ModerateModel {
        protected $fields = array(
            'card_image_id'
        );


		public function _field_card_image()
		{
			$image_manager = new ImageManager();

			return $image_manager->getOneById($this->card_image_id);
		}
	}