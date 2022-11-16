<?php
	class SeoTextManager extends ModelManager
	{
		protected $table_name = 'seo_text';
		protected $model_name = 'SeoTextModel';

        /**
		 * @return SeoTextModel[]
		 */
		public function getListByCityId($city_id){
			$data = $this->orm_model->select()->where('city_id = ?', $city_id)->fetchAll();
			return $this->initList($data);
		}

        /**
		 * @return SeoTextModel[]
		 */
		public function getListBySpecialtyId($specialty_id){
			$data = $this->orm_model->select()->where('specialty_id = ?', $specialty_id)->fetchAll();
			return $this->initList($data);
		}

        /**
		 * @return SeoTextModel[]
		 */
		public function getListByDistrictId($district_id){
			$data = $this->orm_model->select()->where('district_id = ?', $district_id)->fetchAll();
			return $this->initList($data);
		}


        /**
		 * @return SeoTextModel[]
		 */
		public function getListByRegionId($region_id){
			$data = $this->orm_model->select()->where('region_id = ?', $region_id)->fetchAll();
			return $this->initList($data);
		}

        /**
		 * @return SeoTextModel[]
		 */
		public function getListByStreetId($street_id){
			$data = $this->orm_model->select()->where('street_id = ?', $street_id)->fetchAll();
			return $this->initList($data);
		}

        /**
		 * @return SeoTextModel[]
		 */
		public function getListByMetroStationId($metro_station_id){
			$data = $this->orm_model->select()->where('metro_station_id = ?', $metro_station_id)->fetchAll();
			return $this->initList($data);
		}

		/**
		 * @return SeoTextModel
		 */
		public function getOneBySpecialtyIdAndAddressObject($specialty_id, DynamicModel $address)
		{
			$sql = 'SELECT *
					FROM seo_text
					WHERE specialty_id = ' . (int)$specialty_id . '
						AND ';

			switch($address)
			{
				case 'MetroStationModel':
					$sql .= 'metro_station_id = ' . (int)$address->metro_station_id;
					break;
				case 'StreetModel':
					$sql .= 'street_id = ' . (int)$address->street_id;
					break;
				case 'RegionModel':
					$sql .= 'region_id = ' . (int)$address->region_id;
					break;
				case 'DistrictModel':
					$sql .= 'district_id = ' . (int)$address->district_id;
					break;
				case 'CityModel':
					$sql .= 'city_id = ' . (int)$address->city_id;
					break;
				default:
					return null;
			}

			$data = $this->db->query($sql);

			return $data ? $this->initOne($data[0]) : null;
		}
	}
