<?php

class FbAccountGroupManager extends ModelWAccountidManager
{
  protected $table_name = 'fb_account_group';
  protected $model_name = 'FbAccountGroupModel';
  protected $account_id_field_name = 'fb_account_id';


  /**
   * @return FbAccountGroupModel[]
   */
  public function getListByFbAccountId($fb_account_id)
  {
    return $this->getListByAccountId($fb_account_id);
  }

}
