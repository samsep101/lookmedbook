<?php
	class ImageToDoctorManager extends ModelManager
	{
		protected $table_name = 'image_to_doctor';
		protected $model_name = 'ImageToDoctorModel';

        /**
		 * @return ImageToDoctorModel[]
		 */
		public function getListByDoctorId($doctor_id){
			$data = $this->orm_model->select()->where('doctor_id = ?', $doctor_id)->order('id ASC')->fetchAll();
			return $this->initList($data);
		}

        /**
		 * @return ImageToDoctorModel[]
		 */
		public function getListByImageId($image_id){
			$data = $this->orm_model->select()->where('image_id = ?', $image_id)->fetchAll();
			return $this->initList($data);
		}

		public function deleteByDoctorId($doctor_id)
		{
			$sql = 'DELETE FROM ' . $this->table_name . '
                    WHERE  doctor_id=' . (int)$doctor_id;

			$this->db->query($sql);
		}
	}
