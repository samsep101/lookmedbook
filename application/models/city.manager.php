<?php
	class CityManager extends AliasManager
	{
		protected $table_name = 'city';
		protected $model_name = 'CityModel';

		public function getIdByName($name)
		{
			$data = $this->orm_model->select()->where('name = ?', $name)->fetchOne();

			return count($data) ? $data['id'] : null;
		}


        /**
		 * @var string $name
		 *
		 * @return CityModel
		 */
		public function getOneByName($name)
		{
			$data = $this->orm_model->select()->where('name = ?', $name)->fetchOne();
			return $this->initOne($data);
		}

		/**
		 * @param bool $flag
		 *
		 * @return array
		 */
		public function getOrderedListByServiceFlag($flag)
		{
			$sql = 'SELECT *
                    FROM ' . $this->table_name . '
                    WHERE service_flag = ' . (int)$flag . '
                    ORDER BY sort DESC, name ASC';

			$data = $this->db->query($sql);

			return (isset($data)) ? $this->initList($data) : array();
		}

		/**
		 * @return CityModel[]
		 */
		public function getActiveList()
		{
			$sql = 'SELECT *
					FROM city
					WHERE service_flag = 1
					ORDER BY sort DESC, name ASC';

			$data = $this->db->query($sql);

			return $this->initList($data);
		}

		public function getIdByNameAndRegion($name, $region)
		{
			$sql = 'SELECT *
                    FROM ' . $this->table_name . '
                    WHERE name = "' . $this->db->escape($name) . '"
                    AND region = "' . $this->db->escape($region) . '"';
			$db = Register::get('db');
			$data = $db->query($sql);

			return (isset($data[0])) ? $data[0]['id'] : false;
		}

		/**
		 * @param $name
		 *
		 * @return bool
		 */
		public function getIdByCityName($name)
		{
			$sql = 'SELECT *
                    FROM ' . $this->table_name . '
                    WHERE name = "' . $this->db->escape($name) . '"';
			$db = Register::get('db');
			$data = $db->query($sql);

			return (isset($data[0])) ? $data[0]['id'] : false;
		}


		/**
		 * @return CityModel
		 */
		public function getOneByNameAndRegion($name, $region)
		{
			$sql = 'SELECT *
                    FROM ' . $this->table_name . '
                    WHERE name = "' . $this->db->escape($name) . '"
                    AND region = "' . $this->db->escape($region) . '"';
			$db = Register::get('db');
			$data = $db->query($sql);

			return (isset($data[0])) ? $this->initOne($data[0]) : false;
		}


		/**
		 *
		 * @return CityModel[]
		 */
		public function getListByCityAndServiceFlag($query, $service_flag, $by_page, $page)
		{
			$offset = ($page - 1) * $by_page;
			$sql = 'SELECT *
                    FROM ' . $this->table_name . '
                    WHERE name LIKE  "%' . $this->db->escape($query) . '%"
                    AND service_flag = ' . (int)$service_flag . '
                    LIMIT ' . $offset . ',' . $by_page;

			$data = $this->db->query($sql);

			return (count($data)) ? $this->initList($data) : array();
		}

        /**
		 * @return CityModel[]
		 */
		public function getListByCity($query, $by_page, $page)
		{
			$offset = ($page - 1) * $by_page;
			$sql = 'SELECT *
					FROM ' . $this->table_name . '
					WHERE name LIKE  "%' . $this->db->escape($query) . '%"
					LIMIT ' . $offset . ',' . $by_page;

			$data = $this->db->query($sql);

			return (count($data)) ? $this->initList($data) : array();
		}

		public function setServiceFlagById($id, $service_flag)
		{
			$sql = 'UPDATE ' . $this->table_name . '
                    SET service_flag = ' . (int)$service_flag . '
                    WHERE id = ' . (int)$id;

			$this->db->query($sql);
		}

		public function getHavingDoctorsListBySpecialtyId($specialty_id)
		{
			$sql = 'SELECT *
					FROM ' . $this->table_name . ' c
					WHERE EXISTS (
							SELECT *
							FROM doctor dc
							INNER JOIN doctor_to_clinic d2c ON d2c.doctor_id = dc.id
							INNER JOIN doctor_specialty_to_clinic ds2c ON ds2c.doctor_id = dc.id
							INNER JOIN clinic cl ON cl.id = d2c.clinic_id
							WHERE dc.is_active = 1
								AND cl.city_id = c.id
								AND cl.is_active = 1
								AND c.is_has_doctors = 1
								AND ds2c.specialty_id = ' . (int)$specialty_id . '
						)
					ORDER BY `name`
					';

			$data = $this->db->query($sql);
			return $this->initList($data);
		}

		public function getHavingClinicsListByTypeOrService($item_id, $type)
		{
            $tables = ClinicHelper::getDataToLinkTheTables($type);

            if(empty($tables)) return array();

			$sql = 'SELECT *
					FROM ' . $this->table_name . ' AS c
					WHERE
					  EXISTS (
                        SELECT *
                        FROM `clinic`                                                 AS cl
                          INNER JOIN `' . $tables['table_name_join'] . '`             AS j  ON j.' . $tables['page_id'] . ' = cl.id
                          INNER JOIN `' . $tables['table_name_for_landing_page'] . '` AS lp ON lp.id = j.' . $tables['join_id'] . '
                        WHERE
                          cl.is_active = 1  AND
                          cl.city_id = c.id AND
                          j.' . $tables['join_id'] . ' = ' . (int)$item_id . '
					  )
					ORDER BY `name`';

			$data = $this->db->query($sql);

            return $data ? $this->initList($data) : array();
		}

		public function setIsHasDoctorsFlag()
		{
			$sql = 'UPDATE city c
					SET is_has_doctors = IF(
							(
							SELECT COUNT(*)
							FROM clinic cl
							INNER JOIN doctor_specialty_to_clinic ds2cl ON ds2cl.clinic_id = cl.id
							INNER JOIN doctor d ON d.id = ds2cl.doctor_id
							WHERE cl.city_id = c.id
								AND cl.is_active = 1
								AND d.is_active = 1
							LIMIT 1
							) > 0, 1, 0
						)';

			$this->db->query($sql);
		}

		public function setIsHasClinicsFlag()
		{
			$sql = 'UPDATE city c
					SET is_has_clinics = IF(
						(
							SELECT COUNT(*)
							FROM clinic cl
							WHERE cl.city_id = c.id
								AND cl.is_active = 1
						) > 0,
						1,
						0
					)';
			$this->db->query($sql);
		}

		public function setIsHasLaboratoriesFlag()
		{
			$sql = 'UPDATE city c
					SET is_has_laboratories = IF(
						(
							SELECT COUNT(*)
							FROM laboratory l
							WHERE l.city_id = c.id
							LIMIT 1
						) > 0,
						1,
						0
					)';
			$this->db->query($sql);
		}

		public function getHavingClinicsListOrderByName()
		{
			$sql = 'SELECT *
					FROM city
					WHERE is_has_clinics = 1
					ORDER BY name ASC';

			$data = $this->db->query($sql);

			return $this->initList($data);
		}

		public function getHavingDoctorsListOrderByName()
		{
			$sql = 'SELECT *
					FROM city
					WHERE is_has_doctors = 1
					ORDER BY name ASC';

			$data = $this->db->query($sql);

			return $this->initList($data);
		}

        public function getHavingLaboratoriesListOrderByName()
        {
            $sql = 'SELECT *
					FROM city
					WHERE is_has_laboratories = 1
					ORDER BY name ASC';

            $data = $this->db->query($sql);

            return $this->initList($data);
        }

        public function getHavingLaboratoriesList()
        {
            $sql = 'SELECT *, (
								SELECT COUNT(*) AS lab
								FROM laboratory
								WHERE city_id = c.id
							   ) AS count_laboratories
                    FROM city c
	                WHERE (
								SELECT COUNT(*) AS lab
								FROM laboratory
								WHERE city_id = c.id
							) > 0
	                ORDER BY count_laboratories DESC';

            $data = $this->db->query($sql);

            return ($data) ? $this->initList($data) : array();
        }

        public function getHavingLaboratoriesListByCity($query, $by_page, $page)
        {
            $offset = ($page - 1) * $by_page;
            $sql = 'SELECT *
					FROM ' . $this->table_name . ' c
					WHERE name LIKE  "%' . $this->db->escape($query) . '%"
					    AND (
								SELECT COUNT(*) AS lab
								FROM laboratory
								WHERE city_id = c.id
							) > 0
					LIMIT ' . $offset . ',' . $by_page;

            $data = $this->db->query($sql);

            return (count($data)) ? $this->initList($data) : array();
        }


		public function getListByModelSearchCriteria(ModelSearchCriteria $criteria)
		{
			/**
			 * @var CitySearchCriteria $criteria
			 */
			$search_params = $criteria->getSearchParams();
			if (!$search_params)
				$search_params = new SearchParams();

			if ($criteria->page && $criteria->by_page)
			{
				$limit = $criteria->by_page;
				$offset = ($criteria->page - 1) * $criteria->by_page;
				$search_params->setOffsetAndLimit($offset, $limit);
			}

			if (isset($criteria->has_doctors))
			{
				$search_params->addParam('is_has_doctors', $criteria->has_doctors);
			}

			if (isset($criteria->has_clinics))
			{
				$search_params->addParam('is_has_clinics', $criteria->has_clinics);
			}

			if (isset($criteria->has_laboratories))
			{
				$search_params->addParam('is_has_laboratories', $criteria->has_laboratories);
			}

            if(isset($criteria->has_one))
            {
                $search_params->startBracket();

                if(isset($criteria->has_one))
                {
                    $search_params->addParam('is_has_doctors OR', 1);
                    $search_params->addParam('is_has_clinics OR', 1);
                    $search_params->addParam('is_has_laboratories OR', 1);
                }

                $search_params->endBracket();
            }

			if ($criteria->name)
			{
				$search_params->addParam('name LIKE', '%'.str_replace('%', '\%', $criteria->name).'%');
			}

			return $this->getListBySearchParams($search_params);
		}

        // n самых больших городов для попапа
        public function getListBySort()
        {
            $sql = 'SELECT *
                    FROM city
                    WHERE service_flag = 1
                    ORDER BY sort DESC
                    LIMIT 0, 19';

            $data = $this->db->query($sql);

            return $this->initList($data);
        }

        // Список городов, в которых есть или клиника или лаборатория
        public function getListWithClinicsOrDoctorsOrLaboratories()
        {
            $sql = 'SELECT *
                    FROM city
                    WHERE is_has_clinics = 1
                    OR is_has_doctors = 1
                    OR is_has_laboratories = 1
                    ORDER BY `name`';

            $data = $this->db->query($sql);

            return $this->initList($data);
        }

        // Список городов, в которых есть врачи или клиники
        public function getHavingClinicsOrDoctorsList()
        {
            $sql = 'SELECT *
                    FROM city
                    WHERE is_has_clinics = 1
                    OR is_has_doctors = 1
                    ORDER BY `name`';

            $data = $this->db->query($sql);

            return $this->initList($data);
        }

        // Список городов, в которых есть не виртуальные врачи
        public function getListWithDoctorsByRegistryUserId($registry_user_id)
        {
            $sql = 'SELECT DISTINCT c.*
                    FROM city c
                    INNER JOIN clinic cl ON cl.city_id = c.id
                    INNER JOIN doctor_to_clinic d2c ON d2c.clinic_id = cl.id
                    INNER JOIN clinic_to_user c2u ON c2u.clinic_id = cl.id
                    INNER JOIN doctor d ON d2c.doctor_id = d.id
                    WHERE d.is_virtual is null
                    AND c2u.user_id = ' . (int)$registry_user_id . '
                    ORDER BY c.name';

            $data = $this->db->query($sql);

            return $this->initList($data);
        }

        // Список городов, в которых есть клиники
        public function getListWithClinicsByRegistryUserId($registry_user_id)
        {
            $sql = 'SELECT DISTINCT c.*
                    FROM city c
                    INNER JOIN clinic cl ON cl.city_id = c.id
                    INNER JOIN clinic_to_user c2u ON c2u.clinic_id = cl.id
                    WHERE c2u.user_id = ' . (int)$registry_user_id . '
                    ORDER BY c.name';

            $data = $this->db->query($sql);

            return $this->initList($data);
        }

        // Список городов, в которых есть не виртуальные врачи
        public function getListWithDoctors()
        {
            $sql = 'SELECT DISTINCT c.*
                    FROM city c
                    INNER JOIN clinic cl ON cl.city_id = c.id
                    INNER JOIN doctor_to_clinic d2c ON d2c.clinic_id = cl.id
                    INNER JOIN doctor d ON d2c.doctor_id = d.id
                    WHERE d.is_virtual is null
                    ORDER BY c.name';

            $data = $this->db->query($sql);

            return $this->initList($data);
        }

        // Список городов, в которых есть клиники
        public function getListWithClinics()
        {
            $sql = 'SELECT DISTINCT c.*
                    FROM city c
                    INNER JOIN clinic cl ON cl.city_id = c.id
                    ORDER BY c.name';

            $data = $this->db->query($sql);

            return $this->initList($data);
        }

        // Список городов, в которых есть пользователи
        public function getListWithUsersByManagerUserId($manager_user_id)
        {
            $user_manager = new UserManager();
            $user = $user_manager->getOneById($manager_user_id);

            if($user->role_id == RoleModel::ACCOUNT_SUPER_MANAGER)
            {
                $sql = 'SELECT DISTINCT c.*
                        FROM city c
                        INNER JOIN clinic cl ON cl.city_id = c.id
                        INNER JOIN clinic_to_user c2u ON c2u.clinic_id = cl.id
                        INNER JOIN user u ON c2u.user_id = u.id
                      ORDER BY c.name';
            } else {
                $sql = 'SELECT DISTINCT c.*
                        FROM city c
                        INNER JOIN clinic cl ON cl.city_id = c.id
                        INNER JOIN clinic_to_user c2u ON c2u.clinic_id = cl.id
                        INNER JOIN user u ON c2u.user_id = u.id
                        WHERE c2u.user_id = ' . (int)$manager_user_id . '
                            AND u.role_id IN (' . join(', ', RoleHelper::getManagerRolesIdList()) . ')
                        ORDER BY c.name';
            }
            $data = $this->db->query($sql);

            return $this->initList($data);
        }

        // Список городов, в которых работает пользователь
        public function getListToEditByUserId($manager_user_id)
        {
            $sql = 'SELECT DISTINCT c.*
                    FROM city c
                    INNER JOIN clinic cl ON cl.city_id = c.id
                    INNER JOIN clinic_to_user c2u ON c2u.clinic_id = cl.id
                    INNER JOIN user u ON c2u.user_id = u.id
                    WHERE c2u.user_id = ' . (int)$manager_user_id . '
                    ORDER BY c.name';
            $data = $this->db->query($sql);

            return $this->initList($data);
        }

        // Список городов, в которых есть региональные клиники
        public function getListWithRegionsByUserId($user_id)
        {
            $user_manager = new UserManager();
            $user = $user_manager->getOneById($user_id);

            if ($user && $user->role_id == RoleModel::ACCOUNT_SUPER_MANAGER)
            {
                $sql = 'SELECT DISTINCT c.*
					FROM city c
					INNER JOIN clinic cl ON cl.city_id = c.id
					WHERE cl.is_region = 1
					ORDER BY c.name';
            } else {
                $sql = 'SELECT DISTINCT c.*
                        FROM city c
                        INNER JOIN clinic cl ON cl.city_id = c.id
                        INNER JOIN clinic_to_user c2u ON c2u.clinic_id = cl.id
                        INNER JOIN user u ON c2u.user_id = u.id
                        WHERE cl.is_region = 1
                        AND u.id = ' .$user_id. '
                        ORDER BY c.name';
            }

            $data = $this->db->query($sql);

            return $this->initList($data);
        }

        // Список городов, в которых есть для проверки региональных клиник
        public function getListWithRegionsCheckByUserId($user_id)
        {
            $user_manager = new UserManager();
            $user = $user_manager->getOneById($user_id);

            if ($user && $user->role_id == RoleModel::ACCOUNT_SUPER_MANAGER)
            {
                $sql = 'SELECT DISTINCT c.*
                        FROM city c
                        INNER JOIN clinic cl ON cl.city_id = c.id
                        WHERE cl.is_region = 1
                        AND cl.date_publish IS NOT NULL
                        ORDER BY c.name';
            } else {
                $sql = 'SELECT DISTINCT c.*
                        FROM city c
                        INNER JOIN clinic cl ON cl.city_id = c.id
                        INNER JOIN clinic_to_user c2u ON c2u.clinic_id = cl.id
                        INNER JOIN user u ON c2u.user_id = u.id
                        WHERE cl.is_region = 1
                        AND cl.date_publish IS NOT NULL
                        AND u.id = ' .$user_id. '
                        ORDER BY c.name';
            }
            $data = $this->db->query($sql);

            return $this->initList($data);
        }
	}
