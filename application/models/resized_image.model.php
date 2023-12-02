<?php
	/**
	 * @property int $id
	 * @property int $image_id
	 * @property ImageModel $image
	 * @property int $width
	 * @property int $height
	 * @property string $action
	 * @property string $folder
	 * @property string $filename
	 * @property boolean $is_with_watermark
	 *
	 * @property string $path
	 */
	class ResizedImageModel extends ImageModel
	{
		protected function _field_path()
		{
			$this->path = MEDIA_UPLOAD_PATH . $this->folder . $this->filename;
			return $this->path;
		}
	}