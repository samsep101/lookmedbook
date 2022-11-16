<?php
	class StreetToRegionManager extends ModelManager
	{
		protected $table_name = 'street_to_region';
		protected $model_name = 'StreetToRegionModel';

		/**
		 * @return StreetToRegionModel
		 */
		public function getOneByStreetIdAndRegionId($street_id, $region_id)
		{
			$data = $this->orm_model->select()->where('street_id = ? AND region_id = ?', $street_id, $region_id)->fetchAll();
			return $this->initList($data);
		}

        /**
		 * @return StreetToRegionModel[]
		 */
		public function getListByStreetId($street_id){
			$data = $this->orm_model->select()->where('street_id = ?', $street_id)->fetchAll();
			return $this->initList($data);
		}

        /**
		 * @return StreetToRegionModel[]
		 */
		public function getListByDistrictId($district_id){
			$data = $this->orm_model->select()->where('district_id = ?', $district_id)->fetchAll();
			return $this->initList($data);
		}


		public function checkExistsByRegionIdAndStreetId($region_id, $street_id)
		{
			$sql = 'SELECT COUNT(*) as result
					FROM street_to_region
					WHERE street_id = ' . (int)$street_id . '
						AND region_id = ' . (int)$region_id;

			$data = $this->db->query($sql);

			return $data[0]['result'];
		}

		public function checkRelationByDistrictIdAndStreetId($district_id, $street_id)
		{
			$sql = 'SELECT COUNT(*) as `result`
					FROM district d
					INNER JOIN region r ON d.id = r.district_id
					WHERE r.id IN (
							SELECT s2r.region_id
							FROM street_to_region s2r
							WHERE s2r.region_id = r.id
								AND s2r.street_id = ' . (int)$street_id . '
						)
						AND d.id = ' . (int)$district_id;

			$data = $this->db->query($sql);

			return $data[0]['result'];
		}

        /**
         * @return StreetToRegionModel
         */
        public function getOneByStreetId($street_id)
        {
            $data = $this->orm_model->select()->where('street_id = ?', $street_id)->fetchOne();
            return $this->initOne($data);
        }

	}
