<?php
class SeoClinicBlockViewHelper
{
	public static function getViewByAddressObject(SpecialtyModel $specialty, DynamicModel $current_model)
	{
		$html = '';

		switch (get_class($current_model)) {
			case 'CityModel':
				$html .= self::getCitiesBlock($specialty, $current_model->getId());
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

    private static function getDataForTypePage($landing_page)
    {
        $result = array();
        $page_class = get_class($landing_page);

        if($page_class == 'ClinicServicesModel')
        {
            $result = array(
                'type' => $page_class,
                'name' => 'Услуги клиник',
            );
        }
        else if($page_class == 'ClinicTypeModel')
        {
            $result = array(
                'type' => $page_class,
                'name' => 'Типы клиник',
            );
        }

        return $result;
    }

	public static function getViewByAddressObjectInArray($landing_page, DynamicModel $current_model)
	{
		$resultData = array();

        if(!empty($landing_page)) {
            $page_data = self::getDataForTypePage($landing_page);

            if(!empty($page_data) && ($page_data['type'] == 'ClinicServicesModel' || $page_data['type'] == 'ClinicTypeModel'))
            {
                switch (get_class($current_model)) {
                    case 'CityModel':
                        $resultData['otherAddressData']['citiesBlock'] = self::getCitiesBlock($landing_page, $current_model->getId());
                        $resultData['districtsBlock'] = self::getDistrictsBlock($current_model, $landing_page, NULL);
                        break;
                    case 'DistrictModel':
                        $resultData['districtsBlock'] = self::getDistrictsBlock($current_model->city, $landing_page, $current_model->getId());
                        $resultData['otherAddressData']['regionsBlock'] = self::getRegionsBlock($current_model, $landing_page, NULL);
                        $resultData['otherAddressData']['metroStationsBlockByDistrict'] = self::getMetroStationsBlockByDistrict($current_model, $landing_page, NULL);
                        break;
                    case 'RegionModel':
                        $resultData['otherAddressData']['regionsBlock'] = self::getRegionsBlock($current_model->district, $landing_page, $current_model->getId());
                        $resultData['otherAddressData']['metroStationsBlockByRegion'] = self::getMetroStationsBlockByRegion($current_model, $landing_page, NULL);
                        $resultData['otherAddressData']['streetsBlock'] = self::getStreetsBlock($current_model, $landing_page, NULL);
                        break;
                    case 'StreetModel':
                        //$html .= self::getStreetsBlock($current_model->region, $specialty, $current_model->getId());
                        break;
                    case 'MetroStationModel':
                        $resultData['metroStationsBlockByRegion'] = self::getMetroStationsBlockByRegion($current_model->region, $landing_page, $current_model->getId());
                        break;
                }
            }
        }

		return $resultData;
	}

	public static function getCitiesBlock($landing_page, $current_id = NULL)
	{
		$city_manager = new CityManager();
        $page_data = self::getDataForTypePage($landing_page);

		$cities = $city_manager->getHavingClinicsListByTypeOrService($landing_page->getId(), $page_data['type']);

		$html = '';

		if (($current_id && count($cities) > 1) || (!$current_id && !$cities))
		{
			$html = '<div class="specialties-block">';
			$html .= '<h2>' . $page_data['name'] . ' ' . StringHelper::startProposalWord($landing_page->title) . ' в городах:</h2>';
			$html .= '<div class="text">';

			foreach ($cities as $city) {
				if ($current_id && $current_id == $city->getId())
					continue;
				$html .= '<a href="' . SeoLinkViewHelper::getSpecialtyPageLink($landing_page, $city, 'clinic') . '">' . $city->name . '</a>';
			}

			$html .= '</div>';
			$html .= '</div>';
		}

		return $html;
	}

	public static function getDistrictsBlock(CityModel $city, $landing_page, $current_id = FALSE)
	{
		$html = '';

        $page_data = self::getDataForTypePage($landing_page);
		$district_manager = new DistrictManager();

		$districts = $district_manager->getHavingClinicListByTypeOrServiceId($landing_page->getId(), $city->getId(), $page_data['type']);

		if (($current_id && count($districts) > 1) ||(!$current_id && $districts)) {
			$html = '<div class="specialties-block">';
			$html .= '<h2>' . $page_data['name'] . ' по округам города ' . $city->name . ':</h2>';
			$html .= '<div class="text">';
			foreach ($districts as $district) {
				if ($current_id && $current_id == $district->getId())
					continue;
				$html .= '<a href="' . SeoLinkViewHelper::getSpecialtyPageLink($landing_page, $district, 'clinic') . '">' . $district->formal_name . '</a>';
			}
			$html .= '</div>';
			$html .= '</div>';
		}

		return $html;
	}

	public static function getRegionsBlock(DistrictModel $district, $landing_page, $current_id)
	{
		$region_manager = new RegionManager();
        $page_data = self::getDataForTypePage($landing_page);
		$regions = $region_manager->getHavingClinicListByTypeOrService($landing_page->getId(), $district->getId(), $page_data['type']);

		$html = '';
		if (($current_id && count($regions) > 1) || (!$current_id && $regions)) {
			$html = '<div class="specialties-block">';
			$html .= '<h2>' . $page_data['name'] . ' по районам округа ' . $district->formal_name . ':</h2>';
			$html .= '<div class="text">';

			foreach ($regions as $region) {
				if ($current_id && $current_id == $region->getId())
					continue;
				$html .= '<a href="' . SeoLinkViewHelper::getSpecialtyPageLink($landing_page, $region, 'clinic') . '">' . $region->name . '</a>';
			}

			$html .= '</div>';
			$html .= '</div>';
		}

		return $html;
	}

	public static function getStreetsBlock(RegionModel $region, $landing_page, $current_id)
	{
		$street_manager = new StreetManager();
        $page_data = self::getDataForTypePage($landing_page);
		$streets = $street_manager->getHavingClinicListByTypeOrService($landing_page->getId(), $region->district->getId(), $region->getId(), $page_data['type']);

		$html = '';
		if (($current_id && count($streets) > 1) || (!$current_id && $streets)) {
			$html = '<div class="specialties-block">';
			$html .= '<h2>' . $page_data['name'] . ' по улицам района ' . $region->name . ':</h2>';
			$html .= '<div class="text">';

			foreach ($streets as $street) {
				if ($current_id && $current_id == $street->getId())
					continue;
				$html .= '<a href="' . SeoLinkViewHelper::getSpecialtyPageLink($landing_page, $street, 'clinic') . '">' . $street->full_name . '</a>';
			}

			$html .= '</div>';
			$html .= '</div>';
		}

		return $html;
	}
	
	public static function getMetroStationsBlockByDistrict(DistrictModel $district, $landing_page, $current_id)
	{
		$metro_station_manager = new MetroStationManager();
        $page_data = self::getDataForTypePage($landing_page);
		$metro_stations = $metro_station_manager->getHavingClinicListByTypeOrService($landing_page->getId(), $district->getId(), $page_data['type']);

		$html = '';

		if (($current_id && count($metro_stations) > 1) || (!$current_id && $metro_stations)) {
			$html = '<div class="specialties-block">';
			$html .= '<h2>' . $page_data['name'] . ' по станциям метро округа ' . $district->formal_name . ':</h2>';
			$html .= '<div class="text">';

			foreach ($metro_stations as $metro_station) {
				if ($current_id && $current_id == $metro_station->getId())
					continue;
				$html .= '<a href="' . SeoLinkViewHelper::getSpecialtyPageLink($landing_page, $metro_station, 'clinic') . '">' . $metro_station->name . '</a>';
			}

			$html .= '</div>';
			$html .= '</div>';
		}
		return $html;
	}

	public static function getMetroStationsBlockByRegion(RegionModel $region, $landing_page, $current_id)
	{
		$metro_station_manager = new MetroStationManager();
        $page_data = self::getDataForTypePage($landing_page);
		$metro_stations = $metro_station_manager->getHavingClinicListByTypeOrServiceForRegion($landing_page->getId(), $region->getId(), $page_data['type']);

		$html = '';

		if (($current_id && count($metro_stations) > 1) || (!$current_id && $metro_stations)) {
			$html = '<div class="specialties-block">';
			$html .= '<h2>' . $page_data['name'] . ' по станциям метро района ' . $region->name . ':</h2>';
			$html .= '<div class="text">';

			foreach ($metro_stations as $metro_station) {
				if ($current_id && $current_id == $metro_station->getId())
					continue;
				$html .= '<a href="' . SeoLinkViewHelper::getSpecialtyPageLink($landing_page, $metro_station, 'clinic') . '">' . $metro_station->name . '</a>';
			}

			$html .= '</div>';
			$html .= '</div>';
		}
		return $html;
	}
}