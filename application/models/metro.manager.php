<?php
	class MetroManager extends ModelManager
	{
		protected $table_name = 'metro';
		protected $model_name = 'MetroModel';

        /**
		 * @return MetroModel[]
		 */
		public function getListByCityId($city_id){
			$data = $this->orm_model->select()->where('city_id = ?', $city_id)->fetchAll();
			return $this->initList($data);
		}
	}
