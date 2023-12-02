<?php

class UserManager extends ModelManager
{
  protected $table_name = 'user';
  protected $model_name = 'UserModel';

  /**
   * @return UserModel
   */
  /**
   * @return UserModel
   */
  public function getOneByLoginAndPassword($login, $password)
  {
    $user = $this->orm_model->select()->where('login = ? AND password = ?', $login, $password)->fetchOne();
    return $this->initOne($user);
  }

  /**
   * @return UserModel
   */
  public function getOneByLogin($login)
  {
    $user = $this->orm_model->select()->where('login = ?', $login)->fetchOne();
    return $this->initOne($user);
  }

  public function getManagersList()
  {
    $sql = 'SELECT *
          FROM `user`
          WHERE role_id IN (' . join(', ', RoleHelper::getManagerRolesIdList()) . ')
          ORDER BY id';

    $data = $this->db->query($sql);

    return $this->initList($data);
  }

  public function getFreelancersListByManagerUserId($manager_user_id)
  {
    $sql = 'SELECT u.*
          FROM `user` u
          INNER JOIN clinic_to_user c2u ON c2u.user_id = u.id
          WHERE u.role_id = ' . RoleModel::FREELANCE_MANAGER . '
            AND u.clinic_id IN (
                      SELECT clinic_id
                      FROM user_to_clinic
                      WHERE user_id = ' . (int)$manager_user_id . '
                    )
          ORDER BY id';

    $data = $this->db->query($sql);
    return $this->initList($data);
  }

  /**
   * @return UserModel[]
   */
  public function getListByUserSearchParams(UserSearchParams $user_search_params)
  {
    $search_params = new SearchParams();
    $limit = $user_search_params->by_page;
    $offset = ($user_search_params->page - 1) * $user_search_params->by_page;
    $search_params->setOffsetAndLimit($offset, $limit);

    if ($user_search_params->role_id) {
      $search_params->addParam('role_id', $user_search_params->role_id);
    }

    if ($user_search_params->registry_user_id) {
      $user = $this->getOneByid($user_search_params->registry_user_id);

      if ($user->role_id != RoleModel::ACCOUNT_SUPER_MANAGER) {
        $clinic_manager = new ClinicManager();
        $clinics = $clinic_manager->getListByManagerUserId($user_search_params->registry_user_id);

        $clinics_id_list = array();
        if ($clinics) {
          foreach ($clinics as $clinic) {
            $clinics_id_list[] = $clinic->getId();
          }
        } else {
          return array();
        }

        $search_params->addJoin('clinic_to_user', 'clinic_to_user.user_id', 'user.id');
        $search_params->addParam('clinic_to_user.clinic_id IN', $clinics_id_list);

        $search_params->addParam('role_id IN', array(RoleModel::FREELANCE_MANAGER, RoleModel::ACCOUNT_REGISTRY));
      }
    }

    if ($user_search_params->clinic_id) {
      $search_params->addJoin('clinic_to_user', 'clinic_to_user.user_id', 'user.id');
      $search_params->addParam('clinic_to_user.clinic_id', $user_search_params->clinic_id);
    }

    if ($user_search_params->login) {
      $search_params->addParam('login', $user_search_params->login);
    }

    if ($user_search_params->role_id) {
      $search_params->addParam('role_id', $user_search_params->role_id);
    } else {
      $search_params->addParam('role_id IN', array(RoleModel::ACCOUNT_MANAGER, RoleModel::FREELANCE_MANAGER, RoleModel::ACCOUNT_REGISTRY));
    }

    if ($user_search_params->city_id) {
      $search_params->addJoin('clinic_to_user', 'clinic_to_user.user_id', 'user.id');
      $search_params->addJoin('clinic', 'clinic_to_user.clinic_id', 'clinic.id');
      $search_params->addParam('clinic.city_id =', $user_search_params->city_id);
    }

    return $this->getListBySearchParams($search_params);
  }


  public function getFreelancersList()
  {
    $sql = 'SELECT *
          FROM user
          WHERE role_id = ' . RoleModel::FREELANCE_MANAGER . '
          ORDER BY id';

    $data = $this->db->query($sql);

    return $this->initList($data);
  }


  public function getFreelancersListByAccountManagerId($account_manager_id)
  {
    $sql = 'SELECT *
        FROM user u
        WHERE u.role_id = ' . RoleModel::FREELANCE_MANAGER . '
            AND EXISTS (
                SELECT *
                FROM clinic_to_user c2u
                WHERE c2u.user_id = u.id
                LIMIT 1
            )';

    $user = $this->getOneById($account_manager_id);

    if ($user->role_id == RoleModel::ACCOUNT_MANAGER) {
      $sql .= ' AND EXISTS(
                            SELECT c2u.clinic_id
                            FROM clinic_to_user c2u
                            INNER JOIN clinic_to_user c2u2 ON c2u2.clinic_id = c2u.clinic_id
                            WHERE c2u.user_id = u.id
                                AND c2u2.user_id = ' . (int)$account_manager_id . ')';
    }

    $data = $this->db->query($sql);

    return $this->initList($data);
  }

  /**
   * @param $doctor_id
   *
   * @return UserModel[]
   */
  public function getListByDoctorId($doctor_id)
  {
    $sql = 'SELECT u.*
          FROM user u
          INNER JOIN clinic_to_user c2u ON  c2u.user_id = u.id
          INNER JOIN doctor_to_clinic d2c ON c2u.clinic_id = d2c.clinic_id
          WHERE d2c.doctor_id = ' . (int)$doctor_id;

    $data = $this->db->query($sql);

    return $this->initList($data);
  }


  /**
   * @param $role_id
   * @param $clinic_id
   *
   * @return UserModel[]
   */
  public function getListByRoleIdAndClinicId($role_id, $clinic_id)
  {
    $sql = 'SELECT u.*
            FROM user u
            WHERE EXISTS(
                    SELECT *
                    FROM clinic_to_user c2u
                    WHERE c2u.clinic_id = ' . (int)$clinic_id . '
                        AND c2u.user_id = u.id
                ) AND u.role_id = ' . (int)$role_id;

    $data = $this->db->query($sql);

    return $this->initList($data);
  }

  /**
   * @param $clinic_id
   *
   * @return UserModel[]
   */
  public function getListByClinicId($clinic_id)
  {
    $sql = 'SELECT u.*
          FROM user u
          INNER JOIN clinic_to_user c2u ON u.id = c2u.user_id
          WHERE c2u.clinic_id = ' . (int)$clinic_id;

    $data = $this->db->query($sql);

    return $this->initList($data);
  }
}
