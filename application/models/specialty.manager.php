<?php

    class SpecialtyManager extends AliasManager
    {
        protected $table_name = 'specialty';
        protected $model_name = 'SpecialtyModel';

        public function beforeSave(DynamicModel $specialty)
        {
            $word_decline = WordDeclination::getInstance();

            SiteTaskManager::setDoctorsPurposesOfVisitBySpecialtyId($specialty->getId());

            if(!$specialty->plural_name)
            {
                $specialty->plural_name = $word_decline->toPlural($specialty->name);
            }
            if(!$specialty->dative_name)
            {
                $specialty->dative_name = $word_decline->toDative($specialty->name);
            }
            if(!$specialty->genitive_name)
            {
                $specialty->genitive_name = $word_decline->toGenitive($specialty->name);
            }
            parent::beforeSave($specialty);
        }

        /**
         * @return SpecialtyModel[]
         */
        public function getListByParentId($parent_id)
        {
            $data = $this->orm_model->select()->where('parent_id = ?', $parent_id)->fetchAll();

            return (count($data)) ? $this->initList($data) : array();
        }

        /**
         * @return SpecialtyModel[]
         */
        public function getAllSpecialities()
        {
            $data = $this->orm_model->select()->fetchAll();

            return (count($data)) ? $this->initList($data) : array();
        }

        /**
         * @return SpecialtyModel[]
         */
        public function getRootList()
        {
            $sql = 'SELECT *
					FROM `' . $this->table_name . '`
					WHERE parent_id IS NULL
					ORDER BY `name`';

            $data = $this->db->query($sql);

            return (count($data)) ? $this->initList($data) : array();
        }

        /**
         * @return SpecialtyModel
         */
        public function getOneByName($name)
        {
            $sql = 'SELECT *
                    FROM specialty
                    WHERE name LIKE "' . $this->db->escape($name) . '";';

            $data = $this->db->query($sql);

            return (isset($data[0])) ? $this->initOne($data[0]) : NULL;
        }

        /**
         * @return SpecialtyModel
         */
        public function getOneByDocDocId($docDocId)
        {
            $sql = 'SELECT *
                    FROM specialty
                    WHERE docdoc_id =' . (int) $docDocId . ';';

            $data = $this->db->query($sql);

            return (isset($data[0])) ? $this->initOne($data[0]) : NULL;
        }

        /**
         * @param $id
         * @return SpecialtyModel[]
         */
        public function getByDocDocSpecialtyId($id = null)
        {
            if (is_array($id)) {
                foreach ($id as &$v) {
                    $v = (int)$v;
                }
                unset($v);
                $where = ' docdoc_id IN ('. implode(',', $id) . ') ';
            } elseif (!empty($id)) {
                $where = 'docdoc_id = "' . (int)$id . ' ';
            } else {
                $where = '';
            }

            $sql = 'SELECT *
                    FROM specialty';

            if ($where) {
                $sql .= ' WHERE ' . $where;
            }

            $sql .= ';';

            $data = $this->db->query($sql);

            return (isset($data)) ? $this->initList($data) : NULL;
        }

        /**
         * @return SpecialtyModel
         */
        public function getOneByAliasOrSyninim($alias)
        {
            $sql = 'SELECT *
                    FROM specialty
                    WHERE alias LIKE "' . $this->db->escape($alias) . '"';

            $data = $this->db->query($sql);

            if (isset($data[0]))
            return $this->initOne($data[0]);

            $sql = 'SELECT *
                    FROM specialty
                    WHERE alias_synonim like "%' . $this->db->escape($alias) . '%"';

            $data = $this->db->query($sql);

            if (isset($data[0]))
                return $this->initOne($data[0]);

            return null;
        }

        /**
         * @return SpecialtyModel[]
         */
        public function getListByDoctorId($doctor_id)
        {
            $sql = 'SELECT DISTINCT s.*
                    FROM specialty s
                    INNER JOIN doctor_specialty_to_clinic ds2c ON specialty_id = s.id
                    INNER JOIN clinic c ON ds2c.clinic_id = c.id
                    WHERE c.is_active = 1
                     	AND doctor_id = ' . (int)$doctor_id;

            $data = $this->db->query($sql);

            //Костыль в связи с косячностью таблицы ds2c
            if(count($data) == 0)
            {
                $sql  = 'select DISTINCT s.*
						FROM specialty s
						INNER JOIN specialty_to_doctor s2d ON specialty_id = s.id
							AND s2d.doctor_id = ' . (int)$doctor_id;
                $data = $this->db->query($sql);
            }

            return count($data) ? $this->initList($data) : array();
        }

        /**
         * @return SpecialtyModel[]
         */
        public function getListByPurposeOfVisitId($purpose_of_visit_id)
        {
            $sql = 'SELECT s.*
				FROM specialty s
				INNER JOIN purpose_of_visit_to_specialty pv2s ON pv2s.specialty_id = s.id
				WHERE pv2s.purpose_of_visit_id = ' . (int)$purpose_of_visit_id . '
					ORDER BY s.name';

            $data = $this->db->query($sql);

            return $this->initList($data);
        }

        public function getParentIdById($specialty_id)
        {
            $data = $this->orm_model->select()->where('id = ?', $specialty_id)->fetchOne();

            return ($data) ? $data['parent_id'] : NULL;
        }

        public function getSpecialtyById($specialty_id)
        {
            $data = $this->orm_model->select()->where('id = ?', $specialty_id)->fetchOne();

            return !empty($data) ? $this->initOne($data) : NULL;
        }

        public function getActiveListByDoctorId($doctor_id)
        {
            $db = Register::get('db');

            $sql = 'SELECT *
                FROM specialty
                WHERE (
                    SELECT COUNT(*)
                    FROM doctor_specialty_to_clinic
                    WHERE specialty_id = specialty.id
                    AND doctor_id = ' . $doctor_id . '
                )>0
                ORDER BY name ASC';

            $data = $db->query($sql);

            return (count($data)) ? $this->initList($data) : array();
        }

        /**
         * @return SpecialtyModel[]
         */
        public function getListByClinicId($clinic_id)
        {
            $db = Register::get('db');

            $sql = 'SELECT *
                FROM specialty
                WHERE (
                    SELECT COUNT(*)
                    FROM specialty_to_clinic
                    WHERE specialty_id = specialty.id
                    AND clinic_id = ' . $clinic_id . '
                )>0
                ORDER BY name ASC';

            $data = $db->query($sql);

            return (count($data)) ? $this->initList($data) : array();
        }

        public function getRootListByClinicId($clinic_id)
        {
            $db = Register::get('db');

            $sql = 'SELECT DISTINCT sp.*
                    FROM specialty sp
                    INNER JOIN doctor_specialty_to_clinic ds2c ON ds2c.specialty_id = sp.id
                    WHERE sp.parent_id IS NULL
                        AND ds2c.clinic_id = ' . (int)$clinic_id . '

                    UNION

                    SELECT sp.*
                    FROM specialty sp
                    WHERE sp.parent_id IS NULL
                        AND EXISTS (
                        SELECT s.id
                        FROM specialty s
                        INNER JOIN doctor_specialty_to_clinic ds2c ON s.id = ds2c.specialty_id
                        WHERE ds2c.clinic_id = ' . (int)$clinic_id . '
                            AND s.parent_id = sp.id
                    )

                    ORDER BY name;';

            $data = $db->query($sql);

            return (count($data)) ? $this->initList($data) : array();
        }


        // added
        public function getSpecialtyListForClinic($clinic_id)
        {
            $db = Register::get('db');

            $sql = 'SELECT DISTINCT sp.*
						FROM specialty sp
						INNER JOIN doctor_specialty_to_clinic ds2c ON ds2c.specialty_id = sp.id
						INNER JOIN doctor d ON d.id = ds2c.doctor_id
						WHERE ds2c.clinic_id = ' . (int)$clinic_id . '
						AND d.is_active = 1 AND (ds2c.is_to_delete IS NULL OR ds2c.is_to_delete = 0)
						ORDER BY sp.`name` ASC';

            $data = $db->query($sql);

            return (count($data)) ? $this->initList($data) : array();
        }

        public function getRootListToSearchDoctors()
        {
            $db = Register::get('db');

            $sql = 'SELECT DISTINCT sp.*
                    FROM specialty sp
                    INNER JOIN specialty_to_doctor sp2d ON sp2d.specialty_id = sp.id
                    WHERE sp.parent_id IS NULL
                        AND sp2d.doctor_id IS NOT NULL

                    UNION

                    SELECT sp.*
                    FROM specialty sp
                    WHERE sp.parent_id IS NULL
                        AND EXISTS (
                    SELECT s.id
                    FROM specialty s
                    INNER JOIN specialty_to_doctor s2d ON s.id = s2d.specialty_id
                    WHERE s2d.doctor_id IS NOT NULL
                        AND s.parent_id = sp.id)

                    ORDER BY name
                    ;';

            $data = $db->query($sql);

            return (count($data)) ? $this->initList($data) : array();
        }

        public function getRootListToSearchDoctorsByCityId($city_id)
        {
            $db = Register::get('db');

            $city_manager = new CityManager();
            $city         = $city_manager->getOneById($city_id);

            $sql = 'SELECT DISTINCT sp.*
                    FROM specialty sp
					INNER JOIN doctor_specialty_to_clinic ds2c ON ds2c.specialty_id = sp.id
					INNER JOIN clinic c ON ds2c.clinic_id = c.id
					WHERE sp.parent_id IS NULL
						AND ds2c.doctor_id IS NOT NULL
						AND (c.city_id = ' . (int)$city_id . '
						';
            if($city && $city->region == 'Московская область')
            {
                $sql .= ' OR c.city_id = ' . (int)CityModel::MOSCOW_ID;
            }
            $sql .= ' )
                    UNION

                    SELECT sp.*
                    FROM specialty sp
                    WHERE sp.parent_id IS NULL
                        AND EXISTS (
                    SELECT s.id
                    FROM specialty s
                    INNER JOIN doctor_specialty_to_clinic ds2c ON s.id = ds2c.specialty_id
                    INNER JOIN clinic c ON c.id = ds2c.clinic_id
                    WHERE c.city_id = ' . (int)$city_id . '
                    	AND s.parent_id = sp.id
                    	AND c.is_active = 1)
            ';
            $sql .= ' ORDER BY name ASC;';

            $data = $db->query($sql);

            return (count($data)) ? $this->initList($data) : array();
        }

        public function getHavingDoctorsListByAddressObject(DynamicModel $address_object)
        {
            switch(get_class($address_object))
            {
                case 'CityModel':
                    return $this->getHavingDoctorsListByCityId($address_object->getId());
                case 'DistrictModel':
                    return $this->getHavingDoctorsListByDistrictId($address_object->getId());
                case 'RegionModel':
                    return $this->getHavingDoctorsListByRegionId($address_object->getId());
                case 'StreetModel':
                    return $this->getHavingDoctorsListByStreetId($address_object->getId());
                case 'MetroStationModel':
                    return $this->getHavingDoctorsListByMetroStationId($address_object->getId());
            }

            return array();
        }

        public function getHavingDoctorsListByCityId($city_id, $forceUpdate = false)
        {
            $cacheKey = __METHOD__ . "_{$city_id}";
            $result = $forceUpdate ? false : MemcacheAdapter::get($cacheKey);
            if (!$result) {

                $city_manager = new CityManager();
                $city         = $city_manager->getOneById($city_id);

                $sql = 'SELECT DISTINCT sp.*,
					(select DISTINCT s2s.is_main from specialty_to_specialization s2s where s2s.specialty_id = sp.id and s2s.is_main = 1) gparent
                    FROM specialty sp
					INNER JOIN doctor_specialty_to_clinic ds2c ON ds2c.specialty_id = sp.id
					INNER JOIN clinic c ON ds2c.clinic_id = c.id
					INNER JOIN doctor d ON ds2c.doctor_id = d.id
					WHERE ds2c.doctor_id IS NOT NULL
					    AND d.first_name IS NOT NULL
					    AND d.second_name IS NOT NULL
					    AND d.last_name IS NOT NULL
						AND (c.city_id = ' . (int)$city_id . '
						';
                if($city && $city->region == 'Московская область')
                {
                    $sql .= ' OR c.city_id = ' . (int)CityModel::MOSCOW_ID;
                }
                $sql .= ' )';

                $sql .= ' ORDER BY gparent DESC, name ASC;';

                $data = $this->db->query($sql);

                $result = (count($data)) ? $this->initList($data) : array();
                MemcacheAdapter::set($cacheKey, $result);
            }
            return $result;
        }

        public function getHavingDoctorsListByDistrictId($district_id)
        {
            $sql = 'SELECT s.*,
					(select DISTINCT s2s.is_main from specialty_to_specialization s2s where s2s.specialty_id = s.id and s2s.is_main = 1) gparent
					FROM ' . $this->table_name . ' s
					WHERE EXISTS (
									SELECT *
									FROM doctor dc
									INNER JOIN doctor_to_clinic d2c ON d2c.doctor_id = dc.id
									INNER JOIN doctor_specialty_to_clinic ds2c ON ds2c.doctor_id = dc.id
									INNER JOIN clinic c ON c.id = d2c.clinic_id
									WHERE
										(
											SELECT COUNT(*)
											FROM region r
											WHERE c.region_id = r.id
												AND r.district_id = ' . (int)$district_id . '
										) > 0
										AND ds2c.clinic_id = c.id
										AND ds2c.specialty_id = s.id
										AND dc.is_active = 1
                                        AND dc.first_name IS NOT NULL
                                        AND dc.second_name IS NOT NULL
                                        AND dc.last_name IS NOT NULL
								)
					ORDER BY gparent DESC, `name` ASC
					';

            $data = $this->db->query($sql);

            return $this->initList($data);
        }

        public function getHavingDoctorsListByRegionId($region_id)
        {
            $sql = 'SELECT s.*,
					(select DISTINCT s2s.is_main from specialty_to_specialization s2s where s2s.specialty_id = s.id and s2s.is_main = 1) gparent
					FROM specialty s
					WHERE EXISTS (
							SELECT *
							FROM doctor dc
							INNER JOIN doctor_to_clinic d2c ON d2c.doctor_id = dc.id
							INNER JOIN doctor_specialty_to_clinic ds2c ON ds2c.doctor_id = dc.id
							INNER JOIN clinic c ON c.id = d2c.clinic_id
							WHERE
								c.region_id = ' . (int)$region_id . '
								AND ds2c.clinic_id = c.id
								AND ds2c.specialty_id = s.id
								AND dc.is_active = 1
						)
					ORDER BY gparent DESC, `name` ASC';

            $data = $this->db->query($sql);

            return $this->initList($data);
        }

        public function getHavingDoctorsListByMetroStationId($metro_station_id)
        {
            $sql = 'SELECT s.*,
					(select DISTINCT s2s.is_main from specialty_to_specialization s2s where s2s.specialty_id = s.id and s2s.is_main = 1) gparent
					FROM specialty s
					WHERE EXISTS (
						SELECT *
						FROM doctor dc
						INNER JOIN doctor_to_clinic d2c ON d2c.doctor_id = dc.id
						INNER JOIN doctor_specialty_to_clinic ds2c ON ds2c.doctor_id = dc.id
						INNER JOIN clinic c ON c.id = d2c.clinic_id
						WHERE
							c.metro_station_id = ' . (int)$metro_station_id . '
							AND ds2c.clinic_id = c.id
							AND dc.is_active = 1
							AND ds2c.specialty_id = s.id
					)
					ORDER BY gparent DESC, `name` ASC';

            $data = $this->db->query($sql);

            return $this->initList($data);
        }

        public function getHavingDoctorsListByStreetId($street_id)
        {
            $sql = 'SELECT s.*,
					(select DISTINCT s2s.is_main from specialty_to_specialization s2s where s2s.specialty_id = s.id and s2s.is_main = 1) gparent
					FROM specialty s
					WHERE EXISTS (
							SELECT *
							FROM doctor dc
							INNER JOIN doctor_to_clinic d2c ON d2c.doctor_id = dc.id
							INNER JOIN doctor_specialty_to_clinic ds2c ON ds2c.doctor_id = dc.id
							INNER JOIN clinic c ON c.id = d2c.clinic_id
							WHERE
								c.street_id = ' . (int)$street_id . '
								AND ds2c.clinic_id = c.id
								AND ds2c.specialty_id = s.id
								AND dc.is_active = 1
						)
					ORDER BY gparent DESC, `name` ASC';

            $data = $this->db->query($sql);

            return $this->initList($data);
        }

        public function getRootListToSearchClinic()
        {
            $db = Register::get('db');

            $sql = 'SELECT DISTINCT sp.*
                    FROM specialty sp
                    INNER JOIN specialty_to_clinic sp2c ON sp2c.specialty_id = sp.id
                    WHERE sp.parent_id IS NULL
                    AND sp2c.clinic_id IS NOT NULL
                    ORDER BY sp.name;';

            $data = $db->query($sql);

            return (count($data)) ? $this->initList($data) : array();
        }

        public function getRootListToSearchClinicByCityId($city_id)
        {
            $db = Register::get('db');

            $city_manager = new CityManager();

            $city = $city_manager->getOneById($city_id);

            $sql = 'SELECT DISTINCT sp.*
                    FROM specialty sp
                    INNER JOIN specialty_to_clinic sp2c ON sp2c.specialty_id = sp.id
                    INNER JOIN clinic c ON c.id = sp2c.clinic_id
                    WHERE sp2c.clinic_id IS NOT NULL
                    AND (c.city_id = ' . (int)$city_id;
            if($city && $city->region == 'Московская область')
            {
                $sql .= ' OR c.city_id = ' . (int)CityModel::MOSCOW_ID;
            }
            $sql .= ' ) ';
            $sql .= ' ORDER BY sp.name;';

            $data = $db->query($sql);

            return (count($data)) ? $this->initList($data) : array();
        }

        public function getIdByName($name)
        {
            $sql = 'SELECT id
                    FROM ' . $this->table_name . '
                    WHERE name = "' . $this->db->escape($name) . '"';

            $data = $this->db->query($sql);

            return (isset($data[0]['id'])) ? $data[0]['id'] : FALSE;
        }

        public function getRootListByPastVisitAndMyDoctorsByAccountId($account_id)
        {
            $sql = 'SELECT * FROM (
                                    SELECT sp.*
                                    FROM specialty sp
                                    INNER JOIN doctor_to_clinic d2c ON d2c.specialty_id = sp.id
                                    INNER JOIN schedule s ON s.doctor_id = d2c.doctor_id
                                    INNER JOIN visit v ON s.visit_id = v.id
                                    WHERE v.account_id = ' . (int)$account_id . '

                                    UNION

                                    SELECT sp.*
                                    FROM specialty sp
                                    INNER JOIN doctor_to_clinic d2c ON d2c.specialty_id = sp.id
                                    INNER JOIN my_doctor myd ON myd.doctor_id = d2c.doctor_id
                                    WHERE myd.account_id = ' . (int)$account_id . '
                                    ) a ORDER BY name';

            $data = $this->db->query($sql);

            return count($data) ? $this->initList($data) : array();
        }

        public function getMainListByDiseaseId($disease_id)
        {
            $sql = 'SELECT *
                    FROM specialty s
                    INNER JOIN specialty_to_disease s2d ON s2d.specialty_id = s.id
                    WHERE s2d.disease_id = ' . (int)$disease_id . '
                    AND s2d.main_flag = 1';

            $data = $this->db->query($sql);

            if (count($data) == 0){
                $sql = 'SELECT *, 1 as is_adult, 1 as is_male, 1 as is_female, 1 as is_children, 1 as is_newborn, 1 as is_pregnant
                    FROM specialty s                    
                    WHERE s.id = 29';

                $data = $this->db->query($sql);
            }

            return count($data) ? $this->initList($data) : array();
        }

        public function getSpecialtyListByDoctorIdAndClinicId($doctor_id, $clinic_id)
        {
            $sql = 'SELECT s.*
					FROM specialty s
					INNER JOIN doctor_to_clinic d2c ON s.id = d2c.specialty_id
					WHERE d2c.doctor_id = ' . (int)$doctor_id . '
						AND d2c.clinic_id = ' . (int)$clinic_id;

            $data = $this->db->query($sql);

            return $this->initList($data);
        }

        /**
         * @param $clinic_id
         *
         * @return SpecialtyModel[]
         */
        public function getDoctorSpecialtyListByClinicId($clinic_id)
        {
            $sql = 'SELECT s.*
                    FROM specialty s
                    INNER JOIN doctor_specialty_to_clinic ds2c ON ds2c.specialty_id = s.id
                    WHERE ds2c.clinic_id = ' . (int)$clinic_id . '
                    GROUP BY s.id';

            $data = $this->db->query($sql);

            return $this->initList($data);
        }

        public function getSuitableListBySpecialtyIdAndPurposeOfVisitId($specialty_ids, $purpose_of_visit_id)
        {
          $specialty_ids = implode(", ", array_map('intval', $specialty_ids));
          $sql = 'SELECT s.*
					FROM specialty s
					INNER JOIN suitable_specialty ss ON s.id = ss.suitable_specialty_id
					WHERE ss.specialty_id IN ('. $specialty_ids . ')
						AND ss.purpose_of_visit_id = ' . (int)$purpose_of_visit_id;

            $data = $this->db->query($sql);

            return $this->initList($data);
        }

        /**
         * @return SpecialtyModel[]
         */
        public function getListBySpecializationId($specialization_id)
        {
            $sql = 'SELECT s.*
					FROM specialty s
					WHERE EXISTS (
						SELECT *
						FROM specialty_to_specialization s2s
						WHERE s2s.specialty_id = s.id
							AND s2s.specialization_id = ' . (int)$specialization_id . '
					)';

            $data = $this->db->query($sql);

            return $this->initList($data);
        }

        public function getMainOneBySpecializationId($specialization_id, $specialty_id = 0)
        {
            $sql = 'SELECT s.*
                    FROM specialty                         AS s
                    INNER JOIN specialty_to_specialization AS sts ON sts.specialty_id = s.id
                    WHERE
                         sts.specialization_id = ' . (int)$specialization_id . ' AND
                         is_main = 1 AND
                         s.id != ' . (int)$specialty_id;

            $data = $this->db->query($sql);

            return ($data) ? $this->initOne($data[0]) : NULL;
        }

        public function getOneSpecializationByMainSpecialtyId($specialty_id)
        {
            $sql = 'SELECT s.*
					FROM specialization AS s
					WHERE EXISTS (
						SELECT *
						FROM specialty_to_specialization AS s2s
						WHERE
						    s2s.specialization_id = s.id                  AND
						    s2s.specialty_id = ' . (int)$specialty_id . ' AND
							is_main = 1
					)';

            $data = $this->db->query($sql);

            return ($data) ? $this->initOne($data[0]) : NULL;
        }


        public function getMainOneBySpecializationIdAndClinicId($specialization_id, $clinic_id)
        {
            $sql = 'SELECT s.*
						FROM specialty s
						WHERE EXISTS (
							SELECT *
							FROM specialty_to_specialization s2s
							INNER JOIN doctor_specialty_to_clinic ds2c ON ds2c.specialty_id = s2s.specialty_id
							WHERE s2s.specialty_id = s.id
								AND s2s.specialization_id = ' . (int)$specialization_id . '
								AND ds2c.clinic_id = ' . (int)$clinic_id . '
								AND is_main = 1
						)';

            $data = $this->db->query($sql);

            return ($data) ? $this->initOne($data[0]) : NULL;
        }


        /**
         * @return SpecialtyModel[]
         */
        public function getListByDoctorIdAndClinicId($doctor_id, $clinic_id)
        {
            $sql  = 'SELECT DISTINCT s.*
                    FROM `' . $this->table_name . '` s
                    LEFT JOIN doctor_specialty_to_clinic dtc  ON dtc.specialty_id = s.id
                    WHERE dtc.doctor_id = ' . (int)$doctor_id . '
                        AND dtc.clinic_id = ' . (int)$clinic_id;
            $data = $this->db->query($sql);

            return (isset($data)) ? $this->initList($data) : array();
        }

        public function getSuitableListByDoctorIdAndClinicIdAndSpecialtyIdList($doctor_id, $clinic_id, $specialties_ids)
        {
            $counter     = 1;
            $specialties = '';
            foreach($specialties_ids as $specialty_id)
            {
                if($counter != 1)
                {
                    $specialties .= ',';
                }
                $specialties .= $specialty_id;
                $counter++;
            }

            $sql = 'SELECT s.*
					FROM specialty s
					INNER JOIN doctor_specialty_to_clinic dtc ON s.id = dtc.specialty_id
					WHERE dtc.specialty_id IN (' . $specialties . ')
					    AND dtc.clinic_id = ' . (int)$clinic_id . '
					    AND dtc.doctor_id = ' . (int)$doctor_id;

            $data = $this->db->query($sql);

            return $this->initList($data);
        }

        public function getOneByNameForm($name)
        {
            $sql = 'SELECT *
					FROM ' . $this->table_name . '
					WHERE name = "' . $this->db->escape($name) . '"
						OR genitive_name = "' . $this->db->escape($name) . '"
						OR dative_name = "' . $this->db->escape($name) . '"
						OR plural_name = "' . $this->db->escape($name) . '"';

            $data = $this->db->query($sql);

            if($data)
            {
                return $this->initOne($data[0]);
            }
            else
            {
                return NULL;
            }
        }
    }
