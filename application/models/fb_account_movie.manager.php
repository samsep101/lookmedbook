<?php

class FbAccountMovieManager extends ModelWAccountidManager
{
  protected $table_name = 'fb_account_movie';
  protected $model_name = 'FbAccountMovieModel';
  protected $account_id_field_name = 'fb_account_id';

  /**
   * @return FbAccountMovieModel[]
   */
  public function getListByFbAccountId($fb_account_id)
  {
    return $this->getListByAccountId($fb_account_id);
  }

}
