<?php

class VisitMailManager extends ModelWAccountidManager
{
  protected $table_name = 'visit_mail';
  protected $model_name = 'VisitMailModel';
  protected $account_id_field_name = 'visit_id';

  public function beforeSave(DynamicModel $visit_mail)
  {
    /**
     * @var VisitMailModel $visit_mail
     */
    if (!$visit_mail->getId()) {
      $visit_mail->dt = date('Y-m-d H:i:s');
    }

    if ($visit_mail->isNew()) {
      $visit_mail->visit_mail_status_id = VisitMailStatusModel::NOT_SEND;
    }
  }

  /**
   * @param int $visit_id
   * @param int $visit_mail_type_id
   *
   * @return VisitMailModel
   */
  public function getOneByVisitIdAndVisitMailTypeId($visit_id, $visit_mail_type_id)
  {
    $data = $this->orm_model->select()->where('visit_id = ? AND visit_mail_type_id = ?', $visit_id, $visit_mail_type_id)->fetchOne();
    return $this->initOne($data);
  }

  /**
   * @param int $visit_mail_status_id
   * @return VisitMailModel[]
   */
  public function getListByVisitMailStatusId($visit_mail_status_id)
  {
    $data = $this->orm_model->select()->where('visit_mail_status_id = ? ', (int)$visit_mail_status_id)->fetchAll();
    return $this->initList($data);
  }
}