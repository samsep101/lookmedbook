<?php
class SeoSpecializationBlockViewHelper
{
    public static function getViewByAddressObject(SpecializationModel $specialization, DynamicModel $current_model)
    {
        $html = '';

        switch (get_class($current_model)) {
            case 'CityModel':
                // Блок линковки по городам удален в рамках #3197. Он был прямо тут, правда-правда!
                $html .= self::getDistrictsBlock($current_model, $specialization, NULL);
                break;
            case 'DistrictModel':
                $html .= self::getDistrictsBlock($current_model->city, $specialization, $current_model->getId());
                $html .= self::getRegionsBlock($current_model, $specialization, NULL);
                $html .= self::getMetroStationsBlockByDistrict($current_model, $specialization, NULL);
                break;
            case 'RegionModel':
                $html .= self::getRegionsBlock($current_model->district, $specialization, $current_model->getId());
                $html .= self::getMetroStationsBlockByRegion($current_model, $specialization, NULL);
                $html .= self::getStreetsBlock($current_model, $specialization, NULL);
                break;
            case 'StreetModel':
                //$html .= self::getStreetsBlock($current_model->region, $specialty, $current_model->getId());
                break;
            case 'MetroStationModel':
                $html .= self::getMetroStationsBlockByRegion($current_model->region, $specialization, $current_model->getId());
                break;
        }
        return $html;
    }

    public static function getViewByAddressObjectInArray(SpecializationModel $specialization, DynamicModel $current_model)
    {
        $resultData = array();

        switch (get_class($current_model)) {
            case 'CityModel':
                // Блок линковки по городам удален в рамках #3197. Он был прямо тут, правда-правда!
                $resultData['districtsBlock'] = self::getDistrictsBlock($current_model, $specialization, NULL);
                break;
            case 'DistrictModel':
                $resultData['districtsBlock'] = self::getDistrictsBlock($current_model->city, $specialization, $current_model->getId());
                $resultData['otherAddressData']['regionsBlock'] = self::getRegionsBlock($current_model, $specialization, NULL);
                $resultData['otherAddressData']['metroStationsBlockByDistrict'] = self::getMetroStationsBlockByDistrict($current_model, $specialization, NULL);
                break;
            case 'RegionModel':
                $resultData['otherAddressData']['regionsBlock'] = self::getRegionsBlock($current_model->district, $specialization, $current_model->getId());
                $resultData['otherAddressData']['metroStationsBlockByRegion'] = self::getMetroStationsBlockByRegion($current_model, $specialization, NULL);
                break;
            case 'StreetModel':
                //$html .= self::getStreetsBlock($current_model->region, $specialty, $current_model->getId());
                break;
            case 'MetroStationModel':
                $resultData['otherAddressData']['metroStationsBlockByRegion'] = self::getMetroStationsBlockByRegion($current_model->region, $specialization, $current_model->getId());
                break;
        }

        return $resultData;
    }

    public static function getDistrictsBlock(CityModel $city, SpecializationModel $specializationModel, $current_id = FALSE)
    {
        $html = '';

        $district_manager = new DistrictManager();
        $districts = $district_manager->getHavingClinicListBySpecializationIdAndCityId($specializationModel->getId(), $city->getId());

        if (($current_id && count($districts) > 1) ||(!$current_id && $districts)) {
            $html = '<div class="specialties-block">';
            $html .= '<h2>' . StringHelper::startProposalWord($specializationModel->name) . ' по округам города ' . $city->name . ':</h2>';
            $html .= '<div class="text">';
            foreach ($districts as $district) {
                if ($current_id && $current_id == $district->getId())
                    continue;
                // в данном случае specialty == specialization (вроде как)
                $html .= '<a href="' . SeoLinkViewHelper::getSpecialtyPageLink($specializationModel, $district, 'clinic') . '">' . $district->formal_name . '</a>';
            }
            $html .= '</div>';
            $html .= '</div>';
        }

        return $html;
    }

    public static function getRegionsBlock(DistrictModel $district, SpecializationModel $specialty, $current_id)
    {
        $region_manager = new RegionManager();
        $regions = $region_manager->getHavingClinicListBySpecializationIdAndDistrictId($specialty->getId(), $district->getId());

        $html = '';
        if (($current_id && count($regions) > 1) || (!$current_id && $regions)) {
            $html = '<div class="specialties-block">';
            $html .= '<h2>' . StringHelper::startProposalWord($specialty->name) . ' по районам округа ' . $district->formal_name . ':</h2>';
            $html .= '<div class="text">';

            foreach ($regions as $region) {
                if ($current_id && $current_id == $region->getId())
                    continue;
                // в данном случае specialty == specialization (вроде как)
                $html .= '<a href="' . SeoLinkViewHelper::getSpecialtyPageLink($specialty, $region, 'clinic') . '">' . $region->name . '</a>';
            }

            $html .= '</div>';
            $html .= '</div>';
        }

        return $html;
    }

    public static function getStreetsBlock(RegionModel $region, SpecializationModel $specialty, $current_id)
    {
        $street_manager = new StreetManager();
        $streets = $street_manager->getHavingDoctorsListBySpecialtyIdAndDistrictId($specialty->getId(), $region->district->getId());

        $html = '';
        if (($current_id && count($streets) > 1) || (!$current_id && $streets)) {
            $html = '<div class="specialties-block">';
            $html .= '<h2>' . StringHelper::startProposalWord($specialty->name) . ' по улицам района ' . $region->name . ':</h2>';
            $html .= '<div class="text">';

            foreach ($streets as $street) {
                if ($current_id && $current_id == $street->getId())
                    continue;
                $html .= '<a href="' . SeoLinkViewHelper::getSpecialtyPageLink($specialty, $street, 'clinic') . '">' . $street->full_name . '</a>';
            }

            $html .= '</div>';
            $html .= '</div>';
        }

        return $html;
    }

    public static function getMetroStationsBlockByDistrict(DistrictModel $district, SpecializationModel $specialization, $current_id)
    {
        $metro_station_manager = new MetroStationManager();
        $metro_stations = $metro_station_manager->getHavingClinicListBySpecializationIdAndDistrictId($specialization->getId(), $district->getId());

        $html = '';

        if (($current_id && count($metro_stations) > 1) || (!$current_id && $metro_stations)) {
            $html = '<div class="specialties-block">';
            $html .= '<h2>' . StringHelper::startProposalWord($specialization->name) . ' по станциям метро округа ' . $district->formal_name . ':</h2>';
            $html .= '<div class="text">';

            foreach ($metro_stations as $metro_station) {
                if ($current_id && $current_id == $metro_station->getId())
                    continue;
                // в данном случае specialty == specialization (вроде как)
                $html .= '<a href="' . SeoLinkViewHelper::getSpecialtyPageLink($specialization, $metro_station, 'clinic') . '">' . $metro_station->name . '</a>';
            }

            $html .= '</div>';
            $html .= '</div>';
        }
        return $html;
    }

    public static function getMetroStationsBlockByRegion(RegionModel $region, SpecializationModel $specialization, $current_id)
    {
        $metro_station_manager = new MetroStationManager();
        $metro_stations = $metro_station_manager->getHavingClinicListBySpecializationIdAndRegionId($specialization->getId(), $region->getId());

        $html = '';

        if (($current_id && count($metro_stations) > 1) || (!$current_id && $metro_stations)) {
            $html = '<div class="specialties-block">';
            $html .= '<h2>' . StringHelper::startProposalWord($specialization->name) . ' по станциям метро района ' . $region->name . ':</h2>';
            $html .= '<div class="text">';

            foreach ($metro_stations as $metro_station) {
                if ($current_id && $current_id == $metro_station->getId())
                    continue;
                // в данном случае specialty == specialization (вроде как)
                $html .= '<a href="' . SeoLinkViewHelper::getSpecialtyPageLink($specialization, $metro_station, 'clinic') . '">' . $metro_station->name . '</a>';
            }

            $html .= '</div>';
            $html .= '</div>';
        }
        return $html;
    }
}