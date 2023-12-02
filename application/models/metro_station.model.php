<?php
	/**
	 * @property int $id
	 * @property string $name
	 * @property string $alias
	 * @property int $metro_branch_id
	 * @property MetroBranchModel $metro_branch
	 * @property int $region_id
	 * @property RegionModel $region
	 * @property string $longitude
	 * @property string $latitude
	 * @property int $number
     * @property int $city_id
	 * @property CityModel $city
     * @property int $main_station_id
     *
	 * @property RegionModel $parent
	 * @property string $seo_name
	 * @property string $name_with_city_name
     * @property MetroStationModel $main_station
     * @property MetroStationModel[] $same_name_stations
	 */
	class MetroStationModel extends DynamicModel
	{
        /**
         * @return MetroStationManager
         */
        public function getManager()
        {
            return parent::getManager();
        }

		protected function _field_parent()
		{
			return $this->region;
		}

		protected function _field_seo_name()
		{
			return 'метро ' . $this->name;
		}

		protected function _field_name_with_city_name()
		{
			if($this->metro_branch && $this->metro_branch->metro->city)
			{
				return $this->name . ' (' . $this->metro_branch->name . ', ' . $this->metro_branch->metro->city->name . ')';
			}
			else
			{
				return $this->name;
			}
		}

		public function isMainStation()
        {
            return $this->main_station_id
                && $this->main_station_id === $this->id;
        }

        public function hasMainStation()
        {
            return $this->main_station_id
                && $this->main_station_id !== $this->id;
        }

        protected function _field_main_station()
        {
            if (!$this->hasMainStation()) {
                return null;
            }

            if (!isset($this->main_station)) {
                $this->main_station = $this->getManager()
                    ->getOneById($this->main_station_id);
            }

            return $this->main_station;
        }

        protected function _field_same_name_stations()
        {
            if (!$this->main_station_id) {
                return [];
            }

            if (!isset($this->same_name_stations)) {
                $this->same_name_stations = $this->getManager()
                    ->getSameNameStations($this->main_station_id, $this->id);
            }

            return $this->same_name_stations;
        }
    }
