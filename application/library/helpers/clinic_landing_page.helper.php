<?php
    class ClinicLandingPageHelper
    {

        protected static function getTablesName($landing_page)
        {
            $tables = array();

            if(get_class($landing_page) == 'ClinicServicesModel')
            {
                $tables['table_name_for_landing_page'] = 'clinic_services';
                $tables['table_name_join'] = 'clinic_to_services';
                $tables['page_id'] = 'clinic_id';
                $tables['join_id'] = 'clinic_service_id';
            }
            else if(get_class($landing_page) == 'ClinicTypeModel')
            {
                $tables['table_name_for_landing_page'] = 'clinic_type';
                $tables['table_name_join'] = 'clinic_to_types';
                $tables['page_id'] = 'clinic_id';
                $tables['join_id'] = 'clinic_type_id';
            }

            if(!empty($tables['table_name_for_landing_page']) && !empty($tables['table_name_join']))
            {
                return $tables;
            }
            else
            {
                return array();
            }
        }

        public function checkExistsBySpecialtyIdAndCityId($landing_page, $city_id)
        {
            $landing_page_alias = $landing_page->alias;
            $data = array();

            $tables = self::getTablesName($landing_page);

            if(!empty($tables['table_name_for_landing_page']) && !empty($tables['table_name_join']) && !empty($landing_page_alias))
            {

                $db = Register::get('db');
                $city_manager = ModelManagerFactory::getByName('city');
                $city = $city_manager->getOneById($city_id);

                $sql = 'SELECT COUNT(*) AS count
                    FROM `clinic` AS c
                    INNER JOIN `' . $tables['table_name_join'] . '` AS j ON j.' . $tables['page_id'] . ' = c.id
                    INNER JOIN `' . $tables['table_name_for_landing_page'] . '` AS lp ON lp.id = j.' . $tables['join_id'] . '
					WHERE
						lp.alias = "' . $landing_page_alias . '"
						AND (c.city_id = ' . (int)$city_id . '
									';
                if ($city && $city->region == 'Московская область')
                {
                    $sql .= ' OR c.city_id = ' . (int)CityModel::MOSCOW_ID;
                }
                $sql .= ' )

            ';
                $sql .= '
            		LIMIT 1';

                $data = $db->query($sql);
            }

            return !empty($data) ? (bool)$data[0]['count'] : false;
        }

        public function checkExistsBySpecialtyIdAndStreetId($landing_page, $street_id)
        {
            $landing_page_alias = $landing_page->alias;
            $data = array();

            $tables = self::getTablesName($landing_page);

            if(!empty($tables['table_name_for_landing_page']) && !empty($tables['table_name_join']) && !empty($landing_page_alias))
            {
                $db = Register::get('db');

                $sql = 'SELECT COUNT(*) AS count
                        FROM `clinic` AS c
                        INNER JOIN `' . $tables['table_name_join'] . '` AS j ON j.' . $tables['page_id'] . ' = c.id
                        INNER JOIN `' . $tables['table_name_for_landing_page'] . '` AS lp ON lp.id = j.' . $tables['join_id'] . '
                        WHERE
                            lp.alias = "' . $landing_page_alias . '" AND
						    c.street_id = ' . (int)$street_id . ' AND
                            c.is_active = 1';

                $data = $db->query($sql);
            }

            return !empty($data) ? (bool)$data[0]['count'] : false;
        }

        public function checkExistsBySpecialtyIdAndRegionId($landing_page, $region_id)
        {
            $landing_page_alias = $landing_page->alias;
            $data = array();

            $tables = self::getTablesName($landing_page);

            if(!empty($tables['table_name_for_landing_page']) && !empty($tables['table_name_join']) && !empty($landing_page_alias))
            {
                $db = Register::get('db');

                $sql = 'SELECT COUNT(*) AS count
                        FROM `clinic` AS c
                        INNER JOIN `' . $tables['table_name_join'] . '` AS j ON j.' . $tables['page_id'] . ' = c.id
                        INNER JOIN `' . $tables['table_name_for_landing_page'] . '` AS lp ON lp.id = j.' . $tables['join_id'] . '
                        WHERE
                            lp.alias = "' . $landing_page_alias . '" AND
						    c.region_id = ' . (int)$region_id . ' AND
                            c.is_active = 1
					    LIMIT 1';

                $data = $db->query($sql);
            }

            return !empty($data) ? (bool)$data[0]['count'] : false;
        }

        public function checkExistsBySpecialtyIdAndDistrictId($landing_page, $district_id)
        {
            $landing_page_alias = $landing_page->alias;
            $data = array();

            $tables = self::getTablesName($landing_page);

            if(!empty($tables['table_name_for_landing_page']) && !empty($tables['table_name_join']) && !empty($landing_page_alias))
            {
                $db = Register::get('db');

                $sql = 'SELECT COUNT(*) AS count
                        FROM `clinic` AS c
                        INNER JOIN `' . $tables['table_name_join'] . '` AS j ON j.' . $tables['page_id'] . ' = c.id
                        INNER JOIN `' . $tables['table_name_for_landing_page'] . '` AS lp ON lp.id = j.' . $tables['join_id'] . '
                        WHERE
                            (
                                SELECT COUNT(*)
                                FROM region r
                                WHERE c.region_id = r.id
                                    AND r.district_id = ' . (int)$district_id . '
                            ) > 0 AND
                            lp.alias = "' . $landing_page_alias . '" AND
                            c.is_active = 1
					    LIMIT 1';

                $data = $db->query($sql);
            }

            return !empty($data) ? (bool)$data[0]['count'] : false;
        }

        public function checkExistsBySpecialtyIdAndMetroStationId($landing_page, $metro_station_id)
        {
            $landing_page_alias = $landing_page->alias;
            $data = array();

            $tables = self::getTablesName($landing_page);

            if(!empty($tables['table_name_for_landing_page']) && !empty($tables['table_name_join']) && !empty($landing_page_alias))
            {
                $db = Register::get('db');

                $sql = 'SELECT COUNT(*) AS count
                        FROM `clinic` AS c
                        INNER JOIN `' . $tables['table_name_join'] . '` AS j ON j.' . $tables['page_id'] . ' = c.id
                        INNER JOIN `' . $tables['table_name_for_landing_page'] . '` AS lp ON lp.id = j.' . $tables['join_id'] . '
                        WHERE
						    c.metro_station_id = ' . (int)$metro_station_id . ' AND
                            lp.alias = "' . $landing_page_alias . '" AND
                            c.is_active = 1
					    LIMIT 1';

                $data = $db->query($sql);
            }

            return !empty($data) ? (bool)$data[0]['count'] : false;
        }

        public function checkExistsBySpecialtyIdAddressObject($landing_page, DynamicModel $address_object)
        {
            switch (get_class($address_object))
            {
                case 'CityModel':
                    return $this->checkExistsBySpecialtyIdAndCityId($landing_page, $address_object->getId());
                    break;
                case 'DistrictModel':
                    return $this->checkExistsBySpecialtyIdAndDistrictId($landing_page, $address_object->getId());
                    break;
                case 'RegionModel':
                    return $this->checkExistsBySpecialtyIdAndRegionId($landing_page, $address_object->getId());
                    break;
                case 'MetroStationModel':
                    return $this->checkExistsBySpecialtyIdAndMetroStationId($landing_page, $address_object->getId());
                    break;
                case 'StreetModel':
                    return $this->checkExistsBySpecialtyIdAndStreetId($landing_page, $address_object->getId());
                    break;
            }

            return false;
        }
    }