<?php
	class FileCacheGenerator implements CacheGenerator
	{
		public function generate()
		{
			$this->generateSeoBlocks();
		}

		private function generateSeoBlocks()
		{
			$view = new View();
			$view->cache = Register::get('cache');
			$view->is_seo_page = true;

			$city_manager = new CityManager();
			$district_manager = new DistrictManager();
			$region_manager = new RegionManager();
			$street_manager = new StreetManager();
			$metro_station_manager = new MetroStationManager();

			$cities = $city_manager->getActiveList();

			$specialty_manager = new SpecialtyManager();

			foreach($cities as $city)
			{
				$specialties = $specialty_manager->getHavingDoctorsListByAddressObject($city);

				foreach($specialties as $specialty)
				{
					$view->specialties = $specialties;
					$view->specialty = $specialty;
					$view->address_object = $city;

					$view->render($this->getTemplatePath('doctor/blocks/seo_block'));

					$districts = $district_manager->getHavingDoctorsListBySpecialtyIdAndCityId($specialty->getId(), $city->getId());

					foreach($districts as $district)
					{
						$view->specialties = $specialty_manager->getHavingDoctorsListByAddressObject($district);
						$view->address_object = $district;
						$view->render($this->getTemplatePath('doctor/blocks/seo_block'));

						$regions = $region_manager->getHavingDoctorsListBySpecialtyIdAndDistrictId($specialty->getId(), $district->getId());
						foreach($regions as $region)
						{
							$view->specialties = $specialty_manager->getHavingDoctorsListByAddressObject($region);
							$view->address_object = $region;
							$view->render($this->getTemplatePath('doctor/blocks/seo_block'));

							$metro_stations = $metro_station_manager->getHavingDoctorsListBySpecialtyIdAndRegionId($specialty->getId(), $region->getId());

							foreach($metro_stations as $metro_station)
							{
								$view->specialties = $specialty_manager->getHavingDoctorsListByAddressObject($metro_station);
								$view->address_object = $metro_station;
								$view->render($this->getTemplatePath('doctor/blocks/seo_block'));
							}
						}

						$streets = $street_manager->getHavingDoctorsListBySpecialtyIdAndDistrictId($specialty->getId(), $district->getId());
						foreach($streets as $street)
						{
							$view->specialties = $specialty_manager->getHavingDoctorsListByAddressObject($street);
							$view->address_object = $street;
							$view->render($this->getTemplatePath('doctor/blocks/seo_block'));
						}
					}


				}
			}
		}

		private function getTemplatePath($templateName)
		{
			$folder =  '';

			$path = NULL;
			if (preg_match('/\//', $templateName)) {
				$path = Application::getTemplatesDir(TRUE) . '/' . $templateName;
				;
			} elseif (strlen($templateName) != 0) {
				$path = Application::getTemplatesDir(TRUE) . '/' . $this->controller . '/' . $templateName;
			} else {
				$path = Application::getTemplatesDir(TRUE) . '/' . $folder . $this->controller . '/' . $this->action;
			}

			return $path;
		}
	}