<?php
	class RegionManager extends AliasManager
	{
		protected $table_name = 'region';
		protected $model_name = 'RegionModel';

		protected $transliterated_field = 'full_name';

        /**
		 * @return RegionModel
		 */
		public function getOneByName($name){
			$data = $this->orm_model->select()->where('name = ?', $name)->fetchOne();
			return $this->initOne($data);
		}

        /**
         * @return RegionModel
         * @param $name
         * @param $cityId
         * @return mixed|null
         */
        public function getOneByNameAndCityId($name, $cityId){
            $data = $this->orm_model
                ->select()
                ->join('district d', 'd.id=region.district_id', 'INNER')
                ->where('region.name = ? AND d.city_id = ?', $name, $cityId)->fetchOne();
            return $this->initOne($data);
        }

        /**
		 * @return RegionModel[]
		 */
		public function getListByCityId($city_id){

		    $data = $this->orm_model->select()->join('district d', 'd.id=region.district_id', 'INNER')->where('city_id = ?', $city_id)->fetchAll();
			return $this->initList($data);
		}

		/**
		 * @return RegionModel[]
		 */
		public function getListByStreetId($street_id)
		{
			$sql = 'SELECT *
					FROM region r
					WHERE EXISTS (
						SELECT *
						FROM street_to_region
						WHERE region_id = r.id
							AND street_id = ' . (int)$street_id . '
					)';

			$data = $this->db->query($sql);
			return $this->initList($data);
		}

		/**
		 * @return RegionModel[]
		 */
		public function getListByDistrictId($district_id)
		{
			$data = $this->orm_model->select()->where('district_id = ?', $district_id)->fetchAll();
			return $this->initList($data);
		}

		public function getHavingDoctorsList()
		{
			$sql = 'SELECT *
					FROM region r
					WHERE EXISTS (
							SELECT *
							FROM doctor dc
							INNER JOIN doctor_to_clinic d2c ON d2c.doctor_id = dc.id
							INNER JOIN clinic c ON c.id = d2c.clinic_id
							WHERE
								c.region_id = r.id
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
					FROM region r
					WHERE EXISTS (
							SELECT *
							FROM doctor dc
							INNER JOIN doctor_to_clinic d2c ON d2c.doctor_id = dc.id
							INNER JOIN clinic c ON c.id = d2c.clinic_id
							WHERE
								c.region_id = r.id
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

		public function getHavingDoctorsListBySpecialtyIdAndDistrictId($specialty_id, $district_id)
		{
			$sql = 'SELECT *
					FROM region r
					WHERE r.district_id = ' . (int)$district_id . '
						AND EXISTS (
							SELECT *
							FROM doctor dc
							INNER JOIN doctor_to_clinic d2c ON d2c.doctor_id = dc.id
							INNER JOIN doctor_specialty_to_clinic ds2c ON ds2c.doctor_id = dc.id
							INNER JOIN clinic c ON c.id = d2c.clinic_id
							WHERE
								c.region_id = r.id
								AND ds2c.clinic_id = c.id
								AND dc.is_active = 1
								AND c.is_active = 1
								AND ds2c.specialty_id = ' . (int)$specialty_id . '
						)
					ORDER BY `name`';

			$data = $this->db->query($sql);
			return $this->initList($data);
		}

        /**
         * @param $specialization_id
         * @param $district_id
         * @return RegionModel[]
         */
        public function getHavingClinicListBySpecializationIdAndDistrictId($specialization_id, $district_id)
        {
            $sql = 'SELECT DISTINCT r.* 
                    FROM specialization_to_clinic stc INNER JOIN clinic c ON stc.clinic_id=c.id 
                    INNER JOIN region r ON c.region_id=r.id INNER JOIN district d ON r.district_id=d.id
                    WHERE c.is_active AND stc.specialization_id = ' . (int) $specialization_id . '
                     AND d.id = ' . (int)$district_id . ';            
            ';

            $data = $this->db->query($sql);
            return $this->initList($data);
        }

		public function getHavingClinicListByTypeOrService($item_id, $district_id, $type)
		{
            $tables = ClinicHelper::getDataToLinkTheTables($type);

            if(empty($tables)) return array();

			$sql = 'SELECT *
					FROM ' . $this->table_name . ' AS r
					WHERE
					  r.district_id = ' . (int)$district_id . ' AND
					  EXISTS (
					    SELECT *
						FROM `clinic`                                                 AS cl
                          INNER JOIN `' . $tables['table_name_join'] . '`             AS j  ON j.' . $tables['page_id'] . ' = cl.id
                          INNER JOIN `' . $tables['table_name_for_landing_page'] . '` AS lp ON lp.id = j.' . $tables['join_id'] . '
					    WHERE
						  cl.is_active = 1    AND
						  cl.region_id = r.id AND
						  j.' . $tables['join_id'] . ' = ' . (int)$item_id . '
					  )
					ORDER BY `name`';

			$data = $this->db->query($sql);

            return $data ? $this->initList($data) : array();
		}
	}
