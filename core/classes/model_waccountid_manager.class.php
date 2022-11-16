<?php

//Tables with account_id field

class ModelWAccountidManager extends ModelManager
{
  protected $account_id_field_name = 'account_id';

  /**
   * @return FbAccountModel
   */
  public function getOneByAccountId($account_id)
  {
    $data = $this->orm_model->select()->where($this->account_id_field_name.' = ?', (int)$account_id)->fetchOne();
    return (isset($data)) ? $this->initOne($data) : null;
  }


  public function getIdsByAccountId($account_id)
  {
    $idName = $this->id_field_name;
    $data = $this->orm_model->select()
      ->fields('`' . $this->orm_model->getTable() . '`.'.$idName)
      ->where($this->account_id_field_name.' = ?', (int)$account_id)->fetchAll();
    $res = [];
    foreach($data as $item){
      $res[$item[$idName]] = $item[$idName];
    }
    return $res;
  }

  /**
   * @param $account_id
   * @return Model[]
   */
  public function getListByAccountId($account_id)
  {
    $data = $this->orm_model->select()->where($this->account_id_field_name.' = ?', (int)$account_id)->fetchAll();
    return count($data) ? $this->initList($data) : [];
  }



  public function delByAccountId($account_id)
  {
    $ids = $this->getIdsByAccountId($account_id);

    $this->orm_model->delete($this->account_id_field_name.' = '.(int)$account_id);

    return $ids;
  }



}
