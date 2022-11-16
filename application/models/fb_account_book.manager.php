<?php

class FbAccountBookManager extends ModelWAccountidManager
{
  protected $table_name = 'fb_account_book';
  protected $model_name = 'FbAccountBookModel';
  protected $account_id_field_name = 'fb_account_id';

  /**
   * @return FbAccountBookModel[]
   */
  public function getListByFbAccountId($fb_account_id)
  {
    return $this->getListByAccountId($fb_account_id);
  }

}
