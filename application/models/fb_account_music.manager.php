<?php

class FbAccountMusicManager extends ModelWAccountidManager
{
  protected $table_name = 'fb_account_music';
  protected $model_name = 'FbAccountMusicModel';
  protected $account_id_field_name = 'fb_account_id';

  /**
   * @return FbAccountMusicModel[]
   */
  public function getListByFbAccountId($fb_account_id)
  {
    return $this->getListByAccountId($fb_account_id);

  }

}
