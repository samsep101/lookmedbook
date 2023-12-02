<?php

class FbAccountClassManager extends ModelWAccountidManager
{
  protected $table_name = 'fb_account_class';
  protected $model_name = 'FbAccountClassModel';
  protected $account_id_field_name = 'fb_account_education_id';

  /**
   * @return FbAccountClassModel[]
   */
  public function getListByFbAccountEducationId($fb_account_education_id)
  {
    $data = $this->orm_model->select()->where('fb_account_education_id = ?', $fb_account_education_id)->fetchAll();
    return $this->initList($data);
  }


}
