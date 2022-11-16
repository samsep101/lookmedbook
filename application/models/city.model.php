<?php
	/**
	 * @property int $id
	 * @property string $name
	 * @property string $prepositional_name
	 * @property string $genitive_name
	 * @property string $alias
	 * @property string $region
	 * @property int $country_id
	 * @property CountryModel $country
	 * @property int $service_flag
	 * @property string $lat
	 * @property string $lng
	 * @property int $sort
	 *
	 * @property DistrictModel[]  $districts
	 * @property string $seo_name
	 *
	 * @property bool $is_has_doctors
	 * @property bool $is_has_laboratories
	 * @property bool $is_has_clinics
	 */
	class CityModel extends DynamicModel
	{
		const MOSCOW_ID = 2;
		const NOVOSIBIRSK_ID = 693;
		const SOLNECHNOGORSK_ID = 792;
		const LUBERTSI_ID = 615;
		const ZELENOGRAD_ID = 458;

		public function _field_districts()
		{
			if(!isset($this->districts))
			{
				$district_manager = new DistrictManager();
				$this->districts = $district_manager->getListByCityId($this->getId());
			}

			return $this->districts;
		}

		public function _field_seo_name()
		{
			return $this->name;
		}

		public function hasDoctors()
		{
			return (bool)$this->is_has_doctors;
		}

		public function hasClinics()
		{
			return (bool)$this->is_has_clinics;
		}

		public function hasLaboratories()
		{
			return (bool)$this->is_has_laboratories;
		}

		public function isUsed()
		{
			return $this->hasClinics() || $this->hasDoctors() || $this->hasLaboratories();
		}
	}