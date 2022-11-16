<?php
	/**
	 * @property int $id
	 * @property string $name
	 * @property string $formal_name
	 * @property int $district_id
	 * @property DistrictModel $district
	 * @property string $alias
     * @property float $latitude
     * @property float $longitude
	 *
	 * @property string $full_name
	 * @property MetroStationModel[] $metro_stations
	 * @property DistrictModel $parent
	 * @property string $seo_name
	 * @property StreetModel[] $streets
	 */
	class RegionModel extends DynamicModel
	{
		protected function _field_full_name()
		{
			return 'район ' . $this->name;
		}

		public function _field_metro_stations()
		{
			if(!isset($this->metro_stations))
			{
				$metro_station_manager = new MetroStationManager();
				$this->metro_stations = $metro_station_manager->getListByRegionId($this->getId());
			}

			return $this->metro_stations;
		}

		public function _field_parent()
		{
			return $this->district;
		}

		public function _field_seo_name()
		{
			return $this->full_name;
		}

		public function _field_streets()
		{
			if(!isset($this->streets))
			{
				$street_manager = new StreetManager();
				$this->streets = $street_manager->getListByRegionId($this->getId());
			}

			return $this->streets;
		}
	}