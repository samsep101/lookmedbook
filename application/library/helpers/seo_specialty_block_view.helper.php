<?php
class SeoSpecialtyBlockViewHelper
{
	public static function getViewByAddressObject(SpecialtyModel $specialty, DynamicModel $current_model)
	{
		$html = '';

		switch (get_class($current_model)) {
			case 'CityModel':
                // Блок линковки по городам удален в рамках #3197. Он был прямо тут, правда-правда!
				$html .= self::getDistrictsBlock($current_model, $specialty, NULL);
				break;
			case 'DistrictModel':
				$html .= self::getDistrictsBlock($current_model->city, $specialty, $current_model->getId());
				$html .= self::getRegionsBlock($current_model, $specialty, NULL);
				$html .= self::getMetroStationsBlockByDistrict($current_model, $specialty, NULL);
				break;
			case 'RegionModel':
				$html .= self::getRegionsBlock($current_model->district, $specialty, $current_model->getId());
				$html .= self::getMetroStationsBlockByRegion($current_model, $specialty, NULL);
				$html .= self::getStreetsBlock($current_model, $specialty, NULL);
				break;
			case 'StreetModel':
				//$html .= self::getStreetsBlock($current_model->region, $specialty, $current_model->getId());
				break;
			case 'MetroStationModel':
				$html .= self::getMetroStationsBlockByRegion($current_model->region, $specialty, $current_model->getId());
				break;
		}
		return $html;
	}

	public static function getViewByAddressObjectInArray(SpecialtyModel $specialty, DynamicModel $current_model)
	{
		$resultData = array();

		switch (get_class($current_model)) {
			case 'CityModel':
                // Блок линковки по городам удален в рамках #3197. Он был прямо тут, правда-правда!
                $resultData['districtsBlock'] = self::getDistrictsBlock($current_model, $specialty, NULL);
				break;
			case 'DistrictModel':
                $resultData['districtsBlock'] = self::getDistrictsBlock($current_model->city, $specialty, $current_model->getId());
                $resultData['otherAddressData']['regionsBlock'] = self::getRegionsBlock($current_model, $specialty, NULL);
                $resultData['otherAddressData']['metroStationsBlockByDistrict'] = self::getMetroStationsBlockByDistrict($current_model, $specialty, NULL);
				break;
			case 'RegionModel':
                $resultData['otherAddressData']['regionsBlock'] = self::getRegionsBlock($current_model->district, $specialty, $current_model->getId());
                $resultData['otherAddressData']['metroStationsBlockByRegion'] = self::getMetroStationsBlockByRegion($current_model, $specialty, NULL);
				break;
			case 'StreetModel':
				//$html .= self::getStreetsBlock($current_model->region, $specialty, $current_model->getId());
				break;
			case 'MetroStationModel':
                $resultData['metroStationsBlockByRegion'] = self::getMetroStationsBlockByRegion($current_model->region, $specialty, $current_model->getId());
				break;
		}

		return $resultData;
	}

	public static function getCitiesBlock(SpecialtyModel $specialty, $current_id = NULL)
	{
		$city_manager = new CityManager();

		$cities = $city_manager->getHavingDoctorsListBySpecialtyId($specialty->getId());

		$html = '';

		if (($current_id && count($cities) > 1) || (!$current_id && !$cities))
		{
			$html = '<div class="specialties-block">';
			$html .= '<h2>' . StringHelper::startProposalWord($specialty->plural_name) . ' в городах:</h2>';
			$html .= '<div class="text">';

			$needed_cities = [2, 770, 693, 902, 671, 489, 768, 714, 957];//TODO: эти города у нас разрешены. Надо вынести в конфиг!

			foreach ($cities as $city) {
				$city_id = $city->getId();
				if (($current_id and $current_id == $city_id) or !in_array($city_id, $needed_cities)) {
					continue;
				}
				$html .= '<a href="' . SeoLinkViewHelper::getSpecialtyPageLink($specialty, $city) . '">' . $city->name . '</a>';
			}

			$html .= '</div>';
			$html .= '</div>';
		}


		return $html;
	}

	public static function getDistrictsBlock(CityModel $city, SpecialtyModel $specialty, $current_id = FALSE)
	{
		$html = '';

		$district_manager = new DistrictManager();
		$districts = $district_manager->getHavingDoctorsListBySpecialtyIdAndCityId($specialty->getId(), $city->getId());

		if (($current_id && count($districts) > 1) ||(!$current_id && $districts)) {
			$html = '<div class="specialties-block">';
			$html .= '<h2>' . StringHelper::startProposalWord($specialty->plural_name) . ' по округам ' . SeoTextViewHelper::getAddressObjectName($city) . ':</h2>';
			$html .= '<div class="text">';
			foreach ($districts as $district) {
				if ($current_id && $current_id == $district->getId())
					continue;
				$html .= '<a href="' . SeoLinkViewHelper::getSpecialtyPageLink($specialty, $district) . '">' . $district->formal_name . '</a>';
			}
			$html .= '</div>';
			$html .= '</div>';
		}

		return $html;
	}

	public static function getRegionsBlock(DistrictModel $district, SpecialtyModel $specialty, $current_id)
	{
		$region_manager = new RegionManager();
		$regions = $region_manager->getHavingDoctorsListBySpecialtyIdAndDistrictId($specialty->getId(), $district->getId());

		$html = '';
		if (($current_id && count($regions) > 1) || (!$current_id && $regions)) {
			$html = '<div class="specialties-block">';
			$html .= '<h2>' . StringHelper::startProposalWord($specialty->plural_name) . ' по районам округа ' . $district->formal_name . ':</h2>';
			$html .= '<div class="text">';

			foreach ($regions as $region) {
				if ($current_id && $current_id == $region->getId())
					continue;
				$html .= '<a href="' . SeoLinkViewHelper::getSpecialtyPageLink($specialty, $region) . '">' . $region->name . '</a>';
			}

			$html .= '</div>';
			$html .= '</div>';
		}

		return $html;
	}

	public static function getStreetsBlock(RegionModel $region, SpecialtyModel $specialty, $current_id)
	{
		$street_manager = new StreetManager();
		$streets = $street_manager->getHavingDoctorsListBySpecialtyIdAndDistrictId($specialty->getId(), $region->district->getId());

		$html = '';
		if (($current_id && count($streets) > 1) || (!$current_id && $streets)) {
			$html = '<div class="specialties-block">';
			$html .= '<h2>' . StringHelper::startProposalWord($specialty->plural_name) . ' по улицам района ' . $region->name . ':</h2>';
			$html .= '<div class="text">';

			foreach ($streets as $street) {
				if ($current_id && $current_id == $street->getId())
					continue;
				$html .= '<a href="' . SeoLinkViewHelper::getSpecialtyPageLink($specialty, $street) . '">' . $street->full_name . '</a>';
			}

			$html .= '</div>';
			$html .= '</div>';
		}

		return $html;
	}
	
	public static function getMetroStationsBlockByDistrict(DistrictModel $district, SpecialtyModel $specialty, $current_id)
	{
		$metro_station_manager = new MetroStationManager();
		$metro_stations = $metro_station_manager->getHavingDoctorsListBySpecialtyIdAndDistrictId($specialty->getId(), $district->getId());

		$html = '';

		if (($current_id && count($metro_stations) > 1) || (!$current_id && $metro_stations)) {
			$html = '<div class="specialties-block">';
			$html .= '<h2>' . StringHelper::startProposalWord($specialty->plural_name) . ' по станциям метро округа ' . $district->formal_name . ':</h2>';
			$html .= '<div class="text">';

			foreach ($metro_stations as $metro_station) {
				if ($current_id && $current_id == $metro_station->getId())
					continue;
				$html .= '<a href="' . SeoLinkViewHelper::getSpecialtyPageLink($specialty, $metro_station) . '">' . $metro_station->name . '</a>';
			}

			$html .= '</div>';
			$html .= '</div>';
		}
		return $html;
	}

	public static function getMetroStationsBlockByRegion(RegionModel $region, SpecialtyModel $specialty, $current_id)
	{
		$metro_station_manager = new MetroStationManager();
		$metro_stations = $metro_station_manager->getHavingDoctorsListBySpecialtyIdAndRegionId($specialty->getId(), $region->getId());

		$html = '';

		if (($current_id && count($metro_stations) > 1) || (!$current_id && $metro_stations)) {
			$html = '<div class="specialties-block">';
			$html .= '<h2>' . StringHelper::startProposalWord($specialty->plural_name) . ' по станциям метро района ' . $region->name . ':</h2>';
			$html .= '<div class="text">';

			foreach ($metro_stations as $metro_station) {
				if ($current_id && $current_id == $metro_station->getId())
					continue;
				$html .= '<a href="' . SeoLinkViewHelper::getSpecialtyPageLink($specialty, $metro_station) . '">' . $metro_station->name . '</a>';
			}

			$html .= '</div>';
			$html .= '</div>';
		}
		return $html;
	}
}