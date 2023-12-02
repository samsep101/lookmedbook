<?php
    class YandexGeocoder
    {
        private $url_template = 'https://geocode-maps.yandex.ru/1.x/?geocode=%name%&format=json';

		public $api_data;

        public function geocode($name)
        {
            $url = str_replace('%name%',urlencode($name), $this->url_template);

            $data = json_decode(file_get_contents($url), true);

            if ($data['response']['GeoObjectCollection']['metaDataProperty']['GeocoderResponseMetaData']['results'] > 0) {
                $point_string = $data['response']['GeoObjectCollection']['featureMember'][0]['GeoObject']['Point']['pos'];
                $point = explode(' ', $point_string);
                return new GeoPoint($point[1], $point[0]);
            } else {
                return FALSE;
            }
        }

		public function getDistrictInfoByGeoPoint(GeoPoint $geo_point,$only_locality = false)
		{
			$region_name = '';
            $this->getApiDataByGeoPointAndKind($geo_point, 'district');
            $api_data = $this->api_data;
			foreach($api_data['response']['GeoObjectCollection']['featureMember'] as $v)
			{
				if (mb_strpos($v['GeoObject']['name'], 'район', null, 'utf-8') !== FALSE)
				{

					$region_name = $v['GeoObject']['name'];
				}
			}

			if ($region_name) {
				$region_name = str_replace('район', '', $region_name);
				$region_name = trim($region_name);

				if(preg_match('/^(.+) ((?:Западное)|(?:Южное)|(?:Северное)|(?:Восточное))$/imsu', $region_name, $matches))
				{
					$region_name = $matches[2].' '.$matches[1];
					$region_name = trim($region_name);
				}

				return $region_name;
			} else {

				return null;
			}
		}

        public function getCityByGeoPoint(GeoPoint $geo_point)
        {
            $this->getApiDataByGeoPointAndKind($geo_point, 'locality');

            $results = $this->api_data['response']['GeoObjectCollection']['featureMember'];
            if (count($this->api_data['response']['GeoObjectCollection']['featureMember']))
            {
                $name = $results[0]['GeoObject']['name'];

                return $name;
            } else {
                return NULL;
            }
        }

        private function getApiDataByGeoPointAndKind(GeoPoint $geo_point, $kind)
        {
            $url = $this->url_template.'&kind='.$kind;
            $url = str_replace('%name%',urlencode($geo_point->getLongitude().','.$geo_point->getLatitude()), $url);


            $api_data = file_get_contents($url);
            $api_data = json_decode($api_data, true);

            $this->api_data = $api_data;

        }

        public function getCityNameByGeoPoint(GeoPoint $geo_point)
        {
            $url = $this->url_template.'&kind=district';
            $url = str_replace('%name%',urlencode($geo_point->getLongitude().','.$geo_point->getLatitude()), $url);

            $api_data = file_get_contents($url);
            $api_data = json_decode($api_data, true);

            $this->api_data = $api_data;

            $results = $this->api_data['response']['GeoObjectCollection']['featureMember'];
            if (count($this->api_data['response']['GeoObjectCollection']['featureMember']))
            {
                $name = $results[0]['GeoObject']['name'];

                if ($results[0]['GeoObject']['metaDataProperty']['GeocoderMetaData']['AddressDetails']['Country']['AdministrativeArea']['Locality']['LocalityName'] != $name)
                    $name = $results[0]['GeoObject']['metaDataProperty']['GeocoderMetaData']['AddressDetails']['Country']['AdministrativeArea']['Locality']['LocalityName'];

                return $name;
            } else {
                return NULL;
            }
        }
    }