<?php
	class SeoLinksHelper
	{
		public static function getCityByPageLink()
		{
			$url = trim(str_replace(SITE_SCHEME . '://', '', SITE_URL), '/');

			$city = null;

			/**
			 * @var CityManager $city_manager
			 */
			$city_manager = ModelManagerFactory::getByName('city');

			if (preg_match('/^(' . SITE_SCHEME . ':\/\/)?([A-Za-z\-]+)\.'.$url.'$/', SERVER_NAME, $matches))
			{
				$alias = $matches[2];
				$city = $city_manager->getOneByAlias($alias);
			} else {
				$city = $city_manager->getOneById(CityModel::MOSCOW_ID);
			}

			return $city;
		}

		public static function getLinks()
		{
			$links = array();

			// Ссылки SEO-страниц
			$city_manager = new CityManager();
			$specialty_manager = new SpecialtyManager();

			$cities = $city_manager->getActiveList();

			$district_manager = new DistrictManager();
			$region_manager = new RegionManager();
			$street_manager = new StreetManager();
			$metro_station_manager = new MetroStationManager();

			foreach($cities as $city)
			{
				$city_specialties = $specialty_manager->getHavingDoctorsListByAddressObject($city);

				foreach($city_specialties as $city_specialty)
				{
					$links[] = SITE_URL . SeoLinkViewHelper::getSpecialtyPageLink($city_specialty, $city);
				}
				unset($city_specialties);
			}


			$districts = $district_manager->getHavingDoctorsList();
			foreach($districts as $district)
			{
				$district_specialties = $specialty_manager->getHavingDoctorsListByAddressObject($district);

				foreach($district_specialties as $specialty)
				{
					$links[] = SITE_URL . SeoLinkViewHelper::getSpecialtyPageLink($specialty, $district);
				}
				unset($district_specialties);
			}

			$regions = $region_manager->getHavingDoctorsList();
			foreach($regions as $region)
			{
				$region_specialties = $specialty_manager->getHavingDoctorsListByAddressObject($region);

				foreach($region_specialties as $specialty)
				{
					$links[] = SITE_URL . SeoLinkViewHelper::getSpecialtyPageLink($specialty, $region);
				}
				unset($region_specialties);
			}

			$metro_stations = $metro_station_manager->getHavingDoctorsList();
			if ($metro_stations)
			{
				foreach($metro_stations as $metro_station)
				{
					$metro_station_specialties = $specialty_manager->getHavingDoctorsListByAddressObject($metro_station);

					foreach($metro_station_specialties as $specialty)
					{
						$links[] = SITE_URL . SeoLinkViewHelper::getSpecialtyPageLink($specialty, $metro_station);
					}
					unset($metro_station_specialties);
				}
			}

			$streets = $street_manager->getHavingDoctorsList();
			if ($streets)
			{
				foreach($streets as $street)
				{
					$street_specialties = $specialty_manager->getHavingDoctorsListByAddressObject($street);

					foreach($street_specialties as $specialty)
					{
						$links[] = SITE_URL . SeoLinkViewHelper::getSpecialtyPageLink($specialty, $street);
					}
					unset($metro_station_specialties);
				}
			}

			$specialty_manager->clearRegister();
			$city_manager->clearRegister();
			$district_manager->clearRegister();
			$region_manager->clearRegister();
			$street_manager->clearRegister();
			$metro_station_manager->clearRegister();

			unset($specialties);
			unset($streets);
			unset($districts);
			unset($regions);

			return $links;
		}
	}