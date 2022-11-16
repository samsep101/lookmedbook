<?php
	class DistrictManager extends AliasManager
	{
		protected $table_name = 'district';
		protected $model_name = 'DistrictModel';

		protected $transliterated_field = 'formal_name';

		/**
		 * @return DistrictModel
		 */
		public function getOneByName($name)
		{
			$data = $this->orm_model->select()->where('name = ?', $name)->fetchOne();
			return $this->initOne($data);
		}

		/**
		 * @return DistrictModel[]
		 */
		public function getListByCityId($city_id)
		{
			return $this->initList($this->orm_model->select()->where('city_id = ?', $city_id)->fetchAll());
		}

		public function getHavingDoctorsListBySpecialtyIdAndCityId($specialty_id, $city_id)
		{
			$sql = 'SELECT *
					FROM ' . $this->table_name . ' d
					WHERE d.city_id = ' . (int)$city_id . '
						AND EXISTS (
							SELECT *
							FROM doctor dc
							INNER JOIN doctor_to_clinic d2c ON d2c.doctor_id = dc.id
							INNER JOIN doctor_specialty_to_clinic ds2c ON ds2c.doctor_id = dc.id
							INNER JOIN clinic c ON c.id = d2c.clinic_id
							WHERE
								(	SELECT COUNT(*)
								 	FROM region r
								 	WHERE c.region_id = r.id
								 		AND r.district_id = d.id) > 0
								AND ds2c.clinic_id = c.id
								AND ds2c.specialty_id = ' . (int)$specialty_id . '
								AND dc.is_active = 1
								AND c.is_active = 1
						)

					ORDER BY `name`
					';

			$data = $this->db->query($sql);
			return $this->initList($data);
		}

        /**
         * @param $specialization_id
         * @param $city_id
         * @return DistrictModel[]
         */
		public function getHavingClinicListBySpecializationIdAndCityId($specialization_id, $city_id)
        {
            $sql = 'SELECT DISTINCT d.* 
                    FROM specialization_to_clinic stc INNER JOIN clinic c ON stc.clinic_id=c.id 
                    INNER JOIN region r ON c.region_id=r.id INNER JOIN district d ON r.district_id=d.id
                    WHERE c.is_active AND stc.specialization_id = ' . (int) $specialization_id . '
                     AND d.city_id = ' . (int)$city_id . ';            
            ';

            $data = $this->db->query($sql);
            return $this->initList($data);
        }

		public function getHavingClinicListByTypeOrServiceId($item_id, $city_id, $type)
		{
            $tables = ClinicHelper::getDataToLinkTheTables($type);

            if(empty($tables)) return array();

			$sql = 'SELECT DISTINCT d.*
				    FROM `clinic`                                                 AS cl
					  INNER JOIN `' . $tables['table_name_join'] . '`             AS j  ON j.' . $tables['page_id'] . ' = cl.id
                      INNER JOIN `' . $tables['table_name_for_landing_page'] . '` AS lp ON lp.id = j.' . $tables['join_id'] . '
					  INNER JOIN `region`                                         AS r  ON cl.region_id = r.id
					  INNER JOIN `district`                                       AS d  ON r.district_id = d.id
				    WHERE
					  r.district_id = d.id                                 AND
					  cl.is_active = 1                                     AND
				      j.' . $tables['join_id'] . ' = ' . (int)$item_id . ' AND
					  cl.city_id = ' . (int)$city_id . '
					ORDER BY `name`';

			$data = $this->db->query($sql);

			return $data ? $this->initList($data) : array();
		}

		public function getHavingDoctorsList()
		{
			$sql = 'SELECT *
					FROM ' . $this->table_name . ' d
					WHERE  EXISTS (
							SELECT *
							FROM doctor dc
							INNER JOIN doctor_to_clinic d2c ON d2c.doctor_id = dc.id
							INNER JOIN clinic c ON c.id = d2c.clinic_id
							WHERE
								(	SELECT COUNT(*)
								 	FROM region r
								 	WHERE c.region_id = r.id
								 		AND r.district_id = d.id) > 0

						)
					ORDER BY `name`
					';

			$data = $this->db->query($sql);
			return $this->initList($data);
		}

		public function getHavingDoctorsListByCityId($city_id)
		{
            $data = array();
            $city_id = intval($city_id);

            if($city_id) {
                $sql = 'SELECT dstrct.*
                        FROM ' . $this->table_name . ' AS dstrct
                        WHERE EXISTS(
                            SELECT *
                                FROM doctor AS dctr
                                INNER JOIN doctor_to_clinic d2c ON d2c.doctor_id = dctr.id
                                INNER JOIN clinic AS clnc ON clnc.id = d2c.clinic_id
                                INNER JOIN region AS rgn ON clnc.region_id = rgn.id
                                WHERE dctr.is_active = 1
                                    AND dctr.first_name IS NOT NULL
                                    AND dctr.second_name IS NOT NULL
                                    AND dctr.last_name IS NOT NULL
                                    AND clnc.city_id = ' . $city_id . '
                        )
					';

                $data = $this->db->query($sql);
            }

            return count($data) > 0 ? $this->initList($data) : array();
		}

        public function getOneByClinicId($clinic_id)
        {
            $sql = 'SELECT d.*
                    FROM district d
                    INNER JOIN region r ON r.district_id = d.id
                    INNER JOIN clinic c ON c.region_id = r.id
                    WHERE c.id = ' .(int)$clinic_id;

            $data = $this->db->query($sql);

            return ($data) ? $this->initOne($data[0]) : null;
        }

        public function getOneByDoctorId($doctor_id)
        {
            $sql = 'SELECT ds.*
                    FROM district ds
                    INNER JOIN region r ON r.district_id = ds.id
                    INNER JOIN clinic c ON c.region_id = r.id
                    INNER JOIN doctor_specialty_to_clinic ds2c ON ds2c.clinic_id = c.id
                    WHERE ds2c.doctor_id = ' .(int)$doctor_id;

            $data = $this->db->query($sql);

            return ($data) ? $this->initOne($data[0]) : null;
        }

        public function getListOfDoctorDistrictsByDoctorId($doctor_id)
        {
            $sql = 'SELECT d.*
                    FROM district d
                    INNER JOIN region r ON r.district_id = d.id
                    INNER JOIN clinic c ON c.region_id = r.id
                    INNER JOIN doctor_specialty_to_clinic ds2c ON ds2c.clinic_id = c.id
                    WHERE ds2c.doctor_id = ' .(int)$doctor_id .'
                    GROUP BY d.id';

            $data = $this->db->query($sql);

            return $this->initList($data);
        }
	}
