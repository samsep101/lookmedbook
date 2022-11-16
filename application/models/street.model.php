<?php
	/**
	 * @property int $id
	 * @property string $name
	 * @property string $alias
	 * @property string $prefix
	 * @property int $street_type_id
     * @property int $city_id
     * @property int $street_id
	 * @property StreetTypeModel $street_type
     * @property float $latitude
     * @property float $longitude
	 *
	 * @property string $full_name
	 * @property RegionModel[] $regions
	 * @property DistrictModel $district
	 * @property DistrictModel $parent
	 * @property string $seo_name
	 */
	class StreetModel extends DynamicModel
	{
		public function _field_full_name()
		{
			return $this->prefix . ' ' . $this->name;
		}

		public function _field_regions()
		{
			if(!isset($this->regions))
			{
				$region_manager = new RegionManager();
				$this->regions = $region_manager->getListByStreetId($this->getId());
			}

			return $this->regions;
		}

		public function _field_district()
		{
			return $this->regions[0]->district;
		}

		public function _field_parent()
		{
			return $this->district;
		}

		public function _field_seo_name()
		{
			return $this->full_name;
		}

		public function isBelongToRegion($region_id)
		{
			$street_to_region_manager = new StreetToRegionManager();
			return $street_to_region_manager->checkExistsByRegionIdAndStreetId($region_id, $this->getId());
		}

		public function isBelongToDistrict($district_id)
		{
			$street_to_region_manager = new StreetToRegionManager();
			return $street_to_region_manager->checkRelationByDistrictIdAndStreetId($district_id, $this->getId());
		}
	}
