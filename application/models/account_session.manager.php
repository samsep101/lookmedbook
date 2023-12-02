<?php

class AccountSessionManager extends ModelWAccountidManager
{
  protected $table_name = 'account_session';
  protected $model_name = 'AccountSessionModel';

  /**
   * @return AccountSessionModel
   */
  public function getOneBySessionHashAndAccountId($session_hash, $account_id)
  {
    $data = $this->orm_model->select()->where('session_hash = ? AND account_id = ?', $session_hash, (int)$account_id)->fetchOne();
    return ($data) ? $this->initOne($data) : null;
  }


}
