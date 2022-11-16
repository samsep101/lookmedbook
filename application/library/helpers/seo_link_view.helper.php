<?php

class SeoLinkViewHelper
{
  public static function getCityPageLink($specialty, CityModel $city, $location = 'doctor')
  {
      if (is_array($specialty)) {
          $specialtyAlias = $specialty['alias'];
      } elseif (is_object($specialty)) {
          $specialtyAlias = $specialty->alias;
      } else {
          $specialtyAlias = '';
      }
    $href = LinkHelper::getSiteUrlByCity($city) . '/' . $location . '/' . $specialtyAlias;
    return $href;
  }

  public static function getDistrictPageLink($specialty, DistrictModel $district, $location = 'doctor')
  {
    $href = self::getCityPageLink($specialty, $district->city, $location);
    return $href . '/' . $district->alias;
  }

  public static function getRegionPageLink($specialty, RegionModel $region, $location = 'doctor')
  {
    $href = self::getDistrictPageLink($specialty, $region->district, $location);
    return $href . '/' . $region->alias;
  }

  public static function getStreetPageLink($specialty, StreetModel $street, $location = 'doctor')
  {
    $href = self::getDistrictPageLink($specialty, $street->regions[0]->district, $location);
    return $href . '/' . $street->alias;
  }

  public static function getMetroStationPageLink($specialty, MetroStationModel $metro_station, $location = 'doctor')
  {
    if (empty($metro_station->region_id) or !is_object($metro_station->region)) {
      return '';
    }
    $href = self::getRegionPageLink($specialty, $metro_station->region, $location);
    return $href . '/' . $metro_station->alias;
  }


  /**
   * @param SpecialtyModel|SpecializationModel $specialty
   * @param StreetModel|MetroStationModel|RegionModel|DistrictModel|CityModel|DynamicModel $address_object_model
   * @param string $location
   * @return string
   */
  public static function getSpecialtyPageLink($specialty, DynamicModel $address_object_model, $location = 'doctor')
  {
    switch (get_class($address_object_model)) {
      case 'StreetModel':
        return self::getStreetPageLink($specialty, $address_object_model, $location);
      case 'MetroStationModel':
        if ($address_object_model->main_station) {
          $address_object_model = $address_object_model->main_station;
        }
        return self::getMetroStationPageLink($specialty, $address_object_model, $location);
      case 'RegionModel':
        return self::getRegionPageLink($specialty, $address_object_model, $location);
      case 'DistrictModel':
        return self::getDistrictPageLink($specialty, $address_object_model, $location);
      case 'CityModel':
        return self::getCityPageLink($specialty, $address_object_model, $location);
      default:
        return '';
    }
  }

    /*
      * Модфицирует внешнюю ссылку добавляя rel="nofollow" class="jsLinkHidingIndexing" и перемещая href -> data-link
     */
  public static function catchOuterLinks($matches){
    $linkOuter = $matches[0];
    $site_url = strtolower(SITE_DOMAIN);
    $innerUrlPattern='/href=.*'.$site_url.'[^.]*/is';

    if (strpos($linkOuter, 'https://bookinghealth.ru/disease/rak-tolstogo-kishechnika') !== false) {
        return $linkOuter;
    }

    if (!preg_match($innerUrlPattern, $linkOuter) && strpos($linkOuter,'//')) {
      if (strpos($linkOuter, 'rel') === false) {
        $linkOuter = preg_replace("%(href=\S(?!$site_url))%i", 'rel="nofollow" $1', $linkOuter);
      } elseif (preg_match("%href=\S(?!$site_url)%i", $linkOuter)) {
        $linkOuter = preg_replace('/rel=S(?!nofollow)\S*/i', 'rel="nofollow"', $linkOuter);
      }
      if (strpos($linkOuter, 'class') === false) {

        $linkOuter = preg_replace("%(href=\S(?!$site_url))%i", 'class="jsLinkHidingIndexing" $1', $linkOuter);
      } elseif (preg_match("%href=\S(?!$site_url)%i", $linkOuter)) {
        $linkOuter = preg_replace('/class="(.*?)"/i', 'class="$1 jsLinkHidingIndexing"', $linkOuter);
      }

      if (preg_match("%href=\S(?!$site_url)%i", $linkOuter)) {
        $regV = '#(<a[a-z\-_\s\"\#\=]*)(href=")((https?|ftp)://)#i';
        $replace = '$1$2" data-link="$3';
        $linkOuter = preg_replace($regV, $replace, $linkOuter);
      }
    }
    if (   $_SERVER['REQUEST_URI']=='/disease/gripp' 
        or $_SERVER['REQUEST_URI']=='/disease/diareya'
        or $_SERVER['REQUEST_URI']=='/disease/otit'
        or $_SERVER['REQUEST_URI']=='/disease/cistit'
        ) {
        $linkOuter=str_replace('<a rel="nofollow" class="jsLinkHidingIndexing" href="" data-link="https://docdoc.ru" target="_blank"','<a class="jsLinkHidingIndexing" href="https://docdoc.ru" data-link="https://docdoc.ru" target="_blank"',$linkOuter);
        $linkOuter=str_replace('<a rel="nofollow" class="jsLinkHidingIndexing jsLinkHidingIndexing" href="" data-link="https://docdoc.ru/','<a class="jsLinkHidingIndexing jsLinkHidingIndexing" href="" data-link="https://docdoc.ru/',$linkOuter);
        $linkOuter=str_replace('<a rel="nofollow" class="jsLinkHidingIndexing" href="" data-link="https://spb.docdoc.ru" target="_blank"','<a class="jsLinkHidingIndexing" href="https://spb.docdoc.ru" data-link="https://spb.docdoc.ru" target="_blank"',$linkOuter);
        $linkOuter=str_replace('<a rel="nofollow" class="jsLinkHidingIndexing" href="" data-link="https://docdoc.ru/doctor/gastroenterolog" target="_blank"','<a class="jsLinkHidingIndexing" href="https://docdoc.ru/doctor/gastroenterolog" data-link="https://docdoc.ru/doctor/gastroenterolog" target="_blank"',$linkOuter);
        
    }
    return $linkOuter;
  }

  /*
     * Заменяем внешние ссылки на преобразованные
  */
  public static function convertLinks($html)
  {
	global $memory_allocation_costil1;

	if (!isset($memory_allocation_costil1) || (isset($memory_allocation_costil1) && !$memory_allocation_costil1) )
	    $html = preg_replace_callback('/<a[^>]+/', 'self::catchOuterLinks', $html);

    return $html;
  }
}
