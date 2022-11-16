<?php
    class KladrApi
    {
        private $address = '';
        private $city = '';
        private $street = '';
        private $building = '';
        private $api = '';

        public function __construct()
        {
            // Инициализация api, в качестве параметров указываем токен и ключ для доступа к сервису
            $this->api = new Kladr(SettingsManager::get('kladr_api_token'), SettingsManager::get('kladr_api_key'));
        }

        public function getPostCodeByCityAndAddress($city, $address)
        {
            $this->address = $address;
            $this->city = $city;

            $post_index = '';

            $address_data = explode(",", $address);
            if ($address_data) {
                if (isset($address_data[0]) && $address_data[0]) {
                    $this->street = $address_data[0];
                }
                else {
                    return self::processError('Для получения индекса формат адреса должен соответствовать [Улица],[Дом]');
                }
                if (isset($address_data[1]) && $address_data[1]) {
                    $this->building = $address_data[1];
                }
                else {
                    return self::processError('Для получения индекса формат адреса должен соответствовать [Улица],[Дом]');
                }
                if (isset($address_data[2])) {
                    return self::processError('Для получения индекса формат адреса должен соответствовать [Улица],[Дом]');
                }
            }

            self::formatAddress();

            $city_data = self::getCityByCityName($city);

            if ($city_data && $city_data[0]['id'] && $this->street)
            {
                $street_data = self::getStreetByCityIdAndStreetName((string)$city_data[0]['id'], $this->street);

                if ($street_data && $street_data[0]['id'] && $this->building)
                {
                    $building_data = self::getBuildingByStreetIdAndBuildingName((string)$street_data[0]['id'], $this->building);

                    if ($building_data && $building_data[0]['zip'])
                    {
                        $post_index = $building_data[0]['zip'];
                    }
                    else {
                        return self::processError('Не удалось получить индекс по указанному номеру дома');
                    }
                }
                else {
                    return self::processError('Не удалось получить данные по указанной улице');
                }
            }
            else {
                return self::processError('Не удалось получить данные по указанному городу');
            }

            return self::processResponse($post_index);
        }

        public function getCityByCityName($city)
        {
            $query = new Query();
            $query->ContentType = KladrObjectType::City;
            $query->ContentName = $city;
            $query->WithParent = false;
            $query->Limit = 1;

            $arResult = $this->api->QueryToArray($query);
            return $arResult ? $arResult : array();
        }

        public function getStreetByCityIdAndStreetName($city_id, $street)
        {
            $query = new Query();
            $query->ParentId = $city_id;
            $query->ParentType = KladrObjectType::City;
            $query->ContentName = $street;
            $query->ContentType = KladrObjectType::Street;
            $query->WithParent = false;
            $query->Limit = 1;

            $arResult = $this->api->QueryToArray($query);
            return $arResult ? $arResult : array();
        }

        public function getBuildingByStreetIdAndBuildingName($street_id, $building)
        {
            $query = new Query();
            $query->ParentId = $street_id;
            $query->ParentType = KladrObjectType::Street;
            $query->ContentName = $building;
            $query->ContentType = KladrObjectType::Building;
            $query->WithParent = false;
            $query->Limit = 1;

            $arResult = $this->api->QueryToArray($query);
            return $arResult ? $arResult : array();
        }

        public function formatAddress()
        {
            $this->street = str_replace('переулок', '', $this->street);
            $this->street = str_replace('Переулок', '', $this->street);
            $this->street = str_replace('бульвар', '', $this->street);
            $this->street = str_replace('Бульвар', '', $this->street);
            $this->street = str_replace('бул.', '', $this->street);
            $this->street = str_replace('улица', '', $this->street);
            $this->street = str_replace('Улица', '', $this->street);
            $this->street = str_replace('ул.', '', $this->street);
            $this->street = str_replace('Ул.', '', $this->street);
            $this->street = str_replace('ул', '', $this->street);
            $this->street = str_replace('Ул', '', $this->street);
            $this->street = str_replace('проспект', '', $this->street);
            $this->street = str_replace('Проспект', '', $this->street);
            $this->street = str_replace('просп.', '', $this->street);
            $this->street = str_replace('пр-т', '', $this->street);
            $this->street = str_replace('пр.', '', $this->street);
            $this->street = str_replace('аллея', '', $this->street);
            $this->street = str_replace('Аллея', '', $this->street);
            $this->street = str_replace('шоссе', '', $this->street);
            $this->street = str_replace('Шоссе', '', $this->street);
            $this->street = str_replace('ш.', '', $this->street);
            $this->street = str_replace('набережная', '', $this->street);
            $this->street = str_replace('Набережная', '', $this->street);
            $this->street = str_replace('наб.', '', $this->street);
            $this->street = str_replace('проезд', '', $this->street);
            $this->street = str_replace('Проезд', '', $this->street);
            $this->street = str_replace('пер.', '', $this->street);
            $this->street = str_replace('пер', '', $this->street);
            $this->street = str_replace('площадь', '', $this->street);
            $this->street = str_replace('пл.', '', $this->street);
            $this->street = str_replace('тракт', '', $this->street);

            $this->street = trim($this->street);

            $counter_matches_array = array('1-я','2-я','3-я','4-я','5-я','6-я','7-я','8-я','9-я','10-я','11-я','12-я','13-я','14-я','15-я','16-я','17-я','1-й','2-й','3-й','4-й','5-й','6-й','7-й','8-й','9-й','10-й','11-й','12-й','13-й','14-й','15-й','16-й','17-й');
            foreach ($counter_matches_array as $single_match) {
                preg_match('/'.$single_match.'/i', $this->street, $match);
                if (isset($match[0]))
                {
                    $this->street = str_replace($single_match, '', $this->street);
                    $this->street.= ' '.$match[0];
                    break;
                }
            }

            $address_words = explode(' ', $this->street);
            if (count($address_words) > 1) {
                $counter_matches_array = array('Большая','Большой','Малая','Малый');
                $counter = 0;
                foreach ($counter_matches_array as $single_match) {
                    $counter++;
                    preg_match('/'.$single_match.'/i', $this->street, $match);
                    if (isset($match[0]))
                    {
                        $this->street = str_replace($single_match, '', $this->street);
                        if ($counter > 2) {
                            $this->street.= ' М';
                        }
                        else {
                            $this->street.= ' Б';
                        }
                        break;
                    }
                }
            }

            $this->building = str_replace('с', 'стр', $this->building);
            $this->building = str_replace('дом', '', $this->building);
            $this->building = str_replace('д.', '', $this->building);

            $this->building = trim($this->building);

            if (strpos($this->building, '/'))
                $this->building = (int)$this->building;
        }

        public function processError($text)
        {
            return array('status' => '2', 'error' => $text);
        }

        public function processResponse($post_index)
        {
            return array('status' => '0', 'result' => $post_index);
        }
    }