<?php
	class MosopenParser {

		public function parse()
		{
			$main_page_parser = new MosopenMainPageParser();
			$district_page_links = $main_page_parser->getDistrictsPageLinks();

			$district_manager = new DistrictManager();
			$region_manager = new RegionManager();
			$street_manager = new StreetManager();
			$street_to_region_manager = new StreetToRegionManager();
			$metro_station_manager = new MetroStationManager();

			$district_page_parser = new MosopenDistrictPageParser();
			$region_page_parser = new MosopenRegionPageParser();

			foreach($district_page_links as $district_page_link)
			{
				$district_page_parser->setPageUrl($district_page_link);
				$district_name = $district_page_parser->getName();
				$district = $district_manager->getOneByName($district_name);

				if(!$district)
					$district = new DistrictModel();

				$district->name = $district_name;
				$district->formal_name = $district_page_parser->getFormalName();
				$district->city_id = 2;
				$district->save();

				$regions_page_links  = $district_page_parser->getRegionsPageLinks();
				foreach($regions_page_links as $region_page_link)
				{
					$region_page_parser->setPageUrl($region_page_link);

					$region_name = $region_page_parser->getName();

					$region = $region_manager->getOneByName($region_name);

					if (!$region)
						$region = new RegionModel();

					$region->name = $region_name;
					$region->district_id = $district->getId();
					$region->save();

					$streets_info = $region_page_parser->getStreets();

					foreach($streets_info as $street_info)
					{
						$street = $street_manager->getOneByPrefixAndName($street_info->type, $street_info->name);

						if (!$street)
							$street = new StreetModel();

						$street->prefix = $street_info->type;
						$street->name = $street_info->name;
						$street->save();

						$street_to_region = $street_to_region_manager->getOneByStreetIdAndRegionId($street->getId(), $region->getId());
						if (!$street_to_region){
							$street_to_region = new StreetToRegionModel();
							$street_to_region->street_id = $street->getId();
							$street_to_region->region_id = $region->getId();
							$street_to_region->save();
						}
					}


					$metro_stations_info = $region_page_parser->getMetroStations();

					if ($metro_stations_info)
					{
						foreach($metro_stations_info as $metro_station_name)
						{
							$metro_stations  = $metro_station_manager->getListByName($metro_station_name);

							if ($metro_stations)
							{
								foreach($metro_stations as $metro_station)
								{
									$metro_station->region_id = $region->getId();
									$metro_station->save();
								}
							}
						}
					}
				}
			}
		}
	}