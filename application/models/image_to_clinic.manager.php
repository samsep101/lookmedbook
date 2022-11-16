<?php
	class ImageToClinicManager extends ModelManager
	{
		protected $table_name = 'image_to_clinic';
		protected $model_name = 'ImageToClinicModel';

        /**
		 * @return ImageToClinicModel[]
		 */
		public function getListByImageId($image_id){
			$data = $this->orm_model->select()->where('image_id = ?', $image_id)->order('id ASC')->fetchAll();
			return $this->initList($data);
		}

        /**
		 * @return ImageToClinicModel[]
		 */
		public function getListByClinicId($clinic_id){
			$data = $this->orm_model->select()->where('clinic_id = ?', $clinic_id)->fetchAll();
			return $this->initList($data);
		}

		public function deleteByClinicId($clinic_id)
		{
			$sql = 'DELETE FROM ' . $this->table_name . '
                    WHERE  clinic_id=' . (int)$clinic_id;

			$this->db->query($sql);
		}

	}
