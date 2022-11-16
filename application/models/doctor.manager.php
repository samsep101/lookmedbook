<?php

class DoctorManager extends AliasManager
{
  protected $table_name = 'doctor';
  protected $model_name = 'DoctorModel';

  protected $transliterated_field = 'full_fio';

  private $doctor_info;

  public function afterSave(DoctorModel $model)
  {
    /**
     * @var DoctorInfoManager $doctor_info_manager
     * @var DoctorInfoModel $doctor_info
     */
    $fields = array(
      'education',
      'course',
      'certificate',
      'academic_title'
    );

    foreach ($fields as $item) {
      if (!$this->doctor_info) {
        $doctor_info_manager = ModelManagerFactory::getByName('doctor_info');
        $doctor_info = $doctor_info_manager->getOneByDoctorId($model->getId());

        if ($doctor_info) {
          $this->doctor_info = $doctor_info;
        } else {
          $this->doctor_info = new DoctorInfoModel();
          $this->doctor_info->doctor_id = $model->getId();
        }
      }

      $this->doctor_info->{$item} = $model->{$item};
    }

    if ($this->doctor_info) {
      $this->doctor_info->save();
    }

      ElasticaTask::indexDoctor($model->id);
  }

  public function getActiveListByClinicId($clinic_id)
  {
    $db = Register::get('db');

      $sql = 'SELECT *
                    FROM doctor
                    WHERE id IN (
                        SELECT doctor_id
                        FROM doctor_specialty_to_clinic
                        WHERE clinic_id = ' . (int)$clinic_id . '
                    )
                    AND doctor.is_active = 1';

    $data = $db->query($sql);

    return (count($data)) ? $this->initList($data) : array();
  }

  /**
   * @return DoctorModel[]
   */
  public function getListByClinicId($clinic_id)
  {
    $sql = 'SELECT SQL_NO_CACHE d.*, d2c.clinic_id, d2c.specialty_id, d2c.first_visit_price, d2c.second_visit_price
                    FROM doctor d
                    INNER JOIN doctor_to_clinic d2c ON d2c.doctor_id = d.id
                    WHERE d2c.clinic_id = ' . (int)$clinic_id . '
                    ORDER BY d.last_name ASC';

    $data = $this->db->query($sql);

    return (count($data)) ? $this->initList($data) : array();
  }

  public function getListByCityId($city_id)
  {
    $data = array();
    $city_id = intval($city_id);

    if ($city_id) {
      $sql = 'SELECT DISTINCT d.*
                FROM `doctor` AS d
                LEFT JOIN `specialty_to_doctor` AS std ON d.id = std.doctor_id
                INNER JOIN `specialty` AS s ON s.id = std.specialty_id
                RIGHT JOIN `doctor_to_clinic` AS dtc ON dtc.doctor_id = d.id
                LEFT JOIN `clinic` AS clnc ON dtc.clinic_id = clnc.id
                INNER JOIN `city` AS ct ON clnc.city_id = ct.id
                WHERE d.is_active = 1
                    AND clnc.is_active = 1
                    AND ct.id = ' . $city_id;
      $data = $this->db->query($sql);
    }

    return (count($data)) ? $this->initList($data) : array();
  }

  /**
   * Возвращает активных докторов
   * (активен, не виртуален, есть имя, привязан к активной клинике).
   * @param int $page
   * @param int $per_page
   * @return DoctorModel[]
   */
  public function getActiveDoctors($page = 1, $per_page = 5000)
  {
      $offset = (int) ($page - 1) * $per_page;
      $limit = (int) $per_page;
      return $this->initList($this->db->query("
          select distinct
            doctor.*
          from doctor
            join doctor_specialty_to_clinic ds2c
              on doctor.id = ds2c.doctor_id
            join clinic
              on clinic.id = ds2c.clinic_id
          where clinic.is_active = 1
                and doctor.is_active = 1
                and (doctor.is_virtual is null or doctor.is_virtual = 0)
                and doctor.full_lower_name <> ''
          limit $limit offset $offset
      "));
  }

  public function getActiveList()
  {
    $data = $this->orm_model->select()->where('is_active = 1 AND first_name != "" AND second_name != "" AND last_name != "" ')->fetchAll();

    return $this->initList($data);
  }

  public function getDoctorsTheListOfIdentifiers($ids = array())
  {
    $data = array();

    if (count($ids) > 0) {
      $data = $this->orm_model->select()->where('is_active = 1 AND id IN(' . implode(', ', $ids) . ') ')->fetchAll();

      return $this->initList($data);
    }

    return count($data) ? $data : array();
  }

  public function setRateAndAdviceRateById($doctor_id, $rate, $advice_rate)
  {
    if (($rate < 1) || ($rate > 5)) {
      $rate = NULL;
    }

    $doctor = $this->getOneById($doctor_id);
    $doctor->rate = $rate;
    $doctor->advice_rate = $advice_rate;

    $doctor->save();
  }

  public function getPrimaryListByHashWithLimit($hash, $limit)
  {
    $show_manager = new DoctorSearchShowManager();

    $doctors_id_list = $show_manager->getPrimaryIdListByHashWithLimit($hash, $limit);

    $result = array();

    if ($doctors_id_list) {
      foreach ($doctors_id_list as $id) {
        $result[] = $this->getOneById($id);
      }
    }

    return $result;
  }

  public function getFavoriteListByAccountId($account_id)
  {
    $sql = 'SELECT d.*
                    FROM doctor d
                    INNER JOIN my_doctor m ON m.doctor_id = d.id
                    WHERE m.account_id = ' . (int)$account_id . '
                    ORDER BY dt DESC';

    $doctors = $this->db->query($sql);

    return $this->initList($doctors);
  }

  public function getFavoriteListBySearchParams(MyDoctorsSearchParams $params)
  {
    $sql = 'SELECT DISTINCT d.*
                    FROM doctor d
                    INNER JOIN my_doctor m ON m.doctor_id = d.id
                    INNER JOIN doctor_to_clinic d2c ON d2c.doctor_id = m.doctor_id';

    if ($params->purpose_of_visit_id) {
      $sql .= ' INNER JOIN purpose_of_visit_to_doctor pv2d ON pv2d.doctor_id = m.doctor_id';
    }

    $sql .= ' WHERE m.account_id = ' . (int)$params->account_id;

    if ($params->specialty_id) {
      $sql .= ' AND d2c.specialty_id = ' . (int)$params->specialty_id;
    }

    if ($params->purpose_of_visit_id) {
      $sql .= ' AND pv2d.purpose_of_visit_id = ' . (int)$params->purpose_of_visit_id;
    }

    if ($params->clinic_id) {
      $sql .= ' AND d2c.clinic_id = ' . (int)$params->clinic_id;
    }

    $sql .= ' ORDER BY dt DESC';

    if ($params->limit) {
      $sql .= ' LIMIT ' . (int)$params->offset . ', ' . $params->limit;
    }

    $doctors = $this->db->query($sql);

    return $this->initList($doctors);
  }

  public function fillRandomSortField()
  {
    $sql = 'UPDATE doctor
                    SET `sort` = RAND()
                    WHERE is_virtual IS NULL
                        AND card_image_id IS NOT  NULL';

    $this->db->query($sql);
  }

  public function deleteByFirstName($first_name)
  {
    $sql = 'DELETE FROM ' . $this->table_name . '
                    WHERE  first_name = "' . $this->db->escape($first_name) . '"';
    $this->db->query($sql);
  }

  /**
   * @return DoctorModel
   */
  public function getOneByFirstName($first_name)
  {
    $db = Register::get('db');

    $sql = 'SELECT *
                    FROM ' . $this->table_name . '
                    WHERE `first_name` = "' . $this->db->escape($first_name) . '"';

    $data = $db->query($sql);

    return (isset($data[0])) ? $this->initOne($data[0]) : NULL;
  }

  /**
   * @param ModelSearchCriteria $criteria
   *
   * @return int
   */
  public function getCountByModelSearchCriteria(ModelSearchCriteria $criteria)
  {
    /**
     * @var DoctorSearchParams $criteria
     */
    $result_query = $this->getResultsForRequestCriteria($criteria);

    return count($result_query);
  }

  public function getResultsForRequestCriteria(ModelSearchCriteria $criteria)
  {
    $criteria = clone $criteria;
    $criteria->by_page = NULL;
    $criteria->page = NULL;

    $search = new ElasticSearchDoctorIndexControl();
    try {
      $res = $search->search($criteria);
    }catch(Exception $e){
      $res = [];
    }
    return $res;
  }

  public function deleteById($id){

        $backup = ( new DoctorManager())->getOneById( $id );

        $deletedDoctors = new DeletedDoctorModel();
        $deletedDoctors->first_name  = $backup->first_name;
        $deletedDoctors->second_name = $backup->second_name;
        $deletedDoctors->last_name   = $backup->last_name;
        $deletedDoctors->look_id     = $backup->id;
        $deletedDoctors->purpose     = "Удаление через панель администратора";
        $deletedDoctors->dt          = date("Y-m-d H:i:s");
        $deletedDoctors->save();

        ElasticaTask::deleteDoctor($id);
        parent::deleteById($id);

  }
  /**
   * @return DoctorModel[]
   */
  public function getListWithPagingByRegistryUserId($registry_user_id, $page = 1, $by_page = 5)
  {
    $doctor_search_params = new DoctorSearchParams();
    $doctor_search_params->registry_user_id = $registry_user_id;
    $doctor_search_params->page = $page;
    $doctor_search_params->by_page = $by_page;
    $doctor_search_params->not_virtual = 1;

    return $this->getListByDoctorSearchParams($doctor_search_params);
  }

  /**
   * @return DoctorModel[]
   */
  public function getListByDoctorSearchParams(DoctorSearchParams $doctor_search_params)
  {
    $search = new ElasticSearchDoctorIndexControl();
    try{
      $ids = $search->search($doctor_search_params);
    }catch(Exception $e){
      $ids = [];
    }

    $this->total_hits = $search->getTotalHits();

    return $this->getListByIds($ids);
  }


  public function getCountByDoctorSearchParams(DoctorSearchParams $doctor_search_params)
  {
      $search = new ElasticSearchDoctorIndexControl();
      try{
          $count = $search->getTotalCount($doctor_search_params);
      }catch(Exception $e){
          $count = 0;
      }

      return $count;
  }


  /**
   * @return DoctorModel[]
   */
  public function getUnboundedListWithPagingByRegistryUserId($registry_user_id, $city_id = NULL, $page = 1, $by_page = 5)
  {
    $doctor_search_params = new DoctorSearchParams();

    $doctor_search_params->registry_user_id = $registry_user_id;
    $doctor_search_params->page = $page;
    $doctor_search_params->by_page = $by_page;
    $doctor_search_params->not_virtual = 1;
    $doctor_search_params->is_has_active_clinic = FALSE;
    if ($city_id && $city_id != 100000) {
      $doctor_search_params->city_id = $city_id;
    } else if ($city_id && $city_id == 100000) {
      $doctor_search_params->is_has_clinic = FALSE;
    }

    return $this->getListByDoctorSearchParams($doctor_search_params);
  }

  /**
   * @return DoctorModel[]
   */
  public function getListByFullLowerNameAndClinicId($query, $clinic_id)
  {
    $sql = 'SELECT *
                    FROM doctor
                    WHERE (
                        SELECT COUNT(*)
                        FROM doctor_to_clinic
                        WHERE doctor_id = doctor.id
                        AND clinic_id = ' . (int)$clinic_id . '
                    )>0
                    AND doctor.full_lower_name LIKE  "%' . $this->db->escape($query) . '%"';

    $data = $this->db->query($sql);

    return (isset($data)) ? $this->initList($data) : array();
  }

  /**
   * @return DoctorModel[]
   */
  public function getListByFullLowerNameWithLimit($full_lower_name, $by_page)
  {
    $sql = 'SELECT *
                FROM ' . $this->table_name . '
                WHERE full_lower_name LIKE  "%' . $this->db->escape($full_lower_name) . '%"
                LIMIT 0,' . $by_page;

    $data = $this->db->query($sql);

    return (isset($data)) ? $this->initList($data) : array();
  }

  /**
   * @return DoctorModel[]
   */
  public function getListByFullLowerNameWithPaging($query, $by_page, $page, $get_extra_entry = 0)
  {
    $offset = ($page - 1) * $by_page;
    if ($get_extra_entry) {
      $by_page++;
    }
    $sql = 'SELECT SQL_CALC_FOUND_ROWS *
                    FROM ' . $this->table_name . '
                    WHERE full_lower_name LIKE  "%' . $this->db->escape($query) . '%"
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

  public function getCountDoctorsInDoctorsListByMetroStationId($metro_station_id, $doctors)
  {
    $sql = 'SELECT *
                    FROM ' . $this->table_name . ' d
                    INNER JOIN doctor_to_clinic d2c ON d2c.doctor_id = d.id
                    INNER JOIN clinic c ON c.id = d2c.clinic_id
                    WHERE c.metro_station_id = ' . (int)$metro_station_id . '
                    AND d.id in (';
    foreach ($doctors as $doctor) {
      $sql .= ' ' . $doctor->getId() . ',';
    }
    $sql .= ')';
    $sql = str_replace(',)', ')', $sql);

    $data = $this->db->query($sql);

    return (count($data)) ? count($data) : NULL;
  }

  public function checkExistsBySpecialtyIdAddressObject($specialty_id, DynamicModel $address_object)
  {
    switch (get_class($address_object)) {
      case 'CityModel':
        return $this->checkExistsBySpecialtyIdAndCityId($specialty_id, $address_object->getId());
        break;
      case 'DistrictModel':
        return $this->checkExistsBySpecialtyIdAndDistrictId($specialty_id, $address_object->getId());
        break;
      case 'RegionModel':
        return $this->checkExistsBySpecialtyIdAndRegionId($specialty_id, $address_object->getId());
        break;
      case 'MetroStationModel':
        return $this->checkExistsBySpecialtyIdAndMetroStationId($specialty_id, $address_object->getId());
        break;
      case 'StreetModel':
        return $this->checkExistsBySpecialtyIdAndStreetId($specialty_id, $address_object->getId());
        break;
    }

    return FALSE;
  }

  public function checkExistsBySpecialtyIdAndCityId($specialty_id, $city_id)
  {
    /**
     * @var CityManager $city_manager
     */
    $city_manager = ModelManagerFactory::getByName('city');
    $city = $city_manager->getOneById($city_id);

    $sql = 'SELECT COUNT(*) as `count`
                    FROM doctor d
                    INNER JOIN doctor_specialty_to_clinic ds2c ON ds2c.doctor_id = d.id
                    INNER JOIN clinic c ON ds2c.clinic_id = c.id
                    INNER JOIN specialty sp ON ds2c.specialty_id = sp.id
                    WHERE
                        sp.id = ' . (int)$specialty_id . '
                        AND (c.city_id = ' . (int)$city_id . '
                                    ';
    if ($city && $city->region == 'Московская область') {
      $sql .= ' OR c.city_id = ' . (int)CityModel::MOSCOW_ID;
    }
    $sql .= ' )

            ';
    $sql .= '
                    LIMIT 1';

    $data = $this->db->query($sql);

    return (bool)$data[0]['count'];
  }

  public function checkExistsBySpecialtyIdAndDistrictId($specialty_id, $district_id)
  {
    $sql = 'SELECT COUNT(*) as `count`
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
                        AND ds2c.specialty_id = ' . (int)$specialty_id . '
                        AND dc.is_active = 1
                    LIMIT 1
            ';

    $data = $this->db->query($sql);

    return (bool)$data[0]['count'];
  }

  public function checkExistsBySpecialtyIdAndRegionId($specialty_id, $region_id)
  {
    $sql = 'SELECT COUNT(*) as `count`
                    FROM doctor dc
                    INNER JOIN doctor_to_clinic d2c ON d2c.doctor_id = dc.id
                    INNER JOIN doctor_specialty_to_clinic ds2c ON ds2c.doctor_id = dc.id
                    INNER JOIN clinic c ON c.id = d2c.clinic_id
                    WHERE
                        c.region_id = ' . (int)$region_id . '
                        AND ds2c.clinic_id = c.id
                        AND ds2c.specialty_id = ' . (int)$specialty_id . '
                        AND dc.is_active = 1
                    LIMIT 1';

    $data = $this->db->query($sql);

    return (bool)$data[0]['count'];
  }

  public function checkExistsBySpecialtyIdAndMetroStationId($specialty_id, $metro_station_id)
  {
    $sql = 'SELECT COUNT(*) as `count`
                    FROM doctor dc
                    INNER JOIN doctor_to_clinic d2c ON d2c.doctor_id = dc.id
                    INNER JOIN doctor_specialty_to_clinic ds2c ON ds2c.doctor_id = dc.id
                    INNER JOIN clinic c ON c.id = d2c.clinic_id
                    WHERE
                        c.metro_station_id = ' . (int)$metro_station_id . '
                        AND ds2c.clinic_id = c.id
                        AND dc.is_active = 1
                        AND ds2c.specialty_id = ' . (int)$specialty_id;

    $data = $this->db->query($sql);

    return (bool)$data[0]['count'];
  }

  public function checkExistsBySpecialtyIdAndStreetId($specialty_id, $street_id)
  {
    $sql = 'SELECT COUNT(*) as `count`
                    FROM doctor dc
                    INNER JOIN doctor_specialty_to_clinic ds2c ON ds2c.doctor_id = dc.id
                    INNER JOIN clinic c ON c.id = ds2c.clinic_id
                    WHERE
                        c.street_id = ' . (int)$street_id . '
                        AND ds2c.clinic_id = c.id
                        AND ds2c.specialty_id = ' . (int)$specialty_id . '
                        AND dc.is_active = 1';

    $data = $this->db->query($sql);

    return (bool)$data[0]['count'];
  }

  public function checkExistsActionBySpecialtyIdAndClinicId($specialty_id, $clinic_id)
  {
    $sql = '  select count(*) as `count`
               from doctor d
               inner join doctor_to_clinic dc on dc.doctor_id=d.id
               inner join specialty_to_doctor sd on (sd.doctor_id=d.id and sd.clinic_id=dc.clinic_id)
               inner join specialty_to_specialization ss on (ss.specialty_id=sd.specialty_id)
               inner join specialization s on (s.id=ss.specialization_id)
               inner join `action` a on (a.clinic_id=dc.clinic_id and now() between a.date_from and a.date_to)
               inner join action_to_specialization asp on (asp.action_id=a.id and asp.specialization_id=s.id)
               where dc.clinic_id='.(int)$clinic_id.' sd.specialty_id='.(int)$specialty_id;

    $data = $this->db->query($sql);

    return (bool)$data[0]['count'];
  }

  public function checkExistsByCityId($city_id)
  {
    $sql = 'SELECT COUNT(*) as `count`
                    FROM doctor d
                    INNER JOIN doctor_specialty_to_clinic ds2c ON ds2c.doctor_id = d.id
                    INNER JOIN clinic c ON ds2c.clinic_id = c.id
                    INNER JOIN specialty sp ON ds2c.specialty_id = sp.id
                    WHERE c.city_id = ' . (int)$city_id;
    $sql .= ' LIMIT 1';

    $data = $this->db->query($sql);

    return (bool)$data[0]['count'];
  }

  public function setAdultFlagToDoctor()
  {
    $sql = 'UPDATE doctor d
                    SET d.is_adult = 1
                    WHERE d.is_children != 1';
    $this->db->query($sql);
  }

  public function getListByClinicIdAndClinicSpecialtiesIds($clinic_id, $clinic_specialties)
  {
    $counter = 1;
    $specialties = '';
    foreach ($clinic_specialties as $clinic_specialty) {
      if ($counter != 1) {
        $specialties .= ',';
      }
      $specialties .= $clinic_specialty->getId();
      $counter++;
    }

    $sql = 'SELECT DISTINCT *
                    FROM doctor d
                    WHERE (
                        SELECT COUNT(*)
                        FROM doctor_specialty_to_clinic
                        WHERE doctor_id = d.id
                        AND specialty_id IN (' . $specialties . ')
                        AND clinic_id = ' . (int)$clinic_id . '
                    )>0
                    AND d.is_active = 1
                    AND d.is_virtual is null';

    $data = $this->db->query($sql);

    return $this->initList($data);
  }

  public function getVirtualDoctorsForClinic($clinic_id)
  {
    $sql = 'SELECT d.*
                    FROM doctor d
                    INNER JOIN doctor_specialty_to_clinic ds2c ON ds2c.doctor_id = d.id
                    WHERE ds2c.clinic_id = ' . (int)$clinic_id . '
                    AND d.is_virtual = 1';

    $data = $this->db->query($sql);

    return $this->initList($data);
  }

  public function deleteVirtualDoctorAfterAddDoctor($specialty_id, $clinic_id)
  {
    $sql = 'DELETE
                    FROM doctor d
                    INNER JOIN doctor_specialty_to_clinic ds2c ON ds2c.doctor_id = d.id
                    INNER JOIN specialty_to_specialization s2s ON s2s.specialty_id = ds2c.specialty_id
                    WHERE s2s.specialty_id = ' . (int)$specialty_id . '
                    AND ds2c.clinic_id = ' . (int)$clinic_id;

    return $this->db->query($sql);
  }

  public function getListByNameAndIsVirtualWithLimit($name, $page, $by_page)
  {
    $sql = 'SELECT *
                    FROM doctor
                    WHERE full_lower_name LIKE "%' . $this->db->escape($name) . '%"
                    AND is_virtual is null
                    LIMIT ' . (int)$page . ', ' . (int)$by_page;

    $data = $this->db->query($sql);

    return (isset($data)) ? $this->initList($data) : array();
  }

  /**
   * @return DoctorModel[]
   */
  public function getNotVirtualListByClinicId($clinic_id)
  {
    $db = Register::get('db');

    $sql = 'SELECT *
                    FROM doctor
                    WHERE (
                        SELECT COUNT(*)
                        FROM doctor_specialty_to_clinic
                        WHERE doctor_id = doctor.id
                        AND clinic_id = ' . (int)$clinic_id . '
                    ) > 0
                    AND doctor.is_active = 1
                    AND doctor.is_virtual is null';

    $data = $db->query($sql);

    return (count($data)) ? $this->initList($data) : array();
  }

  public function getTotalDoctorsForAllRelatedSpecialties($specialty, $doctor_params)
  {
    $data = array(
      'doctors_total_count' => 0,
      'total_doctors' => array()
    );
    $specialties = $this->getRelatedSpecialties($specialty);

    if (!empty($specialties)) {
      $type = $this->getSpecialtiesType($specialties);

      switch ($type) {
        case 1: {
          $specialties = array_merge(array($specialty), $specialties);
          foreach ($specialties AS $sValue) {
            $doctors = $this->doctorsForRelatedSpecialty($doctor_params, $sValue->getId(), 1);
            $data['total_doctors'] = array_merge($data['total_doctors'], $doctors);
          }

          break;
        }
        case 2: {
          $specialties = array($specialty, $specialties);

          foreach ($specialties AS $sValue) {
            $doctors = $this->doctorsForRelatedSpecialty($doctor_params, $sValue->getId());
            $data['total_doctors'] = array_merge($data['total_doctors'], $doctors);
          }

          break;
        }
      }
    }
//            exit;

    $result_doctors = $ids = array();

    foreach ($data['total_doctors'] AS $tdValue) {
      if (!in_array($tdValue->getId(), $ids)) {
        $ids[] = $tdValue->getId();
        $result_doctors[] = $tdValue;
      }
    }


    $data['total_doctors'] = $result_doctors;
    $data['doctors_total_count'] = count($result_doctors);

    return $data;
  }

  public function getRelatedSpecialties($specialty)
  {
    $specialtiesForMainSpecialty = $this->getSpecialtiesRelatedToCurrentSpecialty($specialty);

    if (empty($specialtiesForMainSpecialty)) {
      $mainSpecialtyForCurrentSpecialty = $this->getMainSpecialtyForOneSpecializationCurrentSpecialty($specialty);
    }

    return count($specialtiesForMainSpecialty) > 0 ? $specialtiesForMainSpecialty : (!empty($mainSpecialtyForCurrentSpecialty) ? $mainSpecialtyForCurrentSpecialty : array());
  }

  public function getSpecialtiesRelatedToCurrentSpecialty($specialty)
  {
      if (is_object($specialty)) {
          $specialty = $specialty->getId();
      }
    $specialty_manager = ModelManagerFactory::getByName('specialty');
    $specialization = $specialty_manager->getOneSpecializationByMainSpecialtyId($specialty);
    $specialtiesForMainSpecialization = array();
    $specialtiesForMainSpecializationProcessed = array();

    if (!empty($specialization)) {
      $specialtiesForMainSpecialization = $specialty_manager->getListBySpecializationId($specialization->getId());
    }

    if (!empty($specialtiesForMainSpecialization) && count($specialtiesForMainSpecialization) > 0) {
      foreach ($specialtiesForMainSpecialization AS $sfmsValue) {
        if ($sfmsValue->getId() != $specialty) {
          $specialtiesForMainSpecializationProcessed[] = $sfmsValue;
        }
      }
    }

    return $specialtiesForMainSpecializationProcessed;
  }

  public function getMainSpecialtyForOneSpecializationCurrentSpecialty($specialty)
  {
      /**
       * @var SpecialtyManager $specialty_manager
       */
      $specialty_manager = ModelManagerFactory::getByName('specialty');
      if (!is_object($specialty)) {
          $specialty = $specialty_manager->getOneById($specialty);
      }
    $specialization = $specialty->specializations;
    $main_specialty = array();

    if (count($specialization) == 1) {
      $specialization = $specialization[0];
      $main_specialty = $specialty_manager->getMainOneBySpecializationId($specialization->getId(), $specialty->getId());
      if (!is_array($main_specialty) && !is_null($main_specialty)) {
        $main_specialty = [$main_specialty];
      }
    }

    return $main_specialty;
  }

  public function getSpecialtiesType($specialties)
  {
    if (is_array($specialties)) return 1;
    else if (is_object($specialties)) return 2;
    else return 0;
  }

  public function doctorsForRelatedSpecialty($params, $specialty_id, $setPrimary = 0)
  {
    $params = clone $params;

    $doctor_search_algorithm = new DoctorSearchAlgorithm();

    $params->page = NULL;
    $params->by_page = NULL;
    $params->specialty_id = $specialty_id;

    $search_result = $doctor_search_algorithm->search($params);

    if (count($search_result)) {
      foreach ($search_result AS $rsKey => $rsValue) {
        $search_result[$rsKey]->specialtyIDForDoctorCard = $specialty_id;
      }
    }

    return $search_result;
  }

  public function getDoctorsByClinicId($clinic_id)
  {
    $sql = 'SELECT *
                    FROM doctor
                    WHERE (
                        SELECT COUNT(*)
                        FROM doctor_specialty_to_clinic
                        WHERE doctor_id = doctor.id
                        AND clinic_id = ' . (int)$clinic_id . '
                    ) > 0';

    $data = $this->db->query($sql);

    return (count($data)) ? $this->initList($data) : array();

  }

  protected function beforeSave(DynamicModel $doctor)
  {
    /**
     * @var DoctorModel $doctor ;
     */
    $doctor->full_lower_name = mb_strtolower($doctor->first_name . ' ' . $doctor->second_name . ' ' . $doctor->last_name, 'utf-8');

    if (!$doctor->sex_id) {
      $doctor->sex_id = 0;
    }

    if (!$doctor->is_has_weekend_time) {
      $doctor->is_has_weekend_time = 0;
    }

    if (!$doctor->docdoc_id) {
      $doctor->docdoc_id = null;
    }

    if ($doctor->getOldAttribute('is_active') != $doctor->is_active) {
        $doctor->is_active_updated_at = time();
    }

    parent::beforeSave($doctor);
  }

  public function getByClinics($clinics_ids) {

        is_array($clinics_ids) AND $clinics_ids = implode(', ', array_map('intval', $clinics_ids));

        $fields = [
            'd.id',
            'd.first_name',
            'd.second_name',
            'd.last_name',
            'd.full_lower_name',
            'd.alias',
            'dt.id as type_id',
            'dt.name as type_name',
        ];

        // SELECT c.* FROM `clinic` c inner join clinic_to_types c2t ON c.id = c2t.clinic_id inner join clinic_type ct ON c2t.clinic_type_id = ct.id limit 100
        $q = str_replace(['{ids}', '{fields}'], [
            $clinics_ids,
            implode(', ', $fields),
        ], 'SELECT {fields} FROM doctor d
                INNER JOIN doctor_to_clinic d2c ON d.id = d2c.doctor_id
                LEFT JOIN doctor_type dt ON d.doctor_type_id = dt.id
                WHERE d2c.clinic_id IN ({ids})
                    AND d.is_virtual IS NULL
                    AND (d.is_active = 1 AND d.alias != \'\' AND d.alias IS NOT NULL)
                GROUP BY d.id
                ORDER BY d.alias ASC');

        $q = $this->db->query($q);

        return $this->initList($q);

    }

    public function getOneByDocDocId($docdocId)
    {
        $q = "SELECT *
                          FROM doctor
                          WHERE doctor.docdoc_id=?";

        $doctor = $this->db->query($q, [$docdocId,]);

        return $doctor ? $this->initOne($doctor[0]) : null;
    }

    public function getOneByNameWithoutDocdocId($firstName, $secondName, $lastName)
    {
        $q = "SELECT *
                          FROM doctor
                          WHERE (
                            doctor.docdoc_id IS NULL AND (
                              doctor.last_name LIKE ? AND
                              doctor.first_name LIKE ? AND
                              doctor.second_name LIKE ?
                            )
                          ) LIMIT 1";

        $params = [
            $lastName,
            $firstName,
            $secondName
        ];

        $doctor = $this->db->query($q, $params);

        return $doctor ? $this->initOne($doctor[0]) : null;
    }

    /**
     * @param ClinicModel $clinic
     * @return DoctorModel[] Updated doctors.
     */
    public function updateSingleClinicDoctorWorkingTimeFlags(ClinicModel $clinic)
    {
        $w = (string)(int) $clinic->isOpenOnWeekends();
        $m = (string)(int) $clinic->isOpenInMorning();
        $e = (string)(int) $clinic->isOpenInEvening();
        $doctorsData = $this->db->query('
          SELECT doctor.*
          FROM doctor_specialty_to_clinic dstc
            JOIN doctor_specialty_to_clinic rdstc USING (doctor_id)
            JOIN doctor ON dstc.doctor_id = doctor.id
            JOIN clinic ON rdstc.clinic_id = clinic.id
          WHERE dstc.clinic_id = ?
            AND clinic.is_active = 1
            AND doctor.is_active = 1
            AND (doctor.is_virtual IS NULL OR doctor.is_virtual = 0)
            AND (doctor.is_has_weekend_time <> ?
              OR doctor.is_has_morning_time <> ?
              OR doctor.is_has_evening_time <> ?)
          GROUP BY doctor.id
          HAVING count(DISTINCT rdstc.clinic_id) = 1
        ', [$clinic->getId(), $w, $m ,$e]);
        $ids = $doctors = [];
        foreach ($doctorsData as $dd) {
            $dd['is_has_weekend_time'] = $w;
            $dd['is_has_morning_time'] = $m;
            $dd['is_has_evening_time'] = $e;
            $doctors[] = $this->initOne($dd);
            $ids[] = $dd['id'];
        }
        if ($ids) {
            $this->db->query('
                UPDATE doctor SET
                  is_has_weekend_time = ?,
                  is_has_morning_time = ?,
                  is_has_evening_time = ?
                WHERE id IN ?
            ', [$w, $m, $e, $ids]);
        }

        return $doctors;
    }

    /**
     * @param ClinicModel $clinic
     * @param int[] $excludedDoctorIds
     * @return DoctorModel[]
     */
    public function getMultiClinicDoctors(ClinicModel $clinic, $excludedDoctorIds = [])
    {
        $excluded = implode(',', $excludedDoctorIds);
        $data = $this->db->query('
          SELECT doctor.*
          FROM doctor_specialty_to_clinic dstc
            JOIN doctor_specialty_to_clinic rdstc USING (doctor_id)
            JOIN doctor ON dstc.doctor_id = doctor.id
            JOIN clinic ON rdstc.clinic_id = clinic.id
          WHERE dstc.clinic_id = ? 
            AND clinic.is_active = 1
            AND doctor.is_active = 1
            AND (doctor.is_virtual IS NULL OR doctor.is_virtual = 0)
        ' . ($excluded ? "AND doctor.id NOT IN ($excluded)" : '') . '
          GROUP BY doctor.id
          HAVING count(DISTINCT rdstc.clinic_id) > 1
        ', [$clinic->getId()]);

        return $this->initList($data);
    }
}
