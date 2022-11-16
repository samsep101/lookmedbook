<?php

class AccountManager extends ModelManager
{
  protected $table_name = 'account';
  protected $model_name = 'AccountModel';

  protected function beforeSave(DynamicModel $account)
  {
    /**
     * @var AccountModel $account
     */
    if ($account->password) {
      $account->password_hash = PasswordHashGenerator::generate($account->password);
      unset($account->password);
    }

    if (!$account->getId()) {
      $account->registration_date = date('Y-m-d H:i:s');
    }

    if ($account->wasChangeEmail()) {
      $email = $account->getPreviousEmail();

      $email_api = new EmailApi();
      $email_api->unsubscribe($email);
    }

    if (!$account->dt) {
      $account->dt = date('Y-m-d H:i:s');
    }

    $account->full_name = implode(' ', array_map('trim', [
        $account->last_name,
        $account->first_name,
        $account->middle_name,
    ]));
  }


  /**
   * @return AccountModel
   */
  public function getOneByEmailAndPasswordHash($email, $password_hash)
  {
    $sql = 'SELECT ' . $this->selected_fields . '
                    FROM account
                    WHERE email = "' . $this->db->escape($email) . '"
                        AND password_hash = "' . $this->db->escape($password_hash) . '"';
    $data = $this->db->query($sql);
    return (isset($data[0])) ? $this->initOne($data[0]) : null;
  }

  /**
   * @return AccountModel
   */
  public function getOneByEmail($email)
  {
    $sql = 'SELECT *
                    FROM account
                    WHERE `email` = "' . $this->db->escape($email) . '"';

    $data = $this->db->query($sql);

    return (isset($data[0])) ? $this->initOne($data[0]) : null;
  }

  /**
   * @return AccountModel
   */
  public function getOneByNick($nick)
  {
    $sql = 'SELECT *
                    FROM account
                    WHERE `nick` = "' . $this->db->escape($nick) . '"';

    $data = $this->db->query($sql);

    return (isset($data[0])) ? $this->initOne($data[0]) : null;
  }


  /**
   * @return AccountModel
   */
  public function getOneByEmailAndEmailConfirmCode($email, $email_confirm_code)
  {
    $sql = 'SELECT *
                    FROM account
                    WHERE `email` = "' . $this->db->escape($email) . '"
                        AND `email_confirm_code` = "' . $this->db->escape($email_confirm_code) . '";';

    $data = $this->db->query($sql);

    return (isset($data[0])) ? $this->initOne($data[0]) : null;
  }

  public function confirmEmail($account_id)
  {
    $sql = 'UPDATE account
                    SET is_confirm_email = 1
                    WHERE id = ' . (int)$account_id;

    $this->db->query($sql);
  }

  public function deleteByEmail($email)
  {
    $sql = 'DELETE FROM account
                    WHERE  email="' . $this->db->escape($email) . '"';

    $this->db->query($sql);
  }

  public function deleteByNick($nick)
  {
    $sql = 'DELETE FROM account
                    WHERE  nick="' . $this->db->escape($nick) . '"';

    $this->db->query($sql);
  }

  public function setNewPasswordHashByEmail($password_hash, $email)
  {
    $this->orm_model->update(array('password_hash' => $password_hash), 'email = "' . $this->db->escape($email) . '"');
  }

  public function setEmailAndEmailConfirmCodeAndPasswordHashByAccountId($email, $email_confirm_code, $account_id, $password_hash)
  {
    $this->orm_model->update(array('email' => $email, 'email_confirm_code' => $email_confirm_code, 'password_hash' => $password_hash), 'id = ' . (int)$account_id);
  }

  public static function setFullNameByAccountId($account_id, $full_name)
  {
    $db = Register::get('db');
    $sql = 'UPDATE account
                    SET full_name = "' . $db->escape($full_name) . '"
                    WHERE id = ' . $account_id;

    $db->query($sql);
  }

  /**
   * @return AccountModel
   */
  public function getOneByFullName($full_name)
  {
    $sql = 'SELECT *
                    FROM account
                    WHERE full_name = "' . $this->db->escape($full_name) . '"';

    $data = $this->db->query($sql);

    return (isset($data[0])) ? $this->initOne($data[0]) : null;
  }

  /**
   * @return AccountModel
   */
  public function getOneByFirstNameAndLastNameAndMiddleNameAndEmail($first_name, $last_name, $middle_name, $email)
  {
    $sql = 'SELECT *
                    FROM account
                    WHERE first_name = "' . $this->db->escape($first_name) . '"
						AND last_name = "' . $this->db->escape($last_name) . '"
						AND middle_name = "' . $this->db->escape($middle_name) . '"
						AND email = "' . $this->db->escape($email) . '"';

    $data = $this->db->query($sql);

    return (isset($data[0])) ? $this->initOne($data[0]) : null;
  }

  /**
   * @return AccountModel
   */
  public function getOneByFirstNameAndLastNameAndMiddleName($first_name, $last_name, $middle_name)
  {
    $sql = 'SELECT *
                    FROM account
                    WHERE first_name = "' . $this->db->escape($first_name) . '"
                    AND last_name = "' . $this->db->escape($last_name) . '"
                    AND middle_name = "' . $this->db->escape($middle_name) . '"';

    $data = $this->db->query($sql);

    return (isset($data[0])) ? $this->initOne($data[0]) : null;
  }

  /**
   * @return AccountModel
   */
  public function getOneById($account_id, $w_phone='')
  {
    $sql = 'SELECT `a`.* ';
    if($w_phone){
      $sql .= ', GROUP_CONCAT(DISTINCT `p`.`phone` SEPARATOR \', \') AS phone ';
    }
    $sql .= ' FROM `account` `a` ';
    if($w_phone) $sql .= ' LEFT JOIN `account_phone` `p` ON (`a`.`id`=`p`.`account_id`)';
    $sql .= ' WHERE `a`.`id` = "' . (int)$account_id . '"';
    if($w_phone) $sql .= ' GROUP BY `a`.`id`';
//print_r($sql);
    $data = $this->db->query($sql);

    return (isset($data[0])) ? $this->initOne($data[0]) : null;
  }


  public function getInfoByAccountId($account_id)
  {
    $sql = 'SELECT *
                    FROM account
                    WHERE id = ' . (int)($account_id) . '';

    $data = $this->db->query($sql);

    return (isset($data[0])) ? $data[0] : null;
  }

  /**
   * @param $phone_number
   * @return AccountModel
   */
  public function getOneByPhoneNumber($phone_number)
  {
    $sql = 'SELECT a.*
                    FROM account a
                    INNER JOIN account_phone ap ON ap.account_id = a.id
                        AND ap.is_confirmed = 1
                    WHERE ap.phone = "' . $this->db->escape($phone_number) . '"
                    LIMIT 1';

    $data = $this->db->query($sql);

    return $data ? $this->initOne($data[0]) : null;
  }

  /**
   * @param $session_hash
   * @return AccountModel
   */
  public function getOneByApiSessionHash($session_hash)
  {
    $sql = 'SELECT a.*
					FROM account a
					INNER JOIN account_session asess ON asess.account_id = a.id
					WHERE asess.session_hash = "' . $this->db->escape($session_hash) . '"
					LIMIT 1';

    $data = $this->db->query($sql);

    return isset($data[0]) ? $this->initOne($data[0]) : null;
  }


  /**
   * @param ModelSearchCriteria $criteria
   *
   * @return AccountModel[]
   */
  public function getListByModelSearchCriteria(ModelSearchCriteria $criteria)
  {
    /**
     * @var AccountSearchCriteria $criteria
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

    if (!empty($criteria->first_name)) {
      $search_params->addParam('first_name', $criteria->first_name, '', ['w_mask'=>'both']);
    }

    if (!empty($criteria->middle_name)) {
      $search_params->addParam('middle_name', $criteria->middle_name, '', ['w_mask'=>'both']);
    }

    if (!empty($criteria->last_name)) {
      $search_params->addParam('last_name', $criteria->last_name, '', ['w_mask'=>'both']);
    }

    if (!empty($criteria->email)) {
      $search_params->addParam('email', $criteria->email, '', ['w_mask'=>'both']);
    }

    $search_params->addJoin('account_phone', 'account.id', 'account_phone.account_id', 'LEFT JOIN');
    $search_params->addJoinTableFields('account_phone');

    if(!empty($criteria->phone_number)) {
      $criteria->phone = $criteria->phone_number;
    }
    if (!empty($criteria->phone)) {
      $phone_number = preg_replace('/[^0-9]/ims', '', $criteria->phone);
      if($phone_number>0) {
        $search_params->addParam('account_phone.phone', $phone_number, '', ['w_mask'=>'both']);
      }
    }

    if(!empty($criteria->sort_by)) {
      if($criteria->sort_by=='phone'){
        $criteria->sort_by = 'account_phone.phone';
      }
      $asc = 'ASC';
      if(!empty($criteria->sort_asc) and $criteria->sort_asc==-1){
        $asc = 'DESC';
      }
      $search_params->addSortParam($criteria->sort_by, $asc);
    }else{
      $search_params->addSortParam('id', 'DESC');
    }

    $res = $this->getListBySearchParams($search_params);
    return $res;
  }

}
