<?php

class ModeratePageManager
{
  public function getTheUpdatedPagesByUserIdWithLimit($user_id, $offset = 0, $limit = 6)
  {
    $params = new ModeratePageSearchParams();
    $params->moderate_status_id = ModerateStatusModel::MODERATE;
    $params->limit = $limit;
    $params->offset = $offset;
    $params->registry_user_id = $user_id;


    return $this->getTheUpdatedPagesByModeratePageSearchParams($params);
  }

  public function getTheUpdatedPagesByModeratePageSearchParams(ModeratePageSearchParams $params)
  {
    $db = Register::get('db');

    $clinics_id_list = array();

    if ($params->registry_user_id) {
      $user_manager = new UserManager();
      $user = $user_manager->getOneById($params->registry_user_id);

      if ($user->role_id == RoleModel::ACCOUNT_MANAGER) {
        $clinic_manager = new ClinicManager();
        $clinics = $clinic_manager->getListByManagerUserId($params->registry_user_id);

        if ($clinics) {
          foreach ($clinics as $clinic) {
            $clinics_id_list[] = $clinic->getId();
          }
        } else {
          return array();
        }
      }
    }

    // о клинике
    $sql = '
        SELECT SQL_CALC_FOUND_ROWS * FROM (
          (SELECT ' . ModeratePageTypeModel::CLINIC_ABOUT . ' type_id,
            a.clinic_id as entry_id,
            a.dt,
            a.moderate_status_id
          FROM moderate_clinic_information a';

    if ($params->clinic_name || $clinics_id_list) {
      $sql .= ' INNER JOIN clinic c ON c.id = a.clinic_id ';

      $where = array();
      if ($params->clinic_name) {
        $where[] = 'c.name LIKE "%' . $db->escape($params->clinic_name) . '%"';
      }

      if ($clinics_id_list) {
        $where[] = 'c.id IN (' . join(', ', $clinics_id_list) . ')';
      }

      if ($where) {
        $sql .= ' WHERE ' . join(' AND ', $where) . ' ';
      }
    }

    $sql .= ' LIMIT 5) ';


    // лицензии клиники
    $sql .= '
      UNION
        (SELECT ' . ModeratePageTypeModel::CLINIC_LICENSE . ' type_id,
          a.clinic_id as entry_id,
          a.dt,
          a.moderate_status_id
        FROM moderate_clinic_license a ';

    if ($params->clinic_name || $clinics_id_list) {
      $sql .= ' INNER JOIN clinic c ON c.id = a.clinic_id ';

      $where = array();
      if ($params->clinic_name) {
        $where[] = 'c.name LIKE "%' . $db->escape($params->clinic_name) . '%"';
      }

      if ($clinics_id_list) {
        $where[] = 'c.id IN (' . join(', ', $clinics_id_list) . ')';
      }

      if ($where)
        $sql .= ' WHERE ' . join(' AND ', $where) . ' ';
    }
    //TODO: саня зачем-то убрал и забыл вернуть? спросить!
    $sql .=  ' LIMIT 5) ';

    // реквизиты клиники
    $sql .= '
          UNION

          (SELECT ' . ModeratePageTypeModel::CLINIC_REQUISITES . ' type_id,
            a.clinic_id as entry_id,
            a.dt,
            a.moderate_status_id
          FROM moderate_clinic_requisites a ';

    if ($params->clinic_name || $clinics_id_list) {
      $sql .= ' INNER JOIN clinic c ON c.id = a.clinic_id ';

      $where = array();
      if ($params->clinic_name) {
        $where[] = 'c.name LIKE "%' . $db->escape($params->clinic_name) . '%"';
      }

      if ($clinics_id_list) {
        $where[] = 'c.id IN (' . join(', ', $clinics_id_list) . ')';
      }

      if ($where)
        $sql .= ' WHERE ' . join(' AND ', $where) . ' ';
    }

    //TODO: саня зачем-то убрал и забыл вернуть? спросить!
    $sql .=  ' LIMIT 5) ';

    // описание клиники
    $sql .= '
          UNION

          (SELECT ' . ModeratePageTypeModel::CLINIC_DESCRIPTION . ' type_id,
            a.clinic_id as entry_id,
            a.dt,
            a.moderate_status_id
          FROM moderate_clinic_description a ';

    if ($params->clinic_name || $clinics_id_list) {
      $sql .= ' INNER JOIN clinic c ON c.id = a.clinic_id ';

      $where = array();
      if ($params->clinic_name) {
        $where[] = 'c.name LIKE "%' . $db->escape($params->clinic_name) . '%"';
      }

      if ($clinics_id_list) {
        $where[] = 'c.id IN (' . join(', ', $clinics_id_list) . ')';
      }

      if ($where)
        $sql .= ' WHERE ' . join(' AND ', $where) . ' ';
    }

    //TODO: саня зачем-то убрал и забыл вернуть? спросить!
    $sql .=  ' LIMIT 5) ';

    // фотографии клиники
    $sql .= '
          UNION

          (SELECT ' . ModeratePageTypeModel::CLINIC_PHOTOS . ' type_id,
            a.clinic_id as entry_id,
            a.dt,
            a.moderate_status_id
          FROM moderate_clinic_card_image a ';

    if ($params->clinic_name || $clinics_id_list) {
      $sql .= ' INNER JOIN clinic c ON c.id = a.clinic_id ';

      $where = array();
      if ($params->clinic_name) {
        $where[] = 'c.name LIKE "%' . $db->escape($params->clinic_name) . '%"';
      }

      if ($clinics_id_list) {
        $where[] = 'c.id IN (' . join(', ', $clinics_id_list) . ')';
      }

      if ($where)
        $sql .= ' WHERE ' . join(' AND ', $where) . ' ';
    }

    //TODO: саня зачем-то убрал и забыл вернуть? спросить!
    $sql .=  ' LIMIT 5) ';

    // страница о докторе
    $sql .= '
          UNION

          (SELECT ' . ModeratePageTypeModel::DOCTOR_ABOUT . ' type_id,
            a.doctor_id as entry_id,
            a.dt,
            a.moderate_status_id
          FROM moderate_doctor_information a
          ';

    if ($params->clinic_name || $clinics_id_list) {
      $sql .= ' INNER JOIN doctor d ON d.id = a.doctor_id ';
      $sql .= ' INNER JOIN doctor_to_clinic d2c ON d2c.doctor_id = d.id ';
      $sql .= ' INNER JOIN clinic c ON c.id = d2c.clinic_id ';

      $where = array();
      if ($params->clinic_name) {
        $where[] = 'c.name LIKE "%' . $db->escape($params->clinic_name) . '%"';
      }

      if ($clinics_id_list) {
        $where[] = 'c.id IN (' . join(', ', $clinics_id_list) . ')';
      }

      if ($where)
        $sql .= ' WHERE ' . join(' AND ', $where) . ' ';
    }

    //TODO: саня зачем-то убрал и забыл вернуть? спросить!
    $sql .=  ' LIMIT 5) ';

    // фотографии доктора
    $sql .= '
          UNION

          (SELECT ' . ModeratePageTypeModel::DOCTOR_PHOTOS . ' type_id,
            a.doctor_id as entry_id,
            a.dt,
            a.moderate_status_id
          FROM moderate_doctor_card_image a
          ';

    if ($params->clinic_name || $clinics_id_list) {
      $sql .= ' INNER JOIN doctor d ON d.id = a.doctor_id ';
      $sql .= ' INNER JOIN doctor_to_clinic d2c ON d2c.doctor_id = d.id ';
      $sql .= ' INNER JOIN clinic c ON c.id = d2c.clinic_id ';

      $where = array();
      if ($params->clinic_name) {
        $where[] = 'c.name LIKE "%' . $db->escape($params->clinic_name) . '%"';
      }

      if ($clinics_id_list) {
        $where[] = 'c.id IN (' . join(', ', $clinics_id_list) . ')';
      }

      if ($where)
        $sql .= ' WHERE ' . join(' AND ', $where) . ' ';
    }

    //TODO: саня зачем-то убрал и забыл вернуть? спросить!
    $sql .=  ' LIMIT 5) ';

    // списки для клиники
    $sql .= '
          UNION

          (SELECT CASE
              WHEN (a.list_name = "feature_to_clinic") THEN ' . ModeratePageTypeModel::CLINIC_FEATURES . '
              WHEN (a.list_name = "specialty_to_clinic") THEN ' . ModeratePageTypeModel::CLINIC_SPECIALTIES . '
            END type_id,
            a.entity_id as entry_id,
            a.dt,
            a.moderate_status_id
          FROM moderate_list_revision a ';

    if ($params->clinic_name || $clinics_id_list) {
      $sql .= ' INNER JOIN clinic c ON c.id = a.entity_id ';
    }

    $sql .= ' WHERE a.list_name IN ("feature_to_clinic", "specialty_to_clinic")
          ';

    if ($params->clinic_name || $clinics_id_list) {
      $where = array();
      if ($params->clinic_name) {
        $where[] = 'c.name LIKE "%' . $db->escape($params->clinic_name) . '%"';
      }

      if ($clinics_id_list) {
        $where[] = 'c.id IN (' . join(', ', $clinics_id_list) . ')';
      }

      if ($where)
        $sql .= ' AND ' . join(' AND ', $where) . ' ';
    }

    //TODO: саня зачем-то убрал и забыл вернуть? спросить!
    $sql .=  ' LIMIT 5) ';

    // списки врачей
    $sql .= '
          UNION

          (SELECT ' . ModeratePageTypeModel::DOCTOR_SPECIALTIES . ' type_id,
            a.entity_id as entry_id,
            a.dt,
            a.moderate_status_id
          FROM moderate_list_revision a ';
    if ($params->clinic_name || $clinics_id_list) {
      $sql .= ' INNER JOIN clinic c ON c.id = a.entity_id ';
    }

    $sql .= ' WHERE a.list_name = "specialty_to_doctor"
          ';

    if ($params->clinic_name || $clinics_id_list) {
      $where = array();
      if ($params->clinic_name) {
        $where[] = 'c.name LIKE "%' . $db->escape($params->clinic_name) . '%"';
      }

      if ($clinics_id_list) {
        $where[] = 'c.id IN (' . join(', ', $clinics_id_list) . ')';
      }

      if ($where)
        $sql .= ' AND ' . join(' AND ', $where) . ' ';
    }

    $sql .= ') ) m ';

    $where = array();
    if ($params->moderate_status_id) {
      $where[] .= 'm.moderate_status_id = ' . (int)$params->moderate_status_id . ' ';
    }

    if ($where)
      $sql .= ' WHERE ' . join(', ', $where);

    $sql .= '
        ORDER BY dt DESC
        LIMIT ' . (int)$params->offset . ', ' . (int)$params->limit;

    $data = $db->query($sql);

    return $data;
  }
}