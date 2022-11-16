<?php

class FbAccountEducationManager extends ModelWAccountidManager
{
  protected $table_name = 'fb_account_education';
  protected $model_name = 'FbAccountEducationModel';
  protected $account_id_field_name = 'fb_account_id';

  public function delByAccountId($account_id)
  {
    $ids = parent::delByAccountId($account_id);
    $fbAccountClassManager = new FbAccountClassManager();
    foreach($ids as $id) {
      $fbAccountClassManager->delByAccountId($id);
    }
    return $ids;
  }
}