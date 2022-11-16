<?php

class FbAccountInteresManager extends ModelWAccountidManager
{
  protected $table_name = 'fb_account_interes';
  protected $model_name = 'FbAccountInteresModel';
  protected $account_id_field_name = 'fb_account_id';

  /**
   * @return FbAccountInteresModel[]
   */
  public function getListByFbAccountId($fb_account_id)
  {
    return $this->getListByAccountId($fb_account_id);

  }

}
