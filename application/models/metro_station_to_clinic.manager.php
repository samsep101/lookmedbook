<?php
class MetroStationToClinicManager extends ModelManager {
		protected $model_name = 'MetroStationToClinicModel';
		protected $table_name = 'metro_station_to_clinic';

		public function getListByClinicId($clinic_id)
		{
			$data = $this->orm_model->select()->where('clinic_id = ?', $clinic_id)->fetchAll();
			return $this->initList($data);
		}

		public function deleteByClinicId($clinic_id)
		{
			$sql = 'DELETE FROM ' . $this->table_name . '
					WHERE clinic_id = ' . (int)$clinic_id;

			$this->db->query($sql);
		}

    /**
     * @param $clinic_id
     * @return MetroStationToClinicModel|null
     * @throws Exception
     */
		public function getOneByClinicId($clinic_id)
		{
			$sql = 'SELECT * FROM metro_station_to_clinic
					WHERE clinic_id = ' .(int)$clinic_id;
			$data = $this->db->query($sql);

			return ($data) ? $this->initOne($data[0]) : null;
		}


		public function setClinicMetroId($clinic_id, $metro_id) {
			$this->deleteByClinicId($clinic_id);

			$sql = 'INSERT INTO metro_station_to_clinic (clinic_id, metro_station_id)
					VALUES(' .(int)$clinic_id . ',' . (int)$metro_id . ')';
			$this->db->query($sql);

		}

        public function getClinicMetroID( $clinicId, $metroStationId ){

            $sql = "SELECT id FROM {$this->table_name} WHERE metro_station_id = {$metroStationId} AND clinic_id = {$clinicId}";
            $data = $this->db->query($sql);
            return ($data) ? $this->initOne($data[0]) : null;
        }

}