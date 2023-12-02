<?php
	class DoctorCharacterManager extends ModelManager
	{
		protected $table_name = 'doctor_character';
		protected $model_name = 'DoctorCharacterModel';

        /**
		 * @return DoctorCharacterModel[]
		 */
		public function getListByIsActive($is_active){
			$data = $this->orm_model->select()->where('is_active = ?', $is_active)->fetchAll();
			return $this->initList($data);
		}

	}
