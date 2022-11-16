<?php

class FbAccountLikeManager extends ModelWAccountidManager
{
  protected $table_name = 'fb_account_like';
  protected $model_name = 'FbAccountLikeModel';
  protected $account_id_field_name = 'fb_account_id';

  /**
   * @return FbAccountLikeModel[]
   */
  public function getListByFbAccountId($fb_account_id)
  {
    return $this->getListByAccountId($fb_account_id);
  }

}
