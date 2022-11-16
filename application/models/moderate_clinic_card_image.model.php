<?php
	/**
	 * @property int $id
	 * @property int $clinic_id
	 * @property ClinicModel $clinic
	 * @property int $moderate_status_id
	 * @property ModerateStatusModel $moderate_status
	 * @property int $revision_number
	 * @property int $card_image_id
	 * @property ImageModel $card_image
	 * @property string $dt
	 */
    class ModerateClinicCardImageModel extends ModerateModel
	{

		public function _field_card_image()
		{
			$image_manager = new ImageManager();
			return $image_manager->getOneById($this->card_image_id);
		}
	}