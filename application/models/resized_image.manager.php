<?php
	class ResizedImageManager extends ModelManager
	{
		protected $table_name = 'resized_image';
		protected $model_name = 'ResizedImageModel';

		public function beforeSave(DynamicModel $model)
		{
			/**
			 * @var ResizedImageModel $model
			 */
			if($model->isNew() && $model->is_with_watermark)
			{
				ImageWatermarkHelper::setWatermark(ABS_ROOT . $model->path);
			}
		}

		public function getOneByImageIdAndWidthAndHeightAndAction($image_id, $width, $height, $action = 'crop', $watermark = false)
		{
			$sql = 'SELECT *
                    FROM resized_image
                    WHERE image_id = ' . (int)$image_id . '
                        AND width = ' . (int)$width . '
                        AND height = ' . (int)$height . '
                        AND action = "' . $action . '"';

			if($watermark)
			{
				$sql .= ' AND is_with_watermark = 1';
			}


			$db = Register::get('db');

			$data = $db->query($sql);
            $data = ($data) ? $this->initOne($data[0]) : null;

            if (!is_null($data) && !file_exists((ABS_ROOT . MEDIA_UPLOAD_PATH . $data->folder . $data->filename)))
            {
                $this->delete($data);
                return null;
            }

			return $data;
		}

        public function getListByImageId($image_id)
        {
            $data = $this->orm_model->select()->where('image_id = ?', $image_id)->fetchAll();
            return $this->initList($data);
        }

	}