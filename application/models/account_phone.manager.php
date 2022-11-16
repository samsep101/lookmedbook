<?php

class AccountPhoneManager extends ModelWAccountidManager
{
  protected $table_name = 'account_phone';
  protected $model_name = 'AccountPhoneModel';

  protected function beforeSave(DynamicModel $model)
  {
    /**
     * @var AccountPhoneModel $model
     */
    $model->phone = preg_replace('/[^0-9]/', '', $model->phone);

    if ($model->isNew()) {
      $model->dt = date('Y-m-d H:i:s');
    }
  }

  /**
   * @return AccountPhoneModel
   */
  public function getOneByAccountIdAndPhone($account_id, $phone)
  {
    $phone = preg_replace('/[^0-9]/', '', $phone);
    $data = $this->orm_model->select()->where('account_id = ? AND phone = ?', (int)($account_id), $phone)->fetchOne();

    return (count($data)) ? $this->initOne($data) : null;
  }


  public function getConfirmedListByAccountId($account_id)
  {
    $sql = 'SELECT *
                    FROM account_phone
                    WHERE `account_id` = ' . (int)$account_id . '
                        AND `is_confirmed` = 1
                    ORDER BY id';

    $data = $this->db->query($sql);

    return (isset($data)) ? $this->initList($data) : array();
  }

  public function getNotConfirmedListByAccountId($account_id)
  {
    $sql = 'SELECT *
                    FROM account_phone
                    WHERE `account_id` = ' . (int)$account_id . '
                        AND `is_confirmed` = 0';

    $data = $this->db->query($sql);

    return (isset($data)) ? $this->initList($data) : array();
  }

  public function setConfirmByAccountIdAndPhone($account_id, $phone)
  {
    $sql = 'UPDATE account_phone
                    SET is_confirmed = 1
                    WHERE phone = "' . $this->db->escape($phone) . '"
                    AND account_id = ' . (int)$account_id . ';';

    $this->db->query($sql);
  }

  public static function setCodeAndDtById($id, $code, $date)
  {
    $db = Register::get('db');
    $sql = 'UPDATE account_phone
                    SET code = "' . $db->escape($code) . '",
                        dt = "' . $db->escape($date) . '"
                    WHERE id = ' . $id;

    $db->query($sql);
  }

  /**
   * @param $phone_number
   *
   * @return AccountPhoneModel
   */
  public function getOneConfirmedByPhoneNumber($phone_number)
  {
    $phone_number = StringHelper::leaveOnlyTheNumber($phone_number);
    $data = $this->orm_model->select()->where('phone = ? AND is_confirmed = 1', $phone_number)->fetchOne();
    return $this->initOne($data);
  }


  /**
   * @return AccountPhoneModel
   */
  public function getOneByAccountIdAndPhoneAndCode($account_id, $phone, $code)
  {
    $sql = 'SELECT ' . $this->selected_fields . '
                    FROM account_phone
                    WHERE account_id = ' . $account_id . '
                    AND phone = "' . $this->db->escape($phone) . '"
                    AND code = "' . $this->db->escape($code) . '"';
    $db = Register::get('db');
    $data = $db->query($sql);

    return (isset($data[0])) ? $this->initOne($data[0]) : null;
  }

  public static function setIsConfirmedById($id)
  {
    $sql = 'UPDATE account_phone
                    SET is_confirmed = 1
                    WHERE id = ' . $id;

    Register::get('db')->query($sql);
  }

  public function deleteByPhone($phone)
  {
    $phone = preg_replace('/[^0-9]/', '', $phone);
    $sql = 'DELETE FROM account_phone
                    WHERE phone = "' . $this->db->escape($phone) . '"';

    $this->db->query($sql);
  }

  public function deleteByPhones($phones)
  {
    if(!count($phones)) { return; }
    $where = [];
    foreach($phones as $phone) {
      $phone = preg_replace('/[^0-9]/', '', $phone);
      if($phone) {
        $where[] = 'phone = "' . $this->db->escape($phone) . '"';
      }
    }
    if(count($where)) {
      $sql = 'DELETE FROM account_phone WHERE '.implode(' or ', $where);
      $this->db->query($sql);
    }
  }

  /**
   * @return AccountPhoneModel
   */
  public function getOneByPhone($phone)
  {
    $phone = preg_replace('/[^0-9]/', '', $phone);

    $sql = 'SELECT ' . $this->selected_fields . '
                    FROM account_phone
                    WHERE phone = "' . $this->db->escape($phone) . '"';
    $db = Register::get('db');
    $data = $db->query($sql);

    return (isset($data[0])) ? $this->initOne($data[0]) : null;
  }

  public function getConfirmedOneByAccountId($account_id)
  {
    $sql = 'SELECT *
                    FROM ' . $this->table_name . '
                    WHERE account_id = ' . (int)$account_id . '
                    AND is_confirmed = 1';

    $data = $this->db->query($sql);
    return (isset($data[0])) ? $this->initOne($data[0]) : null;
  }




  public function getAllByAccountId($account_id)
  {
    $sql = 'SELECT * FROM ' . $this->table_name . '
                    WHERE account_id = ' . (int)$account_id;

    $data = $this->db->query($sql);
    $res = [];
    if (count($data)) foreach ($data as $data_) {
      $res[$data_['id']] = $this->initOne($data_);
    }
    return $res;
  }


  public function getConfirmedOneByAccountIdAndPhone($account_id, $phone)
  {
    $sql = 'SELECT *
                    FROM ' . $this->table_name . '
                    WHERE account_id = ' . (int)$account_id . '
                    AND phone = "' . $this->db->escape($phone) . '"
                    AND is_confirmed = 1';

    $data = $this->db->query($sql);
    return (isset($data[0])) ? $this->initOne($data[0]) : null;
  }

  public function deleteNotConfirmedByPhone($phone)
  {
    $sql = 'DELETE FROM ' . $this->table_name . '
                    WHERE phone = "' . $this->db->escape($phone) . '"
                        AND is_confirmed = 0';

    $this->db->query($sql);
  }


  public function delByAccountId($account_id)
  {
    $ids = parent::delByAccountId($account_id);

    $accPhoneCheckManager = new AccountPhoneCheckManager();
    $accPhoneCheckManager->deleteByIds($ids);

    return $ids;
  }


}
