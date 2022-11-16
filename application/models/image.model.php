<?php
	/**
	 * @property int $id
	 * @property string $folder
	 * @property string $filename
	 *
	 * @property array $image_info
	 * @property int $width
	 * @property int $height
	 * @property string $path
	 */
	class ImageModel extends DynamicModel
	{
		protected function _fieldPath()
		{
			return '/media/upload/' . $this->params['folder'] . $this->params['filename'];
		}


		protected function  _field_image_info()
		{
            if (empty($this->path)) {
                return 0;
            }
            return getimagesize(ABS_ROOT . '/' . $this->path);
		}

		public function resizeWithWatermark($width, $height)
		{
			return $this->resize($width, $height, true);
		}

		public function resize($width, $height, $add_watermark = false)
		{
			$resized_image_manager = new ResizedImageManager();
			$resized_image = $resized_image_manager->getOneByImageIdAndWidthAndHeightAndAction($this->getId(), $width, $height, 'resize', $add_watermark);

			if($resized_image)
			{
				return $resized_image;
			}

			$new_filename = $width . 'x' . $height . '-' . 'resize' . '-' . $this->filename;

			$image_resizer = new SimpleImage();

			if(!file_exists((ABS_ROOT . MEDIA_UPLOAD_PATH . $this->folder . $this->filename)))
			{
				return null;
			}
			$image_resizer->load(ABS_ROOT . MEDIA_UPLOAD_PATH . $this->folder . $this->filename);
			$image_resizer->resizeWithRatio($width, $height);


			$image_resizer->save(ABS_ROOT . MEDIA_UPLOAD_PATH . $this->folder . $new_filename, $this->image_info[2]);

			$resized_image = new ResizedImageModel();
			$resized_image->filename = $new_filename;
			$resized_image->folder = $this->folder;
			$resized_image->image_id = $this->getId();
			$resized_image->width = $width;
			$resized_image->height = $height;
			$resized_image->action = 'resize';
			$resized_image->is_with_watermark = (int)$add_watermark;

			$resized_image_manager->save($resized_image);

			return $resized_image;
		}

		public function cropWithWatermark($width, $height)
		{
			return $this->crop($width, $height, true);
		}

		public function crop($width, $height, $add_watermark = false)
		{
			$resized_image_manager = new ResizedImageManager();
			$resized_image = $resized_image_manager->getOneByImageIdAndWidthAndHeightAndAction($this->getId(), $width, $height, 'crop', $add_watermark);

			if($resized_image)
			{
				return $resized_image;
			}

			$new_filename = $width . 'x' . $height . '-' . 'crop' . '-' . $this->filename;

			if(!file_exists((ABS_ROOT . MEDIA_UPLOAD_PATH . $this->folder . $this->filename)))
			{
				return null;
			}

			$image_resizer = new SimpleImage();
			$image_resizer->load(ABS_ROOT . MEDIA_UPLOAD_PATH . $this->folder . $this->filename);
			$image_resizer->crop($width, $height);

			$image_resizer->save(ABS_ROOT . MEDIA_UPLOAD_PATH . $this->folder . $new_filename, $this->image_info[2]);

			$resized_image = new ResizedImageModel();
			$resized_image->filename = $new_filename;
			$resized_image->folder = $this->folder;
			$resized_image->image_id = $this->getId();
			$resized_image->width = $width;
			$resized_image->height = $height;
			$resized_image->action = 'crop';
			$resized_image->is_with_watermark = (int)$add_watermark;

			$resized_image_manager->save($resized_image);

			return $resized_image;
		}

		protected function _field_width()
		{
			return $this->image_info[0];
		}

		protected function _field_height()
		{
			return $this->image_info[1];
		}

		protected function _field_path()
		{
			$this->path = MEDIA_UPLOAD_PATH . $this->folder . $this->filename;
			return $this->path;
		}
	}