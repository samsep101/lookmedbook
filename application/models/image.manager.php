<?php
	class ImageManager extends ModelManager
	{
		protected $table_name = 'image';
		protected $model_name = 'ImageModel';

        protected function beforeSave(DynamicModel $model)
        {
            //ImageWatermarkHelper::setWatermark($model->path);
        }

		public static function getImage($image_id, $width, $height, $action = "crop")
		{
			if(!$image_id)
			{
				return '';
			}
			$db = Register::get('db');
			$resize_data = ResizedImageManager::getOneByImageIdAndWidthAndHeightAndAction($image_id, $width, $height, $db->escape($action));

			if($resize_data)
			{
				return MEDIA_UPLOAD_PATH . $resize_data['folder'] . $resize_data['filename'];
			}
			else
			{
				$imageinfo = self::getById($image_id);

				if($imageinfo)
				{
					if(file_exists(ABS_ROOT . MEDIA_UPLOAD_PATH . $imageinfo['folder'] . $imageinfo['filename']))
					{
						$new_filename = $width . 'x' . $height . '-' . $action . '-' . $imageinfo['filename'];

						$image_resizer = new SimpleImage();

						$image_resizer->load(ABS_ROOT . MEDIA_UPLOAD_PATH . $imageinfo['folder'] . $imageinfo['filename']);

						if($action == 'resize')
						{
							$image_resizer->resizeWithRatio($width, $height);
						}
						elseif($action == 'resize2')
						{

						}
						elseif($action == 'crop')
						{
							$image_resizer->crop($width, $height);
						}
						$image_resizer->save(ABS_ROOT . MEDIA_UPLOAD_PATH . $imageinfo['folder'] . $new_filename);

						$ins_data = array('filename' => $new_filename, 'folder' => $imageinfo['folder'], 'image_id' => $image_id, 'width' => $width, 'height' => $height, 'action' => $action);
						ResizedImagesManager::add($ins_data);

						return MEDIA_UPLOAD_PATH . $imageinfo['folder'] . $new_filename;
					}
					else
					{
						return '/media/images/dafault_photo.png';
					}
				}
				else
				{
					return '/media/images/dafault_photo.png';
				}

			}
		}

        /**
		 * @return ImageModel[]
		 */
		public function getListByDoctorId($doctor_id)
		{
			$sql = 'SELECT i.*
                    FROM image i
                    INNER JOIN image_to_doctor i2d ON i.id = i2d.image_id
                    WHERE i2d.doctor_id = ' . (int)$doctor_id . '
                   	ORDER BY i2d.id';

			$data = $this->db->query($sql);

			return $this->initList($data);
		}

        /**
		 * @return ImageModel[]
		 */
		public function getListByClinicId($clinic_id)
		{
			$sql = 'SELECT i.*
                    FROM image i
                    INNER JOIN image_to_clinic i2c ON i.id = i2c.image_id
                    WHERE i2c.clinic_id = ' . (int)$clinic_id . '
                    ORDER BY i2c.id';

			$data = $this->db->query($sql);

			return $this->initList($data);
		}

        public function getOneByFilename($filename)
        {
            $sql = 'SELECT *
                    FROM image
                    WHERE filename LIKE  "%' .$this->db->escape($filename). '%"
                    LIMIT 1';

            $data = $this->db->query($sql);

            return (isset($data[0])) ? $this->initOne($data[0]) : null;
        }

        /** Получение последней картинки по имени файла */
        public function getOneLastFilename($filename, $model_rows = false)
        {
            $sql = 'SELECT *
                    FROM image
                    WHERE filename LIKE "' .$this->db->escape($filename). '%"
                        ORDER BY id DESC
                    LIMIT 1';

            $data = $this->db->query($sql);

            if(isset($data[0])){
                if($model_rows) {
                    return $this->initOne($data[0]);
                }
                return $data[0];
            }

            return false;
        }

        public function generateImages($images) {
            foreach($images as $image)
            {
                if(file_exists(ABS_ROOT . $image->path))
                {
                    $resized_image_manager = ModelManagerFactory::getByName('resized_image');

                    foreach($resized_image_manager->getListByImageId($image->getId()) as $resized_image)
                    {
                        $new_filename = $resized_image->width . 'x' . $resized_image->height . '-' . $resized_image->action . '-' . $image->filename;

                        if (!file_exists(ABS_ROOT . MEDIA_UPLOAD_PATH . $image->folder . $new_filename)) {
                            $image_resizer = new SimpleImage();

                            $image_resizer->load(ABS_ROOT . MEDIA_UPLOAD_PATH . $image->folder . $image->filename);
                            if($resized_image->action == 'crop')
                            {
                                $image_resizer->crop($resized_image->width, $resized_image->height);
                            }
                            else
                            {
                                $image_resizer->resizeWithRatio($resized_image->width, $resized_image->height);
                            }

                            $image_resizer->save(ABS_ROOT . MEDIA_UPLOAD_PATH . $image->folder . $new_filename, $image->image_info[2]);

                            if($resized_image->is_with_watermark)
                            {
                                ImageWatermarkHelper::setWatermark(ABS_ROOT . MEDIA_UPLOAD_PATH . $image->folder . $new_filename);
                            }
                        }
                    }
                }
            }
        }
	}
