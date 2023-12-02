<?php

class FbAccountManager extends ModelWAccountidManager
{
  protected $table_name = 'fb_account';
  protected $model_name = 'FbAccountModel';

  /**
   * @return FbAccountModel
   */
  public function getOneByUid($uid)
  {
    $data = $this->orm_model->select()->where('uid = ?', $this->db->escape($uid))->fetchOne();
    return (isset($data)) ? $this->initOne($data) : null;
  }


  public function delByAccountId($account_id)
  {
    $ids = parent::delByAccountId($account_id);

    foreach(['fb_account_book', 'fb_account_education', 'fb_account_friend', 'fb_account_group', 'fb_account_interes', 'fb_account_language', 'fb_account_like', 'fb_account_movie', 'fb_account_music', 'fb_account_television', 'fb_account_work', ] as $tabName) {
      $manager = ModelManagerFactory::getByName($tabName);
      foreach($ids as $id) {
        $manager->delByAccountId($id);
      }
    }
    return $ids;
  }

}
