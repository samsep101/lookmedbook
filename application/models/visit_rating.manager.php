<?php

class VisitRatingManager extends ModelWAccountidManager
{
  protected $table_name = 'visit_rating';
  protected $model_name = 'VisitRatingModel';
  protected $account_id_field_name = 'visit_id';

  public function afterSave(VisitRatingModel $model)
  {
    /*
    if ($model->visit->doctor_id)
    {
      $cache = Register::get('cache');
      $cache_id = 'doctor_card_' . $model->visit->doctor_id;
      $cache->remove($cache_id, 'doctor_card_block');
      foreach($model->visit->doctor->specialties as $specialty)
      {
        $cache_id = 'doctor_card_' . $model->visit->doctor_id . '_specialty_' . $specialty->getId();
        $cache->remove($cache_id, 'doctor_card_block');

        foreach($model->visit->doctor->purposes_of_visit as $purpose)
        {
          $cache_id = 'doctor_card_' . $model->visit->doctor_id . '_specialty_' . $specialty->getId() . '_purpose_' . $purpose->getId();
          $cache->remove($cache_id, 'doctor_card_block');
        }
        foreach($model->visit->doctor->clinics as $clinic)
        {
          $cache_id = 'doctor_card_' . $model->visit->doctor_id . '_specialty_' . $specialty->getId() . '_clinic_' . $clinic->getId();

          $cache->remove($cache_id, 'doctor_card_block');
          $cache_id = 'doctor_card_' . $model->visit->doctor_id . '_clinic_' . $clinic->getId();
          $cache->remove($cache_id, 'doctor_card_block');
          foreach($model->visit->doctor->purposes_of_visit as $purpose)
          {
            $cache_id = 'doctor_card_' . $model->visit->doctor_id . '_specialty_' . $specialty->getId() . '_clinic_' . $clinic->getId() . '_purpose_' . $purpose->getId();
            $cache->remove($cache_id, 'doctor_card_block');
          }
        }
      }
    }
    */
  }

  /**
   * @return VisitRatingModel
   */
  /**
   * @return VisitRatingModel
   */
  public function getOneByVisitIdAndAccountId($visit_id, $account_id)
  {
    $sql = 'SELECT *
          FROM visit_rating vr
          INNER JOIN visit v ON v.id = vr.visit_id
          WHERE v.account_id = ' . (int)$account_id . '
            AND v.id = ' . (int)$visit_id;

    $data = $this->db->query($sql);

    return (isset($data[0])) ? $this->initOne($data[0]) : null;
  }

  /**
   * @return VisitRatingModel[]
   */
  public function getListByDoctorId($doctor_id)
  {
    $sql = 'SELECT vr.*
          FROM visit_rating vr
          INNER JOIN visit v ON v.id = vr.visit_id
          INNER JOIN schedule s ON s.id = v.schedule_id
          WHERE s.doctor_id = ' . (int)$doctor_id;

    $data = $this->db->query($sql);

    return $this->initList($data);
  }


  /**
   * @return VisitRatingModel[]
   */
  public function getListByClinicId($clinic_id)
  {
    $sql = 'SELECT vr.*
          FROM visit_rating vr
          INNER JOIN visit v ON v.id = vr.visit_id
          INNER JOIN schedule s ON s.id = v.schedule_id
          WHERE s.clinic_id = ' . (int)$clinic_id;

    $data = $this->db->query($sql);

    return $this->initList($data);
  }

  /**
   * @return VisitRatingModel
   */
  public function getOneByAccountId($account_id)
  {
    $sql = 'SELECT vr.*
          FROM visit_rating vr
          INNER JOIN doctor_review dr ON dr.visit_id = vr.visit_id
          WHERE dr.account_id = ' . (int)$account_id;

    $data = $this->db->query($sql);

    return (isset($data[0])) ? $this->initOne($data[0]) : null;

  }

  /**
   * @return VisitRatingModel
   */
  public function getOneByVisitId($visit_id)
  {
    $sql = 'SELECT vr.*
          FROM visit_rating vr
          WHERE vr.visit_id = ' . (int)$visit_id . ' LIMIT 0,1 ';

    $data = $this->db->query($sql);

    if (isset($data[0])) {
      return $this->initOne($data[0]);
    } else {
      $visit_rating = new VisitRatingModel();
      $visit_rating->disableValidation();
      $visit_rating->visit_id = $visit_id;
      $visit_rating->save();

      return $visit_rating;
    }
  }
}
