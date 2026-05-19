<?php

class DoctorSpecialtyToClinicManager extends ModelManager
{
  protected $table_name = 'doctor_specialty_to_clinic';
  protected $model_name = 'DoctorSpecialtyToClinicModel';

  public function beforeSave(DynamicModel $model)
  {
    if (!$model->getId()) {
      SiteTaskManager::setDoctorsPurposesOfVisitBySpecialtyId($model->specialty_id);
    }
  }

  public function afterSave(DynamicModel $model)
  {
    //if($model->doctor->is_virtual != '1')
    //{
    /**
     * @var DoctorSpecialtyToClinicManager $manager
     * @var DoctorSpecialtyToClinicModel $doctor_specialty_to_clinic
     * @var ScheduleManager $schedule_manager
     * @var PurposeOfVisitToDoctorManager $purpose_of_visit_to_doctor_manager
     * @var DoctorManager $doctor_manager
     * @var DoctorModel $doctor
     * @var DoctorToClinicManager $doctor_to_clinic_manager
     */
    /*$manager = ModelManagerFactory::getByName('doctor_specialty_to_clinic');
            $doctor_specialty_to_clinic = $manager->getOneVirtualDoctorToClinic($model->specialty_id, $model->clinic_id);

            if($doctor_specialty_to_clinic)
            {
                $doctor_manager = ModelManagerFactory::getByName('doctor');
                $doctor = $doctor_manager->getOneById($doctor_specialty_to_clinic->doctor_id);
                $doctor_to_clinic_manager = ModelManagerFactory::getByName('doctor_to_clinic');
                $purpose_of_visit_to_doctor_manager = ModelManagerFactory::getByName('purpose_of_visit_to_doctor');
                $schedule_manager = ModelManagerFactory::getByName('schedule');
                $doctor_specialty_to_clinic->delete();
                $doctor_to_clinic_manager->deleteByDoctorIdAndClinicId($doctor->getId(), $model->clinic_id);
                $purpose_of_visit_to_doctor_manager->deleteByDoctorId($doctor->getId());
                $schedule_manager->deleteByDoctorIdAndClinicId($doctor->getId(), $model->clinic_id);
                $doctor->delete();
            }
        }*/
  }

  /**
   * @return DoctorSpecialtyToClinicModel
   */
  public function getOneByDoctorIdAndClinicIdAndSpecialtyId($doctor_id, $clinic_id, $specialty_id)
  {
    $data = $this->orm_model->select()->where('doctor_id = ? AND clinic_id = ? AND specialty_id = ?', $doctor_id, $clinic_id, $specialty_id)->fetchOne();
    return (count($data)) ? $this->initOne($data) : null;
  }

  public function getSpecialtyIdByDoctorIdAndClinicId($doctor_id, $clinic_id)
  {
    $data = $this->orm_model->select()->where('doctor_id = ? AND clinic_id = ?', $doctor_id, $clinic_id)->fetchOne();
    return ($data) ? $data['specialty_id'] : false;
  }


  /**
   * @return DoctorSpecialtyToClinicModel[]
   */
  public function getListByDoctorId($doctor_id)
  {
    $data = $this->orm_model->select()->where('doctor_id = ?', $doctor_id)->fetchAll();
    return $this->initList($data);
  }


  /**
   * @return DoctorSpecialtyToClinicModel[]
   */
  public function getListByDoctorIdAndClinicId($doctor_id, $clinic_id)
  {
    $data = $this->orm_model->select()->where('doctor_id = ? AND clinic_id = ?', $doctor_id, $clinic_id)->fetchAll();
    return $this->initList($data);
  }

  /**
   * @return DoctorSpecialtyToClinicModel
   */
  public function getOneByClinicIdAndSpecialtyId($clinic_id, $specialty_id)
  {
    $data = $this->orm_model->select()->where('clinic_id = ? AND specialty_id = ?', $clinic_id, $specialty_id)->fetchOne();
    return (count($data)) ? $this->initOne($data) : null;
  }


  /**
   * @return DoctorSpecialtyToClinicModel[]
   */
  public function getListByClinicIdAndSpecialtyId($clinic_id, $specialty_id)
  {
    $data = $this->orm_model->select()->where('clinic_id = ? AND specialty_id = ?', $clinic_id, $specialty_id)->fetchAll();
    return (count($data)) ? $this->initList($data) : array();
  }

  /**
   * @return DoctorSpecialtyToClinicModel
   */
  public function getOneByClinicId($clinic_id)
  {
    $data = $this->orm_model->select()->where('clinic_id = ?', $clinic_id)->fetchOne();
    return (count($data)) ? $this->initOne($data) : null;
  }

  /**
   * @return DoctorSpecialtyToClinicModel
   */
  public function getOneByClinicIdAndDoctorId($clinic_id, $doctor_id)
  {
    $data = $this->orm_model->select()->where('clinic_id = ? AND doctor_id = ?', $clinic_id, $doctor_id)->fetchOne();
    return (count($data)) ? $this->initOne($data) : null;
  }

  /**
   * @return DoctorSpecialtyToClinicModel[]
   */
  public function getListByClinicIdAndDoctorId($clinic_id, $doctor_id)
  {
    $data = $this->orm_model->select()->where('clinic_id = ? AND doctor_id = ?', $clinic_id, $doctor_id)->fetchAll();
    return (count($data)) ? $this->initList($data) : array();
  }

  public function getDoctorIdBySpecialtyIdAndClinicId($specialty_id, $clinic_id)
  {
    $data = $this->orm_model->select()->where('specialty_id = ? AND clinic_id = ?', $specialty_id, $clinic_id)->fetchAll();
    return ($data) ? $this->initList($data) : false;
  }

  /**
   * @return DoctorSpecialtyToClinicModel[]
   */
  public function getListByClinicId($clinic_id)
  {
    $data = $this->orm_model->select()->where('clinic_id = ?', $clinic_id)->fetchAll();
    return $this->initList($data);
  }

  /**
   * @return DoctorSpecialtyToClinicModel[]
   */
  public function getListBySpecialtyId($specialty_id)
  {
    $data = $this->orm_model->select()->where('specialty_id = ?', $specialty_id)->fetchAll();
    return $this->initList($data);
  }

  public function deleteByDoctorIdAndClinicId($doctor_id, $clinic_id)
  {
    $sql = 'DELETE
				FROM doctor_specialty_to_clinic
				WHERE doctor_id = ' . (int)$doctor_id . '
					AND clinic_id = ' . (int)$clinic_id;

    $this->db->query($sql);
  }


  public function deleteUnActualDoctorToClinic($doctor_id)
  {
    $sql = 'DELETE
                FROM doctor_specialty_to_clinic
                WHERE doctor_id = ' . (int)$doctor_id . '
                    AND clinic_id NOT IN (
                        SELECT clinic_id
                        FROM doctor_to_clinic
                        WHERE doctor_id = ' . (int)$doctor_id . '
                    )';

    $this->db->query($sql);
  }

  public function getOneVirtualDoctorToClinic($specialty_id, $clinic_id)
  {
    $sql = 'SELECT DISTINCT ds2c.*
                    FROM doctor_specialty_to_clinic ds2c
                    INNER JOIN specialty_to_specialization s2s ON s2s.specialty_id = ds2c.specialty_id
                    INNER JOIN doctor d ON d.id = ds2c.doctor_id
                    WHERE ds2c.clinic_id = ' . (int)$clinic_id . '
                    AND d.is_virtual = 1
                    AND s2s.specialization_id = (SELECT specialization_id
												 FROM specialty_to_specialization
												 WHERE specialty_id = ' . (int)$specialty_id . '
												 AND is_main = 1)';

    $data = $this->db->query($sql);

    return $this->initList($data);
  }

  /**
   * @return DoctorSpecialtyToClinicModel[]
   */
  public function getListByIsToDeleteAndDeleteDate($is_to_delete, $delete_date)
  {
    $sql = 'SELECT *
                    FROM ' . $this->table_name . '
                    WHERE is_to_delete = ' . $is_to_delete . '
                    AND delete_date <= "' . date('Y-m-d H:i:s', $delete_date) . '"';
    $data = $this->db->query($sql);

    return (count($data)) ? $this->initList($data) : array();
  }

    /**
     * Получение похожих врачей округа по наибольшему совпадению специализаций.
     * @param int $doctor_id
     * @param string $equal_ids
     * @param string $regions
     * @return DoctorSpecialtyToClinicModel[]
     */
  public function getListEqualOfDistrictByDoctorId($doctor_id, $equal_ids, $regions)
  {
    $sql = 'SELECT ds2c1.*
        FROM doctor_specialty_to_clinic ds2c
        INNER JOIN doctor_specialty_to_clinic ds2c1 ON ds2c1.specialty_id = ds2c.specialty_id 
            AND ds2c1.doctor_id != ' . (int)$doctor_id . '
        INNER JOIN clinic c ON c.id = ds2c1.clinic_id
        INNER JOIN doctor d ON ds2c1.doctor_id = d.id
        WHERE ds2c.doctor_id = ' . (int)$doctor_id . '
        AND c.region_id IN (' . $regions . ')' . '
        AND (d.is_virtual IS NULL OR d.is_virtual = 0)
        AND d.is_active = 1
        AND d.full_lower_name <> \'\'
        AND c.is_active = 1';

    if ($equal_ids) {
      $sql .= ' AND ds2c1.doctor_id NOT IN (' . $equal_ids . ')';
    }

    $sql .= ' GROUP BY ds2c1.doctor_id
                    ORDER BY COUNT(DISTINCT ds2c1.specialty_id) DESC, RAND()
                    LIMIT 50';

    $data = $this->db->query($sql);

    return $this->initList($data);
  }

  public function getListByDoctorSearchParams(DoctorSearchParams $doctor_search_params)
  {


         $memcache = Register::get('memcache');
                                $cache_key = 'list_by_doctor_'.json_encode($doctor_search_params);
                                $cache = $memcache->get($cache_key);
                                if ($cache !== false) {
                                        return $cache;
                                }


    $sql = 'SELECT ds2c.*
                    FROM doctor_specialty_to_clinic ds2c
                    INNER JOIN clinic c ON ds2c.clinic_id = c.id
                    INNER JOIN doctor doc ON ds2c.doctor_id = doc.id
                    INNER JOIN region r ON c.region_id = r.id
                    INNER JOIN district d ON r.district_id = d.id
                    INNER JOIN street_to_region s2r ON s2r.region_id = r.id
                    WHERE doc.is_active = 1
                    AND c.is_active = 1';

    if (!empty($doctor_search_params->specialty_id)) {
      $sql .= ' AND ds2c.specialty_id = ' . (int)$doctor_search_params->specialty_id;
    }

    if (!empty($doctor_search_params->specialty_ids) and is_array($doctor_search_params->specialty_ids) and count($doctor_search_params->specialty_ids)) {
      $sql .= ' AND ds2c.specialty_id IN (' . implode(', ', array_map(function($a) { return (int)$a; }, $doctor_search_params->specialty_ids)) . ')';
    }

    if (!empty($doctor_search_params->district_id)) {
      $sql .= ' AND d.id = ' . $doctor_search_params->district_id;
    }

    if (!empty($doctor_search_params->region_id)) {
      $sql .= ' AND r.id = ' . $doctor_search_params->region_id;
    }
    if (!empty($doctor_search_params->regions_ids) and is_array($doctor_search_params->regions_ids) and count($doctor_search_params->regions_ids)) {
      $sql .= ' AND r.id IN (' . implode(', ', array_map(function($a) { return (int)$a; }, $doctor_search_params->regions_ids)) . ')';
    }

    if (!empty($doctor_search_params->metro_station_id)) {
      $sql .= ' AND c.metro_station_id = ' . $doctor_search_params->metro_station_id;
    }

    if (!empty($doctor_search_params->street_id)) {
      $sql .= ' AND c.street_id = ' . $doctor_search_params->street_id;
    }
    if (!empty($doctor_search_params->doctor_id)) {
      $sql .= ' AND doc.id = ' . $doctor_search_params->doctor_id;
    }
    if (!empty($doctor_search_params->doctors_ids) and is_array($doctor_search_params->doctors_ids) and count($doctor_search_params->doctors_ids)) {
      $sql .= ' AND doc.id IN (' . implode(', ', array_map(function($a) { return (int)$a; }, $doctor_search_params->doctors_ids)) . ')';
    }
    if (!empty($doctor_search_params->clinics_ids) and is_array($doctor_search_params->clinics_ids) and count($doctor_search_params->clinics_ids)) {
      $sql .= ' AND ds2c.clinic_id IN (' . implode(', ', array_map(function($a) { return (int)$a; }, $doctor_search_params->clinics_ids)) . ')';
    }

    $group_by = 'ds2c.clinic_id';
    if (!empty($doctor_search_params->group_by)) {
      switch($doctor_search_params->group_by) {
        case 'doctor':
          $group_by = 'ds2c.doctor_id';
          break;
        case 'clinic':
          $group_by = 'ds2c.clinic_id';
          break;
        case 'specialty':
          $group_by = 'ds2c.specialty_id';
          break;
        case 'ds2c':
          $group_by = 'ds2c.id';
          break;
      }
    }
    $sql .= ' GROUP BY '.$group_by;

    $data = $this->db->query($sql);

    $result =  $this->initList($data);
       $memcache->set($cache_key, $result, 1080);
                                        return $result;
  }
}
