<?php

class MyDoctorManager extends ModelWAccountidManager
{

  protected $table_name = 'my_doctor';
  protected $model_name = 'MyDoctorModel';

  public function checkExistsByDoctorIdAndAccountId($doctor_id, $account_id)
  {
    if (!$account_id)
      return null;

    $sql = 'SELECT COUNT(*) as `result`
                FROM ' . $this->table_name . '
                WHERE `doctor_id` = "' . $this->db->escape($doctor_id) . '"
                    AND `account_id` = "' . $this->db->escape($account_id) . '"';

    $data = $this->db->query($sql);

    return (bool)$data[0]['result'];
  }

  /**
   * @return MyDoctorModel
   */
  public function getOneByDoctorIdAndAccountId($doctor_id, $account_id)
  {
    if (!$account_id)
      return NULL;

    $sql = 'SELECT ' . $this->selected_fields . '
                    FROM `' . DB_PREFIX . $this->table_name . '`
                    WHERE `doctor_id` = ' . (int)$doctor_id . '
                    AND `account_id` = ' . (int)$account_id;

    $data = $this->db->query($sql);

    return (isset($data[0])) ? $this->initOne($data[0]) : null;
  }

  /**
   * @return MyDoctorModel[]
   */
  public function getListByAccountIdWithPagging($account_id, $page, $by_page)
  {
    $data = $this->orm_model->select()->where('account_id = ?', (int)$account_id)->limit($page, $by_page)->fetchAll();
    return count($data) ? $this->initList($data) : array();
  }

  public function deleteOneByDoctorIdAndAccountId($doctor_id, $account_id)
  {
    $sql = 'DELETE
                    FROM `' . DB_PREFIX . $this->table_name . '`
                    WHERE `doctor_id` = ' . (int)$doctor_id . '
                    AND `account_id` = ' . (int)$account_id;

    $data = $this->db->query($sql);
  }

}
