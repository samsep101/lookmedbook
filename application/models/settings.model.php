<?php
	/**
	 * @property int $id
	 * @property string $code
	 * @property string $name
	 * @property string $value
	 * @property string $group
	 * @property string $type
	 *
	 * @property  $about_big_image
	 */
	class SettingsModel extends DynamicModel
	{
		protected function _field_about_big_image()
		{
			$image_manager = new ImageManager();
			$this->about_big_image = $image_manager->getOneById($this->about_big_image->value);
			return $this->about_big_image;
		}
	}