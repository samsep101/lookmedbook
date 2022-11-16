<?php
	class MetroStationManager extends AliasManager
	{
		protected $table_name = 'metro_station';
		protected $model_name = 'MetroStationModel';

		protected $transliterated_field = 'seo_name';

        /**
         * @param $metro_branch_id
         * @return MetroStationModel[]
         */
        public function getListByMetroBranchId($metro_branch_id)
		{
			$data = $this->orm_model->select()->where('metro_branch_id = ?', $metro_branch_id)->fetchAll();
			return $this->initList($data);
		}

        /**
         * @return MetroStationModel[]
         */
        public function getListByCityId($city_id)
        {
            $sql = 'SELECT ms.*
					FROM metro_station ms
					INNER JOIN metro_branch mb ON ms.metro_branch_id = mb.id
					INNER JOIN metro m ON m.id = mb.metro_id
					WHERE m.city_id = ' . (int)$city_id . '
					ORDER BY ms.name';

            $data = $this->db->query($sql);
            return $this->initList($data);
        }

		public function getHavingDoctorsList()
		{
			$sql = 'SELECT *
					FROM metro_station ms
					WHERE EXISTS (
							SELECT *
							FROM doctor dc
							INNER JOIN doctor_to_clinic d2c ON d2c.doctor_id = dc.id
							INNER JOIN clinic c ON c.id = d2c.clinic_id
							WHERE
								c.metro_station_id = ms.id
								AND dc.is_active = 1
						)
					ORDER BY `name`';

			$data = $this->db->query($sql);
			return $this->initList($data);
		}

		public function getHavingDoctorsListByCityId($city_id)
		{
            $data = array();
            $city_id = intval($city_id);

            if($city_id) {
                $sql = 'SELECT *
                        FROM metro_station ms
                        WHERE EXISTS (
                                SELECT *
                                FROM doctor dc
                                INNER JOIN doctor_to_clinic d2c ON d2c.doctor_id = dc.id
                                INNER JOIN clinic c ON c.id = d2c.clinic_id
                                WHERE
                                    c.metro_station_id = ms.id
                                    AND dc.is_active = 1
                                    AND c.is_active = 1
                                    AND c.city_id = ' . $city_id . '
                                    AND dc.first_name IS NOT NULL
                                    AND dc.second_name IS NOT NULL
                                    AND dc.last_name IS NOT NULL
                            )
                        ORDER BY `name`';

                $data = $this->db->query($sql);
            }

			return count($data) > 0 ? $this->initList($data) : array();
		}

        public function getHavingDoctorsListBySpecialtyIdAndCityId($specialty_id, $city_id)
        {
            $sql = '
            SELECT
              DISTINCT ms.*
            FROM clinic c
              INNER JOIN doctor_specialty_to_clinic ds2c ON ds2c.clinic_id=c.id
              INNER JOIN doctor doc ON ds2c.doctor_id = doc.id
              INNER JOIN metro_station_to_clinic mstc ON mstc.clinic_id=c.id
              INNER JOIN metro_station ms ON ms.id=mstc.metro_station_id
              INNER JOIN region r ON c.region_id=r.id AND ms.region_id=r.id
              
            WHERE c.is_active=1 AND doc.is_active=1 AND ds2c.specialty_id = '  . (int) $specialty_id .  ' 
              AND c.city_id = ' . (int)$city_id . ';
            ';

            $data = $this->db->query($sql);
            return $this->initList($data);
        }

		public function getHavingDoctorsListBySpecialtyIdAndDistrictId($specialty_id, $district_id)
		{
			$sql = 'SELECT DISTINCT ms.*
                    FROM metro_station ms
                    INNER JOIN metro_station_to_clinic mstc ON mstc.metro_station_id=ms.id
                    INNER JOIN clinic c ON c.id=mstc.clinic_id
                    INNER JOIN region r ON ms.region_id = r.id AND c.region_id=r.id
                    INNER JOIN district d ON r.district_id = d.id
                    INNER JOIN doctor_specialty_to_clinic ds2c ON ds2c.clinic_id = c.id
                    INNER JOIN doctor doc ON ds2c.doctor_id = doc.id
                    WHERE ds2c.specialty_id = ' .(int)$specialty_id .'
                    AND d.id = ' .(int)$district_id .'
                    AND doc.is_active = 1
                    AND c.is_active = 1
                    ORDER BY ms.name';

			$data = $this->db->query($sql);
			return $this->initList($data);
		}

        /**
         * @param $specialization_id
         * @param $city_id
         * @return MetroStationModel[]
         */
        public function getHavingClinicListBySpecializationIdAndCityId($specialization_id, $city_id)
        {
            $sql = '
            SELECT
              DISTINCT ms.*
            FROM specialization_to_clinic stc
              INNER JOIN clinic c ON stc.clinic_id=c.id
              INNER JOIN metro_station_to_clinic mstc ON mstc.clinic_id=c.id
              INNER JOIN metro_station ms ON ms.id=mstc.metro_station_id
              INNER JOIN region r ON c.region_id=r.id AND ms.region_id=r.id
              INNER JOIN district d ON r.district_id=d.id
            WHERE c.is_active=1 AND stc.specialization_id = '  . (int) $specialization_id .  ' 
              AND c.city_id = ' . (int)$city_id . ';
            ';

            $data = $this->db->query($sql);
            return $this->initList($data);
        }

        /**
         * @param $specialization_id
         * @param $district_id
         * @return MetroStationModel[]
         */
        public function getHavingClinicListBySpecializationIdAndDistrictId($specialization_id, $district_id)
        {
            $sql = '
            SELECT
              DISTINCT ms.*
            FROM specialization_to_clinic stc
              INNER JOIN clinic c ON stc.clinic_id=c.id
              INNER JOIN metro_station_to_clinic mstc ON mstc.clinic_id=c.id
              INNER JOIN metro_station ms ON ms.id=mstc.metro_station_id
              INNER JOIN region r ON c.region_id=r.id AND ms.region_id=r.id
              INNER JOIN district d ON r.district_id=d.id
            WHERE c.is_active=1 AND stc.specialization_id = '  . (int) $specialization_id .  ' 
              AND d.id = ' . (int)$district_id . ';
            ';

            $data = $this->db->query($sql);
            return $this->initList($data);
        }

		public function getHavingClinicListByTypeOrService($item_id, $district_id, $type)
		{
            $tables = ClinicHelper::getDataToLinkTheTables($type);

            if(empty($tables)) return array();

			$sql = 'SELECT DISTINCT ms.*
                    FROM metro_station ms
                      INNER JOIN clinic                                           AS c  ON c.metro_station_id = ms.id
                      INNER JOIN `' . $tables['table_name_join'] . '`             AS j  ON j.' . $tables['page_id'] . ' = c.id
                      INNER JOIN `' . $tables['table_name_for_landing_page'] . '` AS lp ON lp.id = j.' . $tables['join_id'] . '
                      INNER JOIN region                                           AS r  ON c.region_id = r.id
                      INNER JOIN district                                         AS d  ON r.district_id = d.id
                    WHERE
                      j.' . $tables['join_id'] . ' = ' . (int)$item_id . ' AND
                      d.id = ' .(int)$district_id . '                      AND
                      c.is_active = 1
                    ORDER BY ms.name';

			$data = $this->db->query($sql);

            return $data ? $this->initList($data) : array();
		}

		public function getHavingDoctorsListBySpecialtyIdAndRegionId($specialty_id, $region_id)
		{
			$sql = 'SELECT DISTINCT ms.*
                    FROM metro_station ms
                    INNER JOIN metro_station_to_clinic mstc ON mstc.metro_station_id=ms.id
                    INNER JOIN clinic c ON c.id=mstc.clinic_id
                    INNER JOIN region r ON ms.region_id = r.id AND c.region_id=r.id
                    INNER JOIN district d ON r.district_id = d.id
                    INNER JOIN doctor_specialty_to_clinic ds2c ON ds2c.clinic_id = c.id
                    INNER JOIN doctor doc ON ds2c.doctor_id = doc.id
                    WHERE ds2c.specialty_id = ' .(int)$specialty_id .'
                    AND r.id = ' .(int)$region_id .'
                    AND doc.is_active = 1
                    AND c.is_active = 1
                    ORDER BY ms.name';

			$data = $this->db->query($sql);
			return $this->initList($data);
		}

        /**
         * @param $specialization_id
         * @param $region_id
         * @return MetroStationModel[]
         */
        public function getHavingClinicListBySpecializationIdAndRegionId($specialization_id, $region_id)
        {
            $sql = 'SELECT DISTINCT ms.* 
                    FROM specialization_to_clinic stc 
                    INNER JOIN clinic c ON stc.clinic_id=c.id 
                    INNER JOIN region r ON c.region_id=r.id 
                    INNER JOIN metro_station_to_clinic mstc ON mstc.clinic_id=c.id
                    INNER JOIN metro_station ms ON r.id=ms.region_id AND mstc.metro_station_id=ms.id
                    WHERE c.is_active AND stc.specialization_id = ' . (int) $specialization_id . '
                     AND r.id = ' . (int)$region_id . ';            
            ';

            $data = $this->db->query($sql);
            return $this->initList($data);
        }


        public function getHavingClinicListByTypeOrServiceForRegion($item_id, $region_id, $type)
        {
            $tables = ClinicHelper::getDataToLinkTheTables($type);

            if(empty($tables)) return array();

            $sql = 'SELECT DISTINCT ms.*
                    FROM ' . $this->table_name . '                                AS ms
                      INNER JOIN clinic                                           AS c  ON c.metro_station_id = ms.id
                      INNER JOIN `' . $tables['table_name_join'] . '`             AS j  ON j.' . $tables['page_id'] . ' = c.id
                      INNER JOIN `' . $tables['table_name_for_landing_page'] . '` AS lp ON lp.id = j.' . $tables['join_id'] . '
                      INNER JOIN region                                           AS r  ON ms.region_id = r.id
                      INNER JOIN district                                         AS d  ON r.district_id = d.id
                    WHERE
                      j.' . $tables['join_id'] . ' = ' . (int)$item_id . ' AND
                      r.id = ' .(int)$region_id . '                        AND
                      c.is_active = 1
                    ORDER BY ms.name';

            $data = $this->db->query($sql);

            return $data ? $this->initList($data) : array();
        }

		/**
		* @return MetroStationModel[]
		 */
		public function getListByName($name)
		{
			$data = $this->orm_model->select()->where('name = ?', $name)->fetchAll();
			return $this->initList($data);
		}

		/**
		* @return MetroStationModel[]
		 */
		public function getListByRegionId($region_id)
		{
			$data = $this->orm_model->select()->where('region_id = ?', $region_id)->fetchAll();
			return $this->initList($data);
		}

        /**
		 * @return MetroStationModel
		 */
		public function getOneByNameAndMetroBranchId($name, $metro_branch_id)
		{
			$data = $this->orm_model->select()->where('name = ? AND metro_branch_id = ?', $name, $metro_branch_id)->fetchOne();
			return $this->initOne($data);
		}

        /**
         * @param $name
         * @param $cityId
         * @return MetroStationModel
         * @throws Exception
         */
        public function getOneByNameAndCityId($name, $cityId)
        {
            $sql = 'SELECT ms.* FROM metro_station ms INNER JOIN metro_branch mb ON ms.metro_branch_id=mb.id '
                . ' INNER JOIN metro m ON mb.metro_id=m.id '
                . ' WHERE ms.name = ? and m.city_id = ?';
            $data = $this->db->query($sql, [$name, $cityId]);
            return $data ? $this->initOne($data[0]) : null;
        }

        /**
         * @param string $name
         * @param int|string $cityId
         * @return MetroStationModel[]
         * @throws Exception
         */
        public function getListByNameSampleAndCityId($name, $cityId)
        {
            $sql = 'SELECT ms.* FROM metro_station ms INNER JOIN metro_branch mb ON ms.metro_branch_id=mb.id '
                . ' INNER JOIN metro m ON mb.metro_id=m.id '
                . ' WHERE ms.name LIKE ? and m.city_id = ?';
            $name .= '%';
            $data = $this->db->query($sql, [$name, $cityId]);
            return $data ? $this->initList($data) : [];
        }


        /**
         * @param string $name
         * @param string $branchName
         * @return MetroStationModel|null
         */
        public function getOneByNameAndBranchName($name, $branchName)
        {
            $data = $this->db->query('
                select s.*
                from metro_station s
                  join metro_branch b on s.metro_branch_id = b.id
                where s.name = ?
                  and b.name = ?
            ', [$name, $branchName]);
            return $data ? $this->initOne($data[0]) : null;
        }

		/**
		 * @return MetroStationModel[]
		 */
		public function getListByMetroBranchIdAndNumberRange($metro_branch_id, $number_from, $number_to)
		{
			$data = $this->orm_model->select()->where('metro_branch_id = ? AND number >= ? AND number <= ?', $metro_branch_id, $number_from, $number_to)->fetchAll();
			return $this->initList($data);
		}

        /**
         * @param int $main_station_id
         * @param int|null $exclude
         * @return MetroStationModel[]
         */
        public function getSameNameStations($main_station_id, $exclude = null)
        {
            return $this->initList(
                $this->orm_model
                    ->select()
                    ->where('main_station_id = ? AND id <> ?', $main_station_id, $exclude)
                    ->fetchAll()
            );
        }
	}
