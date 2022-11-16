<?php

class FbAccountTelevisionManager extends ModelWAccountidManager
{
  protected $table_name = 'fb_account_television';
  protected $model_name = 'FbAccountTelevisionModel';
  protected $account_id_field_name = 'fb_account_id';


  /**
   * @return FbAccountTelevisionModel[]
   */
  public function getListByFbAccountId($fb_account_id)
  {
    return $this->getListByAccountId($fb_account_id);
  }

}
