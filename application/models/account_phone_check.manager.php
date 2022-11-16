<?php

class AccountPhoneCheckManager extends ModelManager
{
  protected $table_name = "account_phone_check";
  protected $model_name = "AccountPhoneCheckModel";


  /**
   * @var int $phone_number
   * @return AccountPhoneCheckModel[]
   */
  public function getListByPhoneNumber($phone_number)
  {
    $data = $this->orm_model->select()->where('phone_number = ?', $phone_number)->fetchAll();
    return $this->initList($data);
  }


  /**
   * @param $id
   * @param $phone_number
   *
   * @return AccountPhoneCheckModel
   */
  public function getOneByIdAndPhoneNumber($id, $phone_number)
  {
    $phone_number = StringHelper::leaveOnlyTheNumber($phone_number);
    $sql = 'SELECT *
					FROM ' . $this->table_name . '
					WHERE id="' . (int)$id . '"
						AND phone_number = "' . $this->db->escape($phone_number) . '"';

    $data = $this->db->query($sql);

    if ($data) {
      return $this->initOne($data[0]);
    } else {
      return null;
    }
  }


}