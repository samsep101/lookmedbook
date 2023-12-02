<?php

class VkAccountManager extends ModelWAccountidManager
{
  protected $table_name = 'vk_account';
  protected $model_name = 'VkAccountModel';

  /**
   * @return VkAccountModel
   */
  public function getOneByUid($uid)
  {
    $data = $this->orm_model->select()->where('uid = ?', $this->db->escape($uid))->fetchOne();
    return (isset($data)) ? $this->initOne($data) : null;
  }

  public function delByAccountId($account_id)
  {
    $ids = parent::delByAccountId($account_id);

    foreach(['vk_account_friend', 'vk_account_group', 'vk_account_relative', 'vk_account_school', 'vk_account_university' ] as $tabName) {
      $manager = ModelManagerFactory::getByName($tabName);
      foreach($ids as $id) {
        $manager->delByAccountId($id);
      }
    }

    return $ids;
  }
}
