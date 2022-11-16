<?php
	/**
	 * @property int $id
	 * @property string $name
	 * @property string $alias
	 * @property string $formal_name
	 * @property int $city_id
     * @property float $latitude
     * @property float $longitude
	 *
     * @property CityModel $city
	 * @property RegionModel[] $regions
	 * @property CityModel $parent
	 * @property string $seo_name
	 * @property StreetModel[] $streets
	 */
	class DistrictModel extends DynamicModel
	{
        public function _field_city()
        {
            return isset($this->city) ? $this->city : $this->city =
                (new CityManager())->getOneById($this->city_id);
        }

		public function _field_regions()
		{
			if(!isset($this->regions))
			{
				$region_manager = new RegionManager();
				$this->regions = $region_manager->getListByDistrictId($this->getId());
			}

			return $this->regions;
		}

		public function _field_parent()
		{
			return $this->city;
		}

		public function _field_seo_name()
		{
			return $this->formal_name;
		}

		public function _field_streets()
		{
			if(!isset($this->streets))
			{
				$street_manager = new StreetManager();
				$this->streets = $street_manager->getListByDistrictId($this->getId());
			}

			return $this->streets;
		}
	}
