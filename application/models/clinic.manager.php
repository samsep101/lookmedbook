<?php

class ClinicManager extends AliasManager
{
    const CB_UPDATE_DOCTOR_WORKING_TIME_FLAGS = 'updateDoctorWorkingTimeFlags';
  protected $table_name = 'clinic';
  protected $model_name = 'ClinicModel';

  protected $transliterated_field = 'name';

    public function __construct($table = '')
    {
        parent::__construct($table);
        $this->setEventCallback(
            self::EVENT_AFTER_SAVE,
            self::CB_UPDATE_DOCTOR_WORKING_TIME_FLAGS,
            function (ClinicModel $clinic) {
                $this->updateDoctorWorkingTimeFlags([$clinic], true);
            }
        );
    }

    /**
     * @param ClinicModel[] $clinics
     * @param bool $checkIfUpdateRequired
     */
    public function updateDoctorWorkingTimeFlags(
        $clinics,
        $checkIfUpdateRequired = false
    ) {
        /** @var DoctorManager $doctorManager */
        $doctorManager = ModelManagerFactory::getByName('doctor');
        $updatedDoctors = $processedMcDoctors = [];
        foreach ($clinics as $clinic) {
            if ($checkIfUpdateRequired
                && !$clinic->isDoctorWorkingTimeFlagsUpdateRequired()
            ) {
                continue;
            }

            $scDoctors = $doctorManager
                ->updateSingleClinicDoctorWorkingTimeFlags($clinic);
            foreach ($scDoctors as $doctor) {
                $updatedDoctors[$doctor->getId()] = $doctor;
            }

            $mcDoctors = $doctorManager
                ->getMultiClinicDoctors($clinic, $processedMcDoctors);
            foreach ($mcDoctors as $doctor) {
                $processedMcDoctors[$doctor->getId()] = $doctor->getId();
                if ($doctor->updateWorkingTimeFlags()) {
                    $doctor->setSaveProcessFlag();
                    $doctor->save();
                    $updatedDoctors[$doctor->getId()] = $doctor;
                }
            }
        }
        if ($updatedDoctors) {
            ElasticaTask::indexDoctors($updatedDoctors);
        }
    }

  protected function beforeSave(DynamicModel $clinic)
  {
      /** @var ClinicModel $clinic */
      $initialAlias = $clinic->alias;

      parent::beforeSave($clinic); // generates missing alias

      $primary_clinic = $clinic->getPrimaryClinic();
      if ($primary_clinic
          && $clinic->primary_clinic_id != $clinic->id
          && strpos($initialAlias, '/') === false
      ) {
          $clinic->original_alias = $clinic->original_alias ?: $initialAlias ?: $clinic->alias;
          $clinic->alias = $primary_clinic->alias.'/'.$clinic->alias;
      }

    if ($clinic->city_id) {
      /**
       * @var CityManager $manager ;
       */
      $manager = ModelManagerFactory::getByName('city');
      $manager->setServiceFlagById($clinic->city_id, 1);
    }

    if ($clinic->getId()) {
      if (!$clinic->is_active) {
        if ($clinic->clinic_status_id == null || $clinic->clinic_status_id == ClinicStatusModel::RAW) {
          $clinic->clinic_status_id = ClinicStatusModel::RAW;
        } else {
          $clinic->clinic_status_id = ClinicStatusModel::PROBLEM;
        }
      } else {
        $clinic->clinic_status_id = ClinicStatusModel::PUBLISHED;
      }

      if ($clinic->isChangeStatus()) {
        $clinic->dt_publish = date('Y-m-d H:i:s');
        $clinic->date_publish = date('Y-m-d');
      }
    }

    if ($clinic->license_validity_date == '0000-00-00') {
        $clinic->license_validity_date = null;
    }

    if ($clinic->license_validity_date == '1970-01-01') {
      $clinic->license_validity_date = null;
    }

    if ($clinic->license_issue_date == '0000-00-00') {
        $clinic->license_issue_date = null;
    }

    if ($clinic->license_issue_date == '1970-01-01') {
      $clinic->license_issue_date = null;
    }


    // определение региона, в котором находится клиника
    /**
     * @var RegionManager $region_manager
     */
    $region_manager = ModelManagerFactory::getByName('region');
    if (!$clinic->region_id && ($clinic->longitude && $clinic->latitude)) {
      $geocoder = new YandexGeocoder();
      $region_name = $geocoder->getDistrictInfoByGeoPoint(new GeoPoint($clinic->latitude, $clinic->longitude));

      $region = $region_manager->getOneByNameAndCityId($region_name, $clinic->city_id);

      if ($region) {
        $clinic->region_id = $region->getId();
      }
    }

    if (!$clinic->docdoc_id) {
        $clinic->docdoc_id = null;
    }

    if ($clinic->getOldAttribute('is_active') != $clinic->is_active) {
        $clinic->is_active_updated_at = time();
    }

    if (!$clinic->metro_station_id) {
        /** @var MetroStationToClinicManager $ms2cManager */
        $ms2cManager = ModelManagerFactory::getByName('metro_station_to_clinic');
        $ms2c = $ms2cManager->getOneByClinicId($clinic->getId());
        if ($ms2c) {
            $clinic->metro_station_id = $ms2c->metro_station_id;
        }
    }
  }

  public function afterSave(DynamicModel $model)
  {
      ElasticaTask::indexClinic($model->getId());

    return;
  }
    /**
     * @return ClinicModel[]
     */
    public function getChildsClinic($primary_clinic_id)
    {
        $sql = 'SELECT DISTINCT c.*
                    FROM `' . $this->table_name . '` c                    
                    WHERE c.primary_clinic_id="'.$primary_clinic_id.'"';

        $data = $this->db->query($sql);

        return (isset($data)) ? $this->initList($data) : array();
    }

    /**
     * @return ClinicModel[]
     */
    public function getListWithDocdocId() {
        $sql = 'SELECT DISTINCT c.*
                    FROM `' . $this->table_name . '` c                    
                    WHERE c.docdoc_id > 0';

        $data = $this->db->query($sql);

        return (isset($data)) ? $this->initList($data) : array();
    }

    /**
     * Получение списка записей конкретных клиник
     * @return ClinicModel[]
     */
    public function getListWithDocdocIdList($docdoc_ids) {

        if(is_array($docdoc_ids)){

            $docdoc_ids = array_map('intval', $docdoc_ids);
            $docdoc_ids = implode(', ', $docdoc_ids);

            $sql = 'SELECT DISTINCT c.*
                    FROM `' . $this->table_name . '` c                    
                    WHERE c.docdoc_id IN ('.$docdoc_ids.')';

            $data = $this->db->query($sql);

            return (isset($data)) ? $this->initList($data) : array();
        }

        return false;
    }

    /**
   * @return ClinicModel[]
   */
  public function getActiveListByDoctorId($doctor_id)
  {
	                        $memcache = Register::get('memcache');
				$cache_key = 'active_list_'.$doctor_id;
				$cache = $memcache->get($cache_key);
				if ($cache !== false) {
					return $cache;
				}	
  $sql = 'SELECT DISTINCT c.*
                    FROM `' . $this->table_name . '` c
                    INNER JOIN doctor_to_clinic dtc  ON dtc.clinic_id = c.id
                    WHERE dtc.doctor_id = ' . (int)$doctor_id . '
                    	AND c.is_active = 1';

    $data = $this->db->query($sql);

				$result =  (isset($data)) ? $this->initList($data) : array();
			$memcache->set($cache_key, $result, 1080);
				return $result;
  }

  /**
   * @param $doctor_id
   *
   * @return ClinicModel[]
   */
  public function getListByDoctorId($doctor_id)
  {
    $sql = 'SELECT DISTINCT c.*
                    FROM `' . $this->table_name . '` c
                    INNER JOIN doctor_to_clinic dtc  ON dtc.clinic_id = c.id
                    WHERE dtc.doctor_id = ' . (int)$doctor_id;

    $data = $this->db->query($sql);

    return (isset($data)) ? $this->initList($data) : array();
  }

  public function getIdAndNameByUserId($user_id)
  {
    $sql = 'SELECT c.id, c.name
					FROM clinic c
					';

    /**
     * @var UserManager $user_manager
     * @var UserModel $user
     */
    $user_manager = ModelManagerFactory::getByName('user');
    $user = $user_manager->getOneById($user_id);

    if ($user) {
      if ($user->role_id != RoleModel::ACCOUNT_SUPER_MANAGER) {

        $sql .= 'INNER JOIN clinic_to_user c2u ON c.id = c2u.clinic_id AND c2u.user_id = ' . (int)$user_id;

      }
    }
    $data = $this->db->query($sql);

    return $data;
  }

  public function getListByUserId($user_id)
  {
    $clinic_params = new ClinicSearchParams();
    $clinic_params->registry_user_id = $user_id;

    return $this->getListByClinicSearchParams($clinic_params);
  }

  public function setRateAndAdviceRateById($clinic_id, $rate, $advice_rate)
  {
    $clinic = $this->getOneById($clinic_id);
    $clinic->rate = $rate;
    $clinic->advice_rate = $advice_rate;
    $clinic->save();
  }

  public function getFavoriteListByAccountId($account_id)
  {
    $sql = 'SELECT c.*
                    FROM clinic c
                    INNER JOIN my_clinic m ON c.id = m.clinic_id
                    WHERE m.account_id = ' . (int)$account_id . '
                    AND c.is_active = 1
                    ORDER BY m.dt DESC';

    $data = $this->db->query($sql);

    return (isset($data)) ? $this->initList($data) : array();
  }

  public function getMyClinicListBySearchParams(MyClinicSearchParams $params)
  {
    $sql = 'SELECT DISTINCT c.*
                    FROM clinic c
                    INNER JOIN my_clinic m ON c.id = m.clinic_id ';

    if ($params->purpoise_of_visit) {
      $sql .= ' INNER JOIN doctor_to_clinic d2c ON d2c.clinic_id = m.clinic_id
                          INNER JOIN purpose_of_visit_to_doctor pv2d ON pv2d.doctor_id = d2c.doctor_id';
    }

    $sql .= ' WHERE m.account_id = ' . (int)$params->account_id;

    if ($params->purpoise_of_visit) {
      $sql .= ' AND pv2d.purpose_of_visit_id = ' . (int)$params->purpoise_of_visit;
    }

    if ($params->type_of_clinic == 'children') {
      $sql .= '   AND c.is_children = 1';
    } elseif ($params->type_of_clinic == 'handicapped') {
      $sql .= '   AND c.is_handicapped = 1';
    } elseif ($params->type_of_clinic == 'pregnant') {
      $sql .= '   AND c.is_pregnant = 1';
    } elseif ($params->type_of_clinic == 'day_night') {
      $sql .= '   AND c.is_day_and_night = 1';
    }

    $sql .= ' AND c.is_active = 1 ';

    $sql .= ' ORDER BY m.dt DESC
                      LIMIT ' . (int)$params->offset . ', ' . (int)$params->limit;

    $data = $this->db->query($sql);

    return (isset($data)) ? $this->initList($data) : array();
  }

  /**
   * Получение списка id клиник, которые находятся в указанном радиусе
   */
  public function getIdListByCoordinatesAndDistance(GeoPoint $coordinates, $distance)
  {
    $sql = 'SELECT id
                    FROM clinic
                    WHERE (6372795 * 2 * asin(
                        sqrt(
                            pow(sin((latitude - ' . $coordinates->getLatitude() . ')*PI()/360),2) +
                            cos(latitude*PI()/180)*cos(' . $coordinates->getLatitude() . '*PI()/180)*
                            pow(sin((longitude - ' . $coordinates->getLongitude() . ')*PI()/360),2)
                            )
                        )
                    )  <= ' . (int)$distance;

    $data = $this->db->query($sql);


    $result = array();
    if ($data) {
      foreach ($data as $v) {
        $result[$v['id']] = $v['id'];
      }
    }

    return $result;
  }

  /**
   * @return ClinicModel[]
   */
  public function getListByCoordinatesAndRadiusDistance(GeoPoint $coordinates, $radius_distance)
  {
    $sql = 'SELECT *
                    FROM clinic
                    WHERE (6372795 * 2 * asin(
                        sqrt(
                            pow(sin((latitude - ' . $coordinates->getLatitude() . ')*PI()/360),2) +
                            cos(latitude*PI()/180)*cos(' . $coordinates->getLatitude() . '*PI()/180)*
                            pow(sin((longitude - ' . $coordinates->getLongitude() . ')*PI()/360),2)
                            )
                        )
                    )  <= ' . (int)$radius_distance;

    $data = $this->db->query($sql);

    return $this->initList($data);
  }

  public function getIdListBySearchParams(SearchParams $search_params)
  {
    $clinics = $this->getListBySearchParams($search_params);

    $result = array();

    if ($clinics) {
      foreach ($clinics as $clinic) {
        $result[$clinic->getId()] = $clinic->getId();
      }
    }

    return $result;
  }

  /**
   * @return ClinicModel[]
   */
  public function getListByPastVisitSearchParams(PastClinicVisitSearchParams $params)
  {
    $sql = 'SELECT DISTINCT c.*
                    FROM clinic c
                    INNER JOIN visit v ON v.clinic_id = c.id
                    INNER JOIN schedule s ON v.schedule_id = s.id
                    ';

    $sql .= ' WHERE v.account_id = ' . (int)$params->account_id;

    if ($params->type_of_clinic == 'children') {
      $sql .= '   AND c.is_children = 1';
    } elseif ($params->type_of_clinic == 'handicapped') {
      $sql .= '   AND c.is_handicapped = 1';
    } elseif ($params->type_of_clinic == 'pregnant') {
      $sql .= '   AND c.is_pregnant = 1';
    } elseif ($params->type_of_clinic == 'day_night') {
      $sql .= '   AND c.is_day_and_night = 1';
    }

    $sql .= ' AND c.is_active = 1 ';

    if ($params->purpoise_of_visit) {
      $sql .= ' AND v.purpose_of_visit_id = ' . (int)$params->purpoise_of_visit;
    }

    $sql .= ' AND v.status_id = ' . VisitModel::CONFIRMED;
    $sql .= ' AND v.visit_start_time < NOW() ';
    $sql .= ' ORDER BY v.visit_start_time DESC ';

    if ($params->limit) {
      $sql .= ' LIMIT ' . (int)$params->offset . ', ' . $params->limit;
    }

    $data = Register::get('db')->query($sql);

    return $this->initList($data);
  }

  /**
   * @return ClinicModel[]
   */
  public function getListByPastVisitAndMyDoctorsByAccountId($account_id)
  {
    $sql = 'SELECT * FROM (
							SELECT DISTINCT c.*
							FROM clinic c
							INNER JOIN schedule s ON s.clinic_id = c.id
							INNER JOIN visit v ON s.visit_id = v.id
							WHERE v.account_id = ' . (int)$account_id . '
                            AND c.is_active = 1

							UNION

							SELECT DISTINCT c.*
							FROM clinic c
							INNER JOIN doctor_to_clinic d2c ON d2c.clinic_id = c.id
							INNER JOIN my_clinic myc ON myc.clinic_id = d2c.clinic_id
							WHERE myc.account_id = ' . (int)$account_id . '
							AND c.is_active = 1
                        ) a ORDER BY name';

    $data = Register::get('db')->query($sql);

    return (count($data)) ? $this->initList($data) : array();
  }

  /**
   * @return ClinicModel
   */
  public function getOneByCityId($city_id)
  {
    $sql = 'SELECT *
                    FROM ' . $this->table_name . '
                    WHERE city_id = ' . (int)$city_id;

    $data = $this->db->query($sql);

    return (isset($data[0])) ? $this->initOne($data[0]) : null;
  }


  /**
   * @return ClinicModel[]
   */
  public function getListByCityId($city_id)
  {
    $sql = 'SELECT *
                    FROM ' . $this->table_name . '
                    WHERE city_id = ' . (int)$city_id . ' AND is_active = 1';
    $data = $this->db->query($sql);

    return (isset($data)) ? $this->initList($data) : array();
  }

    /**
     * @return ClinicModel[]
     */
    public function getIdsWithDocDocIdByCityIds(array $cityIds)
    {
        if (!$cityIds) {
            return [];
        }
        $cityIdsString = implode(',', array_map('intval', $cityIds));
        $sql = 'SELECT id
                    FROM ' . $this->table_name . '
                    WHERE city_id IN(' . $cityIdsString . ') AND is_active = 1 AND docdoc_id IS NOT NULL';
        $data = $this->db->query($sql);


        $res = [];

        foreach ($data as $item) {
            $res[$item['id']] = $item['id'];
        }

        return $res;
    }

  public function checkExistsByCityId($city_id)
  {
    $sql = 'SELECT COUNT(*) as `count`
					FROM clinic cl
					WHERE cl.is_active = 1
						AND cl.city_id = ' . (int)$city_id;
    $data = $this->db->query($sql);

    return (bool)$data[0]['count'];
  }

  /**
   * @return ClinicModel[]
   */
  public function getActiveList()
  {
    $data = $this->orm_model->select()->where('is_active = 1')->fetchAll();

    return $this->initList($data);
  }

  /**
   * @return ClinicModel[]
   */
  public function getListByNameWithLimit($name, $by_page)
  {
    $sql = 'SELECT *
                FROM ' . $this->table_name . '
                WHERE name LIKE  "%' . $this->db->escape($name) . '%"
                LIMIT 0,' . $by_page;

    $data = $this->db->query($sql);

    return (isset($data)) ? $this->initList($data) : array();
  }

  /**
   * @return ClinicModel[]
   */
  public function getListByNameWithPaging($query, $by_page, $page, $get_extra_entry = 0)
  {
    $offset = ($page - 1) * $by_page;
    if ($get_extra_entry) {
      $by_page++;
    }
    $sql = 'SELECT SQL_CALC_FOUND_ROWS *
                    FROM ' . $this->table_name . '
                    WHERE name LIKE  "%' . $this->db->escape($query) . '%"
                    LIMIT ' . $offset . ',' . $by_page;

    $data = $this->db->query($sql);

    return (isset($data)) ? $this->initList($data) : array();
  }

  public function setBallsById($id, $balls)
  {
    $sql = 'UPDATE ' . $this->table_name . '
                    SET balls = ' . (int)$balls . '
                    WHERE id = ' . $id;

    $this->db->query($sql);
  }

  /**
   * @return ClinicModel
   */
  public function getOneByRegistryUserId($registry_user_id)
  {
    $sql = 'SELECT c.*
					FROM clinic c
					INNER JOIN clinic_to_user c2u ON c2u.clinic_id = c.id
					INNER JOIN user u ON c2u.user_id = u.id
					WHERE c2u.user_id = ' . (int)$registry_user_id . '
						AND u.role_id = ' . RoleModel::ACCOUNT_REGISTRY . '
					LIMIT 1';

    $data = $this->db->query($sql);

    return ($data) ? $this->initOne($data[0]) : null;
  }

  /**
   * @return ClinicModel[]
   */
  public function getListByManagerUserId($manager_user_id, $clinics_access = false)
  {
    $user_manager = new UserManager();
    $user = $user_manager->getOneById($manager_user_id);

    if ($user->role_id == RoleModel::ACCOUNT_SUPER_MANAGER) {
      return $this->getList();
    }
    if ($clinics_access) {
      $clinic_search_params = new ClinicSearchParams();
      $clinic_search_params->is_region = null;

      return $this->getListByClinicSearchParams($clinic_search_params);
    }

    $sql = 'SELECT c.*
					FROM clinic c
					INNER JOIN clinic_to_user c2u ON c2u.clinic_id = c.id
					INNER JOIN user u ON c2u.user_id = u.id
					WHERE c2u.user_id = ' . (int)$manager_user_id . '
						AND u.role_id IN (' . join(', ', RoleHelper::getManagerRolesIdList()) . ')';
    $data = $this->db->query($sql);

    return $this->initList($data);
  }

  /**
   * @return ClinicModel
   */
  public function getOneFirstByUserIdAndDoctorId($user_id, $doctor_id)
  {
    $sql = 'SELECT c.*
					FROM clinic c
					INNER JOIN clinic_to_user c2u ON c2u.clinic_id = c.id
					INNER JOIN doctor_to_clinic d2c ON d2c.clinic_id = c.id
					WHERE d2c.doctor_id = ' . (int)$doctor_id . '
						AND d2c.clinic_id = c.id
						AND c2u.user_id = ' . (int)$user_id . '
					LIMIT 1';

    $data = $this->db->query($sql);

    return ($data) ? $this->initOne($data[0]) : null;
  }

  /**
   * @return ClinicModel[]
   */
  public function getListWithPagingByRegistryUserId($registry_user_id, $page = 1, $by_page = 5)
  {
    $clinic_search_params = new ClinicSearchParams();
    $clinic_search_params->registry_user_id = $registry_user_id;
    $clinic_search_params->page = $page;
    $clinic_search_params->by_page = $by_page;

    return $this->getListByClinicSearchParams($clinic_search_params);
  }

  /**
   * @param ModelSearchCriteria $criteria
   *
   * @return int
   */
  public function getCountByModelSearchCriteria(ModelSearchCriteria $criteria)
  {
    $search = new ElasticSearchClinicIndexControl();

    return $search->getTotalCount($criteria);
  }

  /**
   * @return ClinicModel[]
   */
  public function getListByClinicSearchParams(ClinicSearchParams $clinic_search_params, $get_total_hits = false)
  {

    $clinic_index_manager = new ElasticSearchClinicIndexControl();
    try {
      $result = $clinic_index_manager->search($clinic_search_params, $get_total_hits);
    }catch(Exception $e) {
        if (debug) {
            print_r($e);
//            $e->getMessage());
        }
      $result = [];
    }
    $this->total_hits = $clinic_index_manager->getTotalHits();

    return $this->getListByIds($result);
    //устарело
/*
    $search_params = new SearchParams();

    if ($clinic_search_params->registry_user_id) {
      $user_manager = new UserManager();
      // @var UserModel $user
      $user = $user_manager->getOneById($clinic_search_params->registry_user_id);

      if ($user) {
        if ($user->role_id != RoleModel::ACCOUNT_SUPER_MANAGER) {
          $search_params->addJoin('clinic_to_user', 'clinic_to_user.clinic_id', 'clinic.id');
          $search_params->addParam('clinic_to_user.user_id', $clinic_search_params->registry_user_id);
        }
      }
    }

    if ($clinic_search_params->freelancer_id) {
      // @var UserModel $user
      if ($clinic_search_params->registry_user_id && $user->role_id != RoleModel::ACCOUNT_SUPER_MANAGER) {

        $search_params->addJoin('clinic_to_user c2u2', 'c2u2.clinic_id', 'clinic_to_user.clinic_id');
        $search_params->addParam('c2u2.user_id', $clinic_search_params->freelancer_id);
      } else {
        $search_params->addJoin('clinic_to_user c2u2', 'c2u2.clinic_id', 'clinic.id');
        $search_params->addParam('c2u2.user_id', $clinic_search_params->freelancer_id);
      }
    }

    if ($clinic_search_params->doctor_id) {
      $search_params->addJoin('doctor_to_clinic', 'doctor_to_clinic.clinic_id', 'clinic.id');
      $search_params->addParam('doctor_to_clinic.doctor_id', $clinic_search_params->doctor_id);
    }


    $offset = ($clinic_search_params->page - 1) * $clinic_search_params->by_page;
    $limit = $clinic_search_params->by_page;
    $search_params->setOffsetAndLimit($offset, $limit);

    if (isset($clinic_search_params->is_active)) {
      $search_params->addParam('is_active', $clinic_search_params->is_active);
    }

    if (isset($clinic_search_params->is_region) && $clinic_search_params->is_region) {
      $search_params->addParam('is_region', $clinic_search_params->is_region);
    }

    if ($clinic_search_params->status) {
      $search_params->addParam('clinic_status_id', $clinic_search_params->status);
    }

    if ($clinic_search_params->clinic_name) {
      $search_params->addParam('name LIKE', '%' . str_replace('%', '\%', $clinic_search_params->clinic_name) . '%', 'name');
    }

    if ($clinic_search_params->address) {
      $search_params->addParam('address LIKE', '%' . str_replace('%', '\%', $clinic_search_params->address) . '%', 'address');
    }

    if ($clinic_search_params->regions) {
      $search_params->addParam('city_id !=', CityModel::MOSCOW_ID);
    }

    if ($clinic_search_params->city_id) {
      $search_params->addParam('city_id =', $clinic_search_params->city_id);
    }

    if ($clinic_search_params->publish_date_from) {
      $search_params->addParam('date_publish >=', $clinic_search_params->publish_date_from);
    }

    if ($clinic_search_params->publish_date_to) {
      $search_params->addParam('date_publish <=', $clinic_search_params->publish_date_to);
    }

    $specialty_id_list = array();

    if ($clinic_search_params->specialty_id) {
      $specialty_manager = new SpecialtyManager();
      // @var SpecialtyModel $specialty
      $specialty = $specialty_manager->getOneById($clinic_search_params->specialty_id);

      $specialty_id_list[] = $specialty->getId();

      $search_params->addJoin('specialty_to_clinic');
      if ($specialty->childs) {
        $search_params->addSortParam('specialty_to_clinic.specialty_id', array($specialty->getId()), 'DESC');
        foreach ($specialty->childs as $child_specialty) {
          $specialty_id_list[] = $child_specialty->getId();
        }
      }

      $search_params->addParam('specialty_to_clinic.specialty_id IN', $specialty_id_list);
    }

    if ($clinic_search_params->purpose_of_visit_id) {
      $search_params->addJoin('purpose_of_visit_to_clinic');
      $search_params->addParam('purpose_of_visit_to_clinic.purpose_of_visit_id', $clinic_search_params->purpose_of_visit_id);

      if ($clinic_search_params->specialty_id) {
        $search_params->addParam('purpose_of_visit_to_clinic.specialty_id IN', $specialty_id_list);
      }
    }

    if ($clinic_search_params->children || $clinic_search_params->handicapped || $clinic_search_params->pregnant || $clinic_search_params->day_and_night) {
      $search_params->startBracket();
      if ($clinic_search_params->children) {
        $search_params->addParam('is_children OR', 1);
      }

      if ($clinic_search_params->handicapped) {
        $search_params->addParam('is_handicapped OR', 1);
      }

      if ($clinic_search_params->pregnant) {
        $search_params->addParam('is_pregnant OR', 1);
      }

      if ($clinic_search_params->day_and_night) {
        $search_params->addParam('is_day_and_night OR', 1);
      }
      $search_params->endBracket();
    }

    if ($clinic_search_params->not_show_example) {
      $search_params->addParam('id !=', 80);
    }

    if ($clinic_search_params->geo_point) {
      $search_params->setDistance($clinic_search_params->distance);

      if ($clinic_search_params->is_metro) {
        $metro_branch_manager = new MetroBranchManager();
        $branch = $metro_branch_manager->getOneByName($clinic_search_params->metro_branch_name);

        if ($branch) {
          $metro_station_manager = new MetroStationManager();
          $station = $metro_station_manager->getOneByNameAndMetroBranchId($clinic_search_params->metro_station_name, $branch->getId());

          if ($station) {
            $number_from = ($station->number - 3 > 0) ? $station->number - 3 : 1;
            $number_to = $station->number + 3;

            $metro_stations = $metro_station_manager->getListByMetroBranchIdAndNumberRange($branch->getId(), $number_from, $number_to);

            foreach ($metro_stations as $metro_station) {
              $search_params->addDistanceParam(new GeoPoint($metro_station->latitude, $metro_station->longitude), 'clinic.latitude', 'clinic.longitude', $clinic_search_params->distance);
            }
          }
        }
      }

      $search_params->addDistanceParam($clinic_search_params->geo_point, 'clinic.latitude', 'clinic.longitude', $clinic_search_params->distance);
    }


    if ($clinic_search_params->sort_by) {
      switch ($clinic_search_params->sort_by) {
        case 'dt_edit':
          $search_params->addSortParam('dt_edit', 'DESC');
          break;
        case 'date_publish':
          $search_params->addSortParam('date_publish', 'DESC');
          break;
        case 'dt_publish':
          $search_params->addSortParam('dt_publish', 'DESC');
          break;
        case 'raw_clinics_in_start':
          $search_params->addSortParam('clinic_status_id', array(ClinicStatusModel::RAW), 'DESC');
          break;
        case 'recomend':
          $search_params->addSortParam('balls', 'DESC');
          break;
        case 'rate':
          $search_params->addSortParam('rate', 'DESC');
          break;
      }
    }

    $search_params->setGetExtraEntry();

    if ($clinic_search_params->calc_found_rows) {
      $search_params->calcFoundRows();
    }

    return $this->getListBySearchParams($search_params);
    */
  }

  public function setAdultFlagToClinic()
  {
    $sql = 'UPDATE clinic c
                    SET c.is_adult = 1
                    WHERE c.is_children != 1';
    $this->db->query($sql);
  }


  /**
   * @return ClinicModel[]
   */
  public function getListByDoctorIdAndSpecialtyId($doctor_id, $specialty_id)
  {
    $sql = 'SELECT DISTINCT c.*
                    FROM `' . $this->table_name . '` c
                    INNER JOIN doctor_specialty_to_clinic dtc  ON dtc.clinic_id = c.id
                    WHERE dtc.doctor_id = ' . (int)$doctor_id . '
                        AND dtc.specialty_id = ' . (int)$specialty_id . '
                    	AND c.is_active = 1';
    $data = $this->db->query($sql);

    return (isset($data)) ? $this->initList($data) : array();
  }

  /**
   * @param $doctor_id
   * @param $user_id
   *
   * @return ClinicModel[]
   */
  public function getListByDoctorIdAndUserId($doctor_id, $user_id)
  {
      $sql = <<<SQL
        SELECT c.*
        from doctor_to_clinic as dtc
        left join clinic as c on (c.id = dtc.clinic_id)
        where dtc.doctor_id = '$doctor_id'
SQL;

      $data = $this->db->query($sql);
      return (isset($data)) ? $this->initList($data) : [];
  }


  /**
   * @return ClinicModel[]
   */
  public function getListByDoctorIdAndSpecialtyIdList($doctor_id, $specialties_ids)
  {
    $counter = 1;
    $specialties = '';
    foreach ($specialties_ids as $specialty_id) {
      if ($counter != 1) {
        $specialties .= ',';
      }
      $specialties .= $specialty_id;
      $counter++;
    }

    $sql = 'SELECT DISTINCT c.*
                    FROM `' . $this->table_name . '` c
                    INNER JOIN doctor_specialty_to_clinic dtc  ON dtc.clinic_id = c.id
                    WHERE dtc.doctor_id = ' . (int)$doctor_id . '
                        AND dtc.specialty_id IN (' . $specialties . ')
                    	AND c.is_active = 1';
    $data = $this->db->query($sql);

    return (isset($data)) ? $this->initList($data) : array();
  }

  /**
   * @return ClinicModel[]
   */
  public function getRegionsListByNameWithLimit($name, $by_page)
  {
    $sql = 'SELECT *
                FROM ' . $this->table_name . '
                WHERE name LIKE  "%' . $this->db->escape($name) . '%"
                AND city_id != ' . (int)CityModel::MOSCOW_ID . '
                LIMIT 0,' . $by_page;

    $data = $this->db->query($sql);

    return (isset($data)) ? $this->initList($data) : array();
  }

  public function getDistinctPublishDatesByCityId($city_id)
  {
    $sql = 'SELECT DISTINCT(date_publish) as date_publish
                    FROM clinic c
                    WHERE c.is_region = 1';

    if ($city_id) {
      $sql .= ' AND c.city_id = ' . $city_id;
    }

    $sql .= ' AND c.date_publish IS NOT NULL
                    ORDER BY date_publish DESC';

    $data = $this->db->query($sql);

    $result = array();

    if ($data) {
      foreach ($data as $row) {
        if ($row['date_publish']) {
          $result[] = $row['date_publish'];
        }
      }
    }

    return $result;
  }

  public function getDistinctPublishDatesByUserIdAndCityId($user_id, $city_id)
  {
    $sql = 'SELECT DISTINCT(date_publish) as date_publish
                    FROM clinic c
                    INNER JOIN clinic_to_user c2u ON c2u.clinic_id = c.id
                    WHERE c2u.user_id = ' . (int)$user_id . '
                    AND c.is_region = 1';

    if ($city_id) {
      $sql .= ' AND c.city_id = ' . $city_id;
    }

    $sql .= ' AND c.date_publish IS NOT NULL
                    ORDER BY date_publish DESC';

    $data = $this->db->query($sql);

    $result = array();

    if ($data) {
      foreach ($data as $row) {
        if ($row['date_publish']) {
          $result[] = $row['date_publish'];
        }
      }
    }

    return $result;
  }

  /**
   * @param $yandex_url
   *
   * @return ClinicModel
   */
  public function getOneByYandexUrl($yandex_url)
  {
    $data = $this->orm_model->select()->where('yandex_url = ?', $yandex_url)->fetchOne();

    return $this->initOne($data);
  }

    public function getOneByOriginalAlias($original_alias)
    {
        if (!$original_alias)
            return false;

        $data = $this->orm_model->select()->where('original_alias = ?', $original_alias)->fetchOne();
        return $this->initOne($data);
    }

  public function getListByNameOrAddress($query)
  {
    $query = '%' . $query . '%';

    $sql = 'SELECT clinic.*
                    FROM clinic
                    INNER JOIN city ON clinic.city_id = city.id
                    WHERE clinic.name LIKE "' . $query . '"
                        OR address LIKE "' . $query . '"
                        OR city.name LIKE "' . $query . '"';

    $data = $this->db->query($sql);

    return (isset($data)) ? $this->initList($data) : array();
  }

  // Для добавления в регистратуре специализаций всем врачам, не только активным
  public function getAllByDoctorId($doctor_id)
  {
    $sql = 'SELECT DISTINCT c.*
                    FROM `' . $this->table_name . '` c
                    LEFT JOIN doctor_to_clinic dtc  ON dtc.clinic_id = c.id
                    WHERE dtc.doctor_id = ' . (int)$doctor_id;

    $data = $this->db->query($sql);

    return (isset($data)) ? $this->initList($data) : array();
  }

  /**
   * Метод необходим для получения географических границ, в которых расположены объекты
   *
   * @param Address
   */
  public function getBoundsByAddress(Address $address)
  {
    $sql = 'SELECT  MIN(c.latitude) as min_latitude,
                            MAX(c.latitude) as max_latitude,
                            MIN(c.longitude) as min_longitude,
                            MAX(c.longitude) as max_longitude
                    FROM clinic c';

    if ($address->metro_station_id) {
        $sql .= ' INNER JOIN metro_station_to_clinic mstc ON mstc.clinic_id=c.id '
            . ' WHERE mstc.metro_station_id = ' . (int)$address->metro_station_id;
    } elseif ($address->street_id) {
      $sql .= ' WHERE c.street_id = ' . (int)$address->street_id;
    } elseif ($address->region_id) {
      $sql .= ' WHERE c.region_id = ' . (int)$address->region_id;
    } elseif ($address->district_id) {
      $sql .= ' INNER JOIN region r ON c.region_id = r.id
                          WHERE r.district_id = ' . (int)$address->district_id;
    }

    $sql .= ' AND c.is_active = 1';

    $data = $this->db->query($sql);

    return $data[0];
  }

  /**
   * Метод необходим для получения координатного окна, в котором располжены объекты
   *
   * @param GeoPoint $geo_point
   * @param float $distance
   *
   * @return array
   */
  public function getBoundsByGeoPointAndDistance(GeoPoint $geo_point, $distance)
  {
    $search = new ElasticSearchClinicIndexControl();

    $clinics = $search->getListByGeoPointAndDistance($geo_point, $distance);

    $i = 0;
    while (!$clinics && ($i < 4)) {
      $distance *= 4;
      $clinics = $search->getListByGeoPointAndDistance($geo_point, $distance);
      $i++;
    }

    $bounds = array();
    $bounds['min_latitude'] = 10000;
    $bounds['max_latitude'] = 0;
    $bounds['min_longitude'] = 1000;
    $bounds['max_longitude'] = 0;

    foreach ($clinics as $clinic) {
      if ($clinic['geo_point']['lat'] < $bounds['min_latitude']) {
        $bounds['min_latitude'] = $clinic['geo_point']['lat'];
      }

      if ($clinic['geo_point']['lon'] < $bounds['min_longitude']) {
        $bounds['min_longitude'] = $clinic['geo_point']['lon'];
      }

      if ($clinic['geo_point']['lat'] > $bounds['max_latitude']) {
        $bounds['max_latitude'] = $clinic['geo_point']['lat'];
      }

      if ($clinic['geo_point']['lon'] > $bounds['max_longitude']) {
        $bounds['max_longitude'] = $clinic['geo_point']['lon'];
      }
    }

    return $bounds;
  }

  public function getListByCityIdWithoutMetroStationId($city_id)
  {
    $sql = 'SELECT *
                    FROM clinic
                    WHERE city_id = ' . (int)$city_id . '
                    AND is_active = 1
                    AND (metro_station_id = 0
                    OR metro_station_id IS NULL)';
    $data = $this->db->query($sql);

    return $this->initList($data);
  }

  public function getClinicRedirectList()
  {
    $data = $this->orm_model->select()->where('redirect_list = 1')->fetchAll();

    return $this->initList($data);
  }


    /**
     * @param ModelSearchCriteria $criteria
     *
     * @return ClinicModel[]
     */
    public function getListByModelSearchCriteria(ModelSearchCriteria $criteria)
    {
        /**
         * @var ClinicSearchCriteria $criteria
         */

        $search_params = $criteria->getSearchParams();


        if (!$search_params) {
            $search_params = new SearchParams();
        }

        if (!empty($criteria->by_page)) {
            $limit = $criteria->by_page + 1;
            $offset = ($criteria->page - 1) * $criteria->by_page;

            $search_params->setOffsetAndLimit($offset, $limit);
        }

        if (!empty($criteria->name)) {
            $search_params->addParam('name', $criteria->name, '', ['w_mask'=>'both']);
        }

        if (!empty($criteria->alias)) {
            $search_params->addParam('alias', $criteria->alias, '', ['w_mask'=>'both']);
        }

        $res = $this->getListBySearchParams($search_params);
        return $res;
    }

    public function getClinicsByServicesID($services_id, $limit = 10, $offset = 0, $orderby = 'c.alias ASC') {
        // корневые клиники привязанные к услуге
        $q = sprintf('SELECT c.*,s2c.price AS service_price FROM service_to_clinic s2c
                    INNER JOIN clinic c ON s2c.clinic_id = c.id
                    WHERE s2c.service_id = %d
                        AND c.city_id = %d
                    GROUP BY s2c.clinic_id
                    ORDER BY %s LIMIT %d OFFSET %d', (int)$services_id, $this->cityID, $orderby, $limit, $offset);

        $q = $this->db->query($q);

        $results = [];
        if(!empty($q)){
            
            foreach($q as $root){
                $results[$root['id']] = $root;
            }
        }

        return $this->initList($results);
    }

    public function getClinics($clinics_ids) {

        is_array($clinics_ids) AND $clinics_ids = implode(', ', array_map('intval', $clinics_ids));

        $fields = [
            'c.id',
            'c.name',
            'c.alias',
            'c.address',
            'c.latitude',
            'c.longitude',
            'c.rate',
            'c.fact_address',
            'c.metro_station_id',
            'c.phone',
            'c.email',
            'c.is_active',
            'GROUP_CONCAT(ct.id) as type_id',
            'GROUP_CONCAT(ct.name) as type_name'
        ];

        // SELECT c.* FROM `clinic` c inner join clinic_to_types c2t ON c.id = c2t.clinic_id inner join clinic_type ct ON c2t.clinic_type_id = ct.id limit 100
        $q = str_replace(['{ids}', '{fields}'], [
            $clinics_ids,
            implode(', ', $fields),
        ], 'SELECT {fields} FROM clinic c
                LEFT JOIN clinic_to_types c2t ON c.id = c2t.clinic_id
                LEFT JOIN clinic_type ct ON c2t.clinic_type_id = ct.id
                WHERE c.id IN ({ids}) 
                GROUP BY c.id
                ORDER BY c.alias ASC');

        $q = $this->db->query($q);

        return $this->initList($q);

    }

    public function getAutocomplete($search) {

        $fields = [
            'c.id',
            'c.name',
            'c.alias',
        ];

        // SELECT c.* FROM `clinic` c inner join clinic_to_types c2t ON c.id = c2t.clinic_id inner join clinic_type ct ON c2t.clinic_type_id = ct.id limit 100
        $q = str_replace(['{search}', '{fields}'], [
            '%'.$search.'%',
            implode(', ', $fields),
        ], 'SELECT {fields} FROM clinic c WHERE c.name LIKE \'{search}\' OR c.alias LIKE \'{search}\' ORDER BY c.alias ASC LIMIT 50');

        $q = $this->db->query($q);

        if(!empty($q)){

            $data = [];
            foreach($q as $row){
                $data[] = [
                    'code' => $row['id'],
                    'value' => $row['name'].' ('.$row['alias'].')'
                ];
            }

            return $data;
        }

        return [];
    }

    public function deleteById($id)
    {
        ElasticaTask::deleteClinic($id);
        parent::deleteById($id);
    }
}
