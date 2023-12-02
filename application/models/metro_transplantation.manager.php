<?php
	class MetroTransplantationManager extends ModelManager
	{
		protected $table_name = 'metro_transplantation';
		protected $model_name = 'MetroTransplantationModel';


        /**
		 * @return MetroTransplantationModel[]
		 */
		public function getListByMetroStationId($metro_station_id)
		{
			$data = $this->orm_model->select()->where('metro_station_id = ?', $metro_station_id)->fetchAll();
			return $this->initList($data);
		}

        /**
		 * @return MetroTransplantationModel[]
		 */
		public function getListByTransplantationStationId($transplantation_station_id)
		{
			$data = $this->orm_model->select()->where('transplantation_station_id = ?', $transplantation_station_id)->fetchAll();
			return $this->initList($data);
		}

	}
