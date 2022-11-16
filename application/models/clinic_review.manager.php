<?php

class ClinicReviewManager extends ModelManager
{
  protected $table_name = 'visit_rating';
  protected $model_name = 'ClinicReviewModel';

  protected $selected_fields = 'id,
                                      dt,
                                      clinic_review_text as text,
                                      clinic_review_text,
                                      is_confirmed,
                                      clinic_id,
                                      account_id,
                                      author_name,
                                      visit_id,
                                      is_clinic_advice,
                                      value_for_money,
                                      diagnosis_is_clear,
                                      service_at_the_reception,
                                      waiting_time,
                                      relationship
                                        ';

  protected $selected_field_joins = ' vr.id,
                                            vr.dt,
                                            vr.is_confirmed,
                                            vr.clinic_review_text as text,
                                            vr.clinic_review_text,
                                            vr.clinic_id,
                                            vr.account_id,
                                            vr.author_name,
                                            vr.visit_id,
                                            vr.is_clinic_advice,
                                            vr.value_for_money,
                                            vr.diagnosis_is_clear,
                                            vr.service_at_the_reception,
                                            vr.waiting_time,
                                            vr.relationship
                                            ';

  public function afterSave(DynamicModel $model)
  {
    /**
     * @var ClinicReviewModel $model
     */
    SiteTaskManager::updateClinicRate($model->clinic);
  }

  public function getConfirmedListByClinicId($clinic_id)
  {
    $db = Register::get('db');

    $sql = 'SELECT ' . $this->selected_field_joins . '
                    FROM visit_rating vr
                    WHERE clinic_id = ' . $clinic_id . '
                        AND vr.is_confirmed = 1
                        AND vr.clinic_review_text IS NOT NULL';

    $data = $db->query($sql);

    return (count($data)) ? $this->initList($data) : array();
  }

  public function getConfirmedListByClinicIdWithPagging($clinic_id, $page, $by_page)
  {
    $db = Register::get('db');

    $sql = 'SELECT ' . $this->selected_field_joins . '
                    FROM ' . DB_PREFIX . $this->table_name . ' vr
                    WHERE vr.clinic_id = ' . $clinic_id . '
                        AND vr.is_confirmed = 1
                        AND vr.clinic_review_text IS NOT NULL
                        order by `dt` desc
                    LIMIT ' . $page . ', ' . $by_page . '; ';

    $data = $db->query($sql);

    return (count($data)) ? $this->initList($data) : array();
  }

  /**
   * @return ClinicReviewModel[]
   */
  /**
   * @return ClinicReviewModel[]
   */
  public function getListByAccountIdWithPadding($account_id, $offset, $limit)
  {
    $sql = 'SELECT ' . $this->selected_field_joins . '
                    FROM visit_rating vr
                    INNER JOIN visit v ON vr.visit_id = v.id
                    WHERE vr.account_id = ' . (int)$account_id . '
                        AND v.status_id in (' . VisitModel::FEDDBACK . ',' . VisitModel::VISITED . ')
                        AND v.visit_start_time < NOW()
                        AND vr.clinic_review_text IS NOT NULL
                    ORDER BY v.visit_start_time DESC
                    LIMIT ' . $offset . ', ' . $limit . ';';

    $data = $this->db->query($sql);

    return $this->initList($data);
  }

  public function getConfirmedOneByVisitIdAndAccountId($visit_id, $account_id)
  {
    $sql = 'SELECT ' . $this->selected_field_joins . '
                    FROM ' . $this->table_name . '  vr
                    WHERE vr.visit_id = ' . (int)$visit_id . '
                        AND vr.account_id = ' . (int)$account_id . '
                        AND vr.is_confirmed = 1
                        AND vr.clinic_review_text IS NOT NULL';

    $data = $this->db->query($sql);
    return (isset($data[0])) ? $this->initOne($data[0]) : null;
  }

  /**
   * @return ClinicReviewModel
   */
  public function getOneByVisitIdAndAccountId($visit_id, $account_id)
  {
    $sql = 'SELECT ' . $this->selected_field_joins . '
                    FROM ' . $this->table_name . ' vr
                    WHERE vr.visit_id = ' . (int)$visit_id . '
                        AND vr.account_id = ' . (int)$account_id . '
                        AND vr.clinic_review_text IS NOT NULL';
    $data = $this->db->query($sql);
    return (isset($data[0])) ? $this->initOne($data[0]) : null;
  }

  /**
   * @return ClinicReviewModel[]
   */
  public function getListByAccountId($account_id)
  {
    $sql = 'SELECT ' . $this->selected_field_joins . '
                    FROM ' . $this->table_name . ' vr
                    WHERE account_id = ' . (int)$account_id;

    $data = $this->db->query($sql);

    return (count($data)) ? $this->initList($data) : array();
  }

  /**
   * @param $account_id
   * @param $clinic_id
   *
   * @return ClinicReviewModel
   */
  public function getOneByAccountIdAndClinicId($account_id, $clinic_id)
  {
    $data = $this->orm_model->select()->where('account_id = ? AND clinic_id = ?', $account_id, $clinic_id)->fetchOne();
    return $this->initOne($data);
  }
}
