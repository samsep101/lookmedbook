<?php

class MyClinicManager extends ModelWAccountidManager
{
  protected $table_name = 'my_clinic';
  protected $model_name = 'MyClinicModel';

  public function checkExistsByClinicIdAndAccountId($clinic_id, $account_id)
  {
    $sql = 'SELECT COUNT(*) as `result`
                FROM ' . $this->table_name . '
                WHERE `clinic_id` = "' . $this->db->escape($clinic_id) . '"
                    AND `account_id` = "' . $this->db->escape($account_id) . '"';

    $data = $this->db->query($sql);

    return (bool)$data[0]['result'];
  }


  /**
   * @return MyClinicModel
   */
  public function getOneByClinicIdAndAccountId($clinic_id, $account_id)
  {
    $sql = 'SELECT ' . $this->selected_fields . '
                    FROM `' . DB_PREFIX . $this->table_name . '`
                    WHERE `clinic_id` = ' . (int)$clinic_id . '
                    AND `account_id` = ' . (int)$account_id;

    $data = $this->db->query($sql);

    return (isset($data[0])) ? $this->initOne($data[0]) : null;
  }


  /**
   * @return MyClinicModel
   */
  public function getOneByClinicId($clinic_id)
  {
    $data = $this->orm_model->select()->where('clinic_id = ?', (int)$clinic_id)->fetchOne();
    return (count($data)) ? $this->initOne($data) : null;
  }

  public function deleteOneByClinicIdAndAccountId($clinic_id, $account_id)
  {
    $sql = 'DELETE
                    FROM `' . DB_PREFIX . $this->table_name . '`
                    WHERE `clinic_id` = ' . (int)$clinic_id . '
                    AND `account_id` = ' . (int)$account_id;

    $data = $this->db->query($sql);
  }



}
