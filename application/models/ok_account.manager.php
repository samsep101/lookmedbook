<?php

class OkAccountManager extends ModelWAccountidManager
{
  protected $table_name = 'ok_account';
  protected $model_name = 'OkAccountModel';

  /**
   * @return OkAccountModel
   */
  public function getOneByUid($uid)
  {
    $data = $this->orm_model->select()->where('uid = ?', $this->db->escape($uid))->fetchOne();
    return (isset($data)) ? $this->initOne($data) : null;
  }



  public function delByAccountId($account_id)
  {
    $ids = parent::delByAccountId($account_id);

    $manager = new OkAccountFriendManager();
    foreach($ids as $id) {
      $manager->delByAccountId($id);
    }
    return $ids;
  }

}
