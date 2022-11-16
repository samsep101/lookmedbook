<?php

class VisitManager extends ModelWAccountidManager
{
  protected $table_name = 'visit';
  protected $model_name = 'VisitModel';

  static function setOverdueStatus()
  {
    $visit_manager = new VisitManager();
    $visit_manager->orm_model->update(['status_id' => VisitModel::FEDDBACK], ' visit_start_time <= now() and status_id = ' . VisitModel::CONFIRMED . ' ');

    return true;
  }

  public function beforeSave(DynamicModel $model)
  {
    /**
     * @var VisitModel $model
     */
    if (!$model->getId() && !$model->status_id) {
      $model->status_id = VisitModel::CHECKING;
    }

    if (!$model->getId() && !$model->schedule_id) {
      $model->schedule_id = VisitModel::RESERVED_TIME_SLOT;
    }

    if ($model->isNew()) {
      $model->create_time = date('Y-m-d H:i:s');
      $model->is_new_visit = 1;
    }

    if (!$model->isNew()) {
      $model->is_new_visit = 0;
    }

    if (!$model->city_id)
    {
        $city = SeoLinksHelper::getCityByPageLink();
        $model->city_id = $city->id;
    }

    if (!$model->operator_account_id){
        $model->operator_account_id = Acc::accountId() ? Acc::accountId() : false;
    }

    $model->phone = preg_replace('/[^0-9]/', '', $model->phone);

      if ($model->status_id == VisitModel::CONFIRMED && !$model->create_mail_sended){
          EmailSenderHelper::sendVisitConfirmMessage($model);
          $model->create_mail_sended = 1;
      }

    if ($model->visit_start_time && $model->isChangeStatus() && ($model->status_id == VisitModel::CONFIRMED)) {
      if ($model->account && $model->account->email) {
        $visit_mail = new VisitMailModel();
        $visit_mail->visit_id = $model->getId();
        $visit_mail->visit_mail_type_id = VisitMailTypeModel::VISIT_CONFIRM_TO_USER;
        $visit_mail->send($model);
      }

      if ($model->phone) {
        if (!$model->doctor_id || $model->doctor->is_virtual == 1) {
          $tokens = array(
            'clinic_address' => $model->clinic->address,
            'clinic_name' => $model->clinic_name,
            'doctor_specialty' => $model->visit_specialty,
            'doctor_specialty_d' => $model->dative_visit_specialty,
            'doctor_specialty_g' => $model->genitive_visit_specialty,
            'doctor_specialty_pl' => $model->plural_visit_specialty,
            'visit_date' => DateViewHelper::date($model->visit_start_time, 'day_and_month_and_week_day'),
            'visit_time' => DateViewHelper::date($model->visit_start_time, 'time')
          );
          $code = 'sms_record_confirm_without_doctor';
        } else {
          $tokens = array(
            'clinic_address' => $model->clinic->address,
            'clinic_name' => $model->clinic_name,
            'doctor_specialty' => $model->visit_specialty,
            'doctor_specialty_d' => $model->dative_visit_specialty,
            'doctor_specialty_g' => $model->genitive_visit_specialty,
            'doctor_specialty_pl' => $model->plural_visit_specialty,
            'doctor_name' => $model->doctor_name,
            'visit_date' => DateViewHelper::date($model->visit_start_time, 'day_and_month_and_week_day'),
            'visit_time' => DateViewHelper::date($model->visit_start_time, 'time')
          );
          $code = 'sms_record_confirm';
        }

        $template_data = MailTemplateDataHelper::getTemplateByCodeAndTokens($code, $tokens);
        SmsSender::sendMessage($model->phone, $template_data->text);
      }

      $push_sender_helper = new MobilePushNotificationSenderHelper();
      $push_sender_helper->sendVisitNotification($model, MobilePushNotificationTypeModel::VISIT_CONFIRMED);
    }

    if ($model->getId() && $model->yandex_id && $model->isChangeApprovedStatus()) {
      $this->updateYandexBookStatus($model->yandex_id, $model->status_id);
    }

    if ($model->visit_start_time) {
      $server_timezone = SettingsManager::get('server_timezone');
      $timezone = $server_timezone;

      if ($model->clinic && $model->clinic->city) {
        if ($model->clinic->city->timezone !== null) {
          $timezone = $model->clinic->city->timezone;
        }
      }

      $model->moscow_visit_start_time = date('Y-m-d H:i:s', strtotime($model->visit_start_time) + 60 * 60 * ($timezone - $server_timezone));

      if ($model->notify_minutes && $model->moscow_visit_start_time) {
        $model->notification_dt = date('Y-m-d H:i:00', strtotime($model->moscow_visit_start_time) - 60 * $model->notify_minutes);
        unset($model->notify_minutes);
      }
    } else {
      $model->moscow_visit_start_time = null;
    }

    if ($model->clinic && $model->clinic->city) {
      $model->city_id = $model->clinic->city_id;
    }
  }

  public function afterSave(VisitModel $model)
  {

    if ($model->status_id == VisitModel::VISITED){
        $client = new GuzzleHttp\Client();

        try {
            $client->post('http://mixmarket.biz/uni/gate.php?cid=1294937485&hash1=f4ebda7792edc5ce0b536db1e0f13a16&pass_=sxfTWjBzuo&e=send', [
                'multipart' => [
                    [
                        'msg' => 'field_name',
                        'contents' => '<?xml version="1.0" encoding="windows-1251"?>
	<uni version="1.0">
		<condition id="1294937485">
			<object><id>' . $model->id . '</id></object>			
		</condition>
	</uni>
'
                    ]
                ]]);
        }catch (Exception $exp){

        }
    }

    if ($model->status_id == VisitModel::CANCELLED){
        $client = new GuzzleHttp\Client();

        try {
            $client->post('http://mixmarket.biz/uni/gate.php?cid=1294937484&hash1=ac4455c2f1a26fe1adc40ed0ede4c6b2&pass_=sxfTWjBzuo&e=send', [
                'multipart' => [
                    [
                        'msg' => 'field_name',
                        'contents' => '<?xml version="1.0" encoding="windows-1251"?>
	<uni version="1.0">
		<condition id="1294937484">
			<object><id>' . $model->id . '</id></object>			
		</condition>
	</uni>
'
                    ]
                ]]);
        }catch (Exception $exp){

        }
    }

    /*
    if ($model->doctor_id && $model->status_id == VisitModel::VISITED)
    {
        $cache = Register::get('cache');
        $cache_id = 'doctor_card_' . $model->doctor_id;
        $cache->remove($cache_id, 'doctor_card_block');
        foreach($model->doctor->specialties as $specialty)
        {
            $cache_id = 'doctor_card_' . $model->doctor_id . '_specialty_' . $specialty->getId();
            $cache->remove($cache_id, 'doctor_card_block');

            foreach($model->doctor->purposes_of_visit as $purpose)
            {
                $cache_id = 'doctor_card_' . $model->doctor_id . '_specialty_' . $specialty->getId() . '_purpose_' . $purpose->getId();
                $cache->remove($cache_id, 'doctor_card_block');
            }
            foreach($model->doctor->clinics as $clinic)
            {
                $cache_id = 'doctor_card_' . $model->doctor_id . '_specialty_' . $specialty->getId() . '_clinic_' . $clinic->getId();

                $cache->remove($cache_id, 'doctor_card_block');
                $cache_id = 'doctor_card_' . $model->doctor_id . '_clinic_' . $clinic->getId();
                $cache->remove($cache_id, 'doctor_card_block');
                foreach($model->doctor->purposes_of_visit as $purpose)
                {
                    $cache_id = 'doctor_card_' . $model->doctor_id . '_specialty_' . $specialty->getId() . '_clinic_' . $clinic->getId() . '_purpose_' . $purpose->getId();
                    $cache->remove($cache_id, 'doctor_card_block');
                }
            }
        }
    }
    */

    if ($model->is_new_visit == 1) {
      // смс для lookmedbook о заявке пользователя
      if (!$model->appeal_id) {
        $tokens = array(
          'visit_id' => $model->getId(),
          'user_name' => $model->full_name,
          'city' => isset($model->account->city_id) ? $model->account->city->name : '-'
        );
        $code = 'sms_record_request';
        $template_data = MailTemplateDataHelper::getTemplateByCodeAndTokens($code, $tokens);
        ServiceNotificationHelper::phoneNotification($template_data->text, 'is_record');
      }

      // почта для lookmedbook о заявке пользователя
      $visit_mail = new VisitMailModel();
      $visit_mail->visit_id = $model->getId();
      $visit_mail->visit_mail_type_id = VisitMailTypeModel::NEW_VISIT_TO_ADMIN;
      $visit_mail->send();

      // смс пользователю при заявке
      if ($model->from_mobile) {
        if (Register::exists('new_password') && $model->account && $model->account->isNew()) {
          $tokens = array(
            'visit_id' => $model->getId(),
            'phone' => $model->phone,
            'password' => Register::get('new_password')
          );
          if (date('H:i') > date('H:i', strtotime('9:00')) && date('H:i') < date('H:i', strtotime('20:30')) && date('N') != 6 && date('N') != 7)
            $code = 'new_user_mobile_sms_record_request_working_time';
          else
            $code = 'new_user_mobile_sms_record_request_holiday_and_night';
        } else {
          $tokens = array(
            'visit_id' => $model->getId()
          );
          if (date('H:i') > date('H:i', strtotime('9:00')) && date('H:i') < date('H:i', strtotime('20:30')) && date('N') != 6 && date('N') != 7)
            $code = 'user_mobile_sms_record_request_working_time';
          else
            $code = 'user_mobile_sms_record_request_holiday_and_night';
        }

        $template_data = MailTemplateDataHelper::getTemplateByCodeAndTokens($code, $tokens);

        SmsSender::sendMessage($model->phone, $template_data->text);
      } else {
        $tokens = array(
          'visit_id' => $model->getId()
        );
        if (date('H:i') > date('H:i', strtotime('9:00')) && date('H:i') < date('H:i', strtotime('20:30')))
          $code = 'user_sms_record_request_day';
        else
          $code = 'user_sms_record_request_night';
        $template_data = MailTemplateDataHelper::getTemplateByCodeAndTokens($code, $tokens);
        SmsSender::sendMessage($model->phone, $template_data->text);
      }

      // почта пользователю при заявке
      $visit_mail_2 = new VisitMailModel();
      $visit_mail_2->visit_id = $model->getId();
      $visit_mail_2->visit_mail_type_id = VisitMailTypeModel::NEW_VISIT_TO_USER;
      $visit_mail_2->send();
    }

    // смс для lookmedbook об отмене визита
    if ($model->status_id == VisitModel::CANCELLED && $model->getWhenceCanceled()) {
      $tokens = array(
        'visit_id' => $model->getId(),
        'whence_canceled' => $model->getWhenceCanceled()
      );

      $code = 'sms_record_cancel';
      $template_data = MailTemplateDataHelper::getTemplateByCodeAndTokens($code, $tokens);
      ServiceNotificationHelper::phoneNotification($template_data->text, 'is_cancel');
    }

    if ($model->account) {
      $account = $model->account;
      if (!$account->full_name) {
        $account->first_name = $model->full_name;
        $account->save();
      }

      if (!$account->email && $model->email) {
        /**
         * @var AccountManager $account_manager
         */
        $account_manager = ModelManagerFactory::getByName('account');
        if (!$account_manager->getOneByEmail($model->email)) {
          $account->email = $model->email;
          $account->save();
        }
      }
    }

    if ($model->appeal_id) {
      $appeal = $model->appeal;
      $appeal->target_call_id = $model->target_call_id;
      $appeal->save();
    }
  }

  public function checkFirstVisitByDoctorIdAndAccountId($doctor_id, $account_id)
  {
    $sql = 'SELECT COUNT(*) as `result`
                    FROM visit
                    INNER JOIN schedule ON visit.schedule_id = schedule.id
                    WHERE visit.account_id = ' . (int)$account_id . '
                    AND schedule.doctor_id = ' . (int)$doctor_id . '
                    AND visit.is_first_visit = 1';

    $data = $this->db->query($sql);

    return !(bool)$data[0]['result'];
  }

  public function getPastListByAccountId($account_id)
  {
    $sql = 'SELECT v.*
                    FROM visit v
                    INNER JOIN schedule s ON s.id = v.schedule_id
                    WHERE v.account_id = ' . (int)$account_id . '
                        AND v.status_id IN (' . VisitModel::VISITED . ', ' . VisitModel::FEDDBACK . ')
                        AND v.visit_start_time < NOW()
                    ORDER BY v.visit_start_time DESC';

    $data = $this->db->query($sql);

    return $this->initList($data);
  }

  public function getPastListByAccountIdWithLimit($account_id, $offset, $limit)
  {
    $sql = 'SELECT v.*
                    FROM visit v
                    INNER JOIN schedule s ON s.visit_id = v.id
                    INNER JOIN doctor_review dr ON s.doctor_id = dr.doctor_id
                    INNER JOIN visit_rating vr ON vr.visit_id = v.id
                    WHERE v.account_id = ' . (int)$account_id . '
                        AND s.dt_end < NOW()
                    ORDER BY s.dt_end DESC
                    LIMIT ' . $offset . ', ' . $limit . ';';

    $data = $this->db->query($sql);

    return $this->initList($data);
  }

  public function getComingListByAccountId($account_id)
  {
    $sql = 'SELECT v.*
                    FROM visit v
                    WHERE v.account_id = ' . (int)$account_id . '
                        AND v.status_id in (' . VisitModel::CHECKING . ',' . VisitModel::CONFIRMED . ')
	                    AND IF (status_id = ' . VisitModel::CONFIRMED . ', visit_start_time, (SELECT dt_end FROM schedule WHERE v.schedule_id = schedule.id)) > NOW()
	                    AND v.status_id = ' . VisitModel::CONFIRMED . '
                    ORDER BY schedule_id ASC';

    $data = $this->db->query($sql);

    return count($data) ? $this->initList($data) : array();
  }

  public function getAllComingListByAccountId($account_id)
  {
    $sql = 'SELECT v.*
                    FROM visit v
                    INNER JOIN `schedule` s ON s.id = v.schedule_id
                    WHERE v.account_id = ' . (int)$account_id . '
                        AND v.status_id in (' . VisitModel::CHECKING . ', ' . VisitModel::CONFIRMED . ', ' . VisitModel::CALL_TO_CLINIC . ')
                        AND (
                            v.visit_start_time > NOW()
                            OR (
                                s.dt_start>NOW() OR s.dt_end>NOW()
                                )
                            )
                    ORDER BY FIELD(status_id,' . VisitModel::CONFIRMED . ',' . VisitModel::FEDDBACK . ',' . VisitModel::CHECKING . ',' . VisitModel::CALL_TO_CLINIC . ') ASC,
                    visit_start_time ASC,
                    s.dt_end ASC';
    $data = $this->db->query($sql);

    return count($data) ? $this->initList($data) : array();
  }

  public function getComingListByAccountIdWithLimit($account_id, $offset, $limit)
  {
    $sql = 'SELECT v.*
                    FROM visit v
                    INNER JOIN schedule s ON s.schedule_id = s.id
                    WHERE v.account_id = ' . (int)$account_id . '
	                    AND s.dt_end > NOW()
                    ORDER BY s.dt_end ASC
                    LIMIT ' . (int)$offset . ', ' . (int)$limit . ';';

    $data = $this->db->query($sql);

    return count($data) ? $this->initList($data) : array();
  }

  /**
   * @return VisitModel
   */
  public function getOneByScheduleId($schedule_id)
  {
    $data = $this->orm_model->select()->where('schedule_id = ?', $schedule_id)->fetchOne();
    return count($data) ? $this->initOne($data) : null;
  }

  /**
   * @return VisitModel[]
   */
  public function getListByNotificationDt($dt)
  {
    $data = $this->orm_model->select()->where('notification_dt = ?', $dt)->fetchAll();
    return count($data) ? $this->initList($data) : array();
  }

  /**
   * @return VisitModel[]
   */
  public function getListByCurrentDate()
  {
    $data = $this->orm_model->select()->where('visit_start_time < "' . date('Y-m-d 00:00:00') . '" AND status_id = ' . VisitModel::CONFIRMED)->fetchAll();
    return count($data) ? $this->initList($data) : array();
  }

  /**
   * @return VisitModel[]
   */
  public function getListByStatusId($status_id)
  {
    $data = $this->orm_model->select()->where('status_id = ' . (int)$status_id)->fetchAll();
    return count($data) ? $this->initList($data) : array();
  }


  /**
   * @return VisitModel
   */
  public function getOneLastUncommentedByAccountId($account_id)
  {
    $sql = 'SELECT v.*
                    FROM visit v
                    WHERE v.account_id = ' . (int)$account_id . '
                        AND v.status_id IN (' . VisitModel::FEDDBACK . ',' . VisitModel::VISITED . ')
                        AND v.visit_start_time < NOW()
                        AND (SELECT COUNT(*) FROM visit_rating vr WHERE vr.visit_id = v.id) = 0
                    ORDER BY v.visit_start_time DESC';

    $data = $this->db->query($sql);

    return ($data) ? $this->initOne($data[0]) : null;
  }

  /**
   * @return VisitModel
   */
  public function getOneLastUncommentedByAccountIdAndDoctorId($account_id, $doctor_id)
  {
    $sql = 'SELECT v.*
                    FROM visit v
                    WHERE v.account_id = ' . (int)$account_id . '
                        AND v.status_id IN (' . VisitModel::FEDDBACK . ',' . VisitModel::VISITED . ')
                        AND v.doctor_id = ' . (int)$doctor_id . '
                        AND v.visit_start_time < NOW()
                        AND (SELECT COUNT(*) FROM visit_rating vr WHERE vr.visit_id = v.id) = 0
                    ORDER BY v.visit_start_time DESC';

    $data = $this->db->query($sql);

    return ($data) ? $this->initOne($data[0]) : null;
  }

  public function recordToTheVisitByVisitInformation(VisitInformation $visit_information)
  {

  }

  public function setOneById($visit_id, $visit)
  {
    $sql = 'UPDATE visit
                    SET schedule_id = ' . (int)$visit->schedule_id . ',
	                    account_id = ' . (int)$visit->account_id . ',
	                    full_name = "' . $this->db->escape($visit->full_name) . '",
	                    phone = "' . $this->db->escape($visit->phone) . '",
	                    purpose_of_visit_id = ' . (int)$visit->purpose_of_visit_id . ',
	                    is_first_visit = "' . $this->db->escape($visit->is_first_visit) . '",
	                    price = "' . $this->db->escape($visit->price) . '"
                    WHERE id = ' . (int)$visit_id;

    $this->db->query($sql);
  }

  public function setStatusIdById($status, $visit_id)
  {
    $sql = 'UPDATE ' . $this->table_name . '
                    SET status_id = "' . (int)$status . '"
                    WHERE id = ' . $visit_id;

    Register::get('db')->query($sql);
  }

  public function getClosedVisitByAccountId($account_id)
  {
    $sql = 'SELECT *
                    FROM visit v
                    WHERE v.account_id = ' . (int)$account_id . '
                    AND v.status_id IN (' . VisitModel::CANCELLED . ', ' . VisitModel::VISITED . ', ' . VisitModel::NOT_VISITED . ');';

    $data = $this->db->query($sql);

    return ($data) ? $this->initList($data) : array();
  }

  public function getOpenVisitByAccountId($account_id)
  {
    $open_visit_statuses = array(
      VisitStatusModel::CALL_TO_CLINIC,
      VisitStatusModel::FEEDBACK,
      VisitStatusModel::CHECKING,
      VisitStatusModel::REJECTED,
      VisitStatusModel::CONFIRMED,
      VisitStatusModel::TO_FILL
    );


    $sql = 'SELECT *
                    FROM visit v
                    WHERE v.account_id = ' . (int)$account_id . '
                    AND v.status_id IN (' . join(', ', $open_visit_statuses) . ');';

    $data = $this->db->query($sql);

    return ($data) ? $this->initList($data) : array();
  }

  /**
   * @return VisitModel
   */
  public function getOneByAccountIdAndDoctorId($account_id, $doctor_id)
  {
    $data = $this->orm_model->select()->where('account_id = ? AND doctor_id = ?', (int)$account_id, (int)$doctor_id)->fetchOne();
    return count($data) ? $this->initOne($data) : null;
  }

  /**
   * @return VisitModel
   */
  public function getOneByAccountIdAndClinicId($account_id, $clinic_id)
  {
    $data = $this->orm_model->select()->where('account_id = ? AND clinic_id = ?', (int)$account_id, (int)$clinic_id)->fetchOne();
    return count($data) ? $this->initOne($data) : null;
  }

  /**
   * @var string $yandex_id
   * @return VisitModel
   */
  public function getOneByYandexId($yandex_id)
  {
    $data = $this->orm_model->select()->where('yandex_id = ?', $yandex_id)->fetchOne();
    return count($data) ? $this->initOne($data) : null;
  }

  public function updateYandexBookStatus($yandex_id, $status_id)
  {
    $status_name = VisitModel::getBookStatusNameForYandexByStatusId($status_id);

    $post_params = array('jsonrpc' => '2.0', 'method' => 'updateBookStatus', 'params' => array($yandex_id, $status_name), 'id' => 99);
    $post_params = json_encode($post_params);

    $url = 'http://lookmedbook:724a1bac0a678e40ba2d014ffc2e7903b54ef073@api.booking-preview.yandex.ru/api';
    $result = @CurlRequestSender::post($url, $post_params);
    //$result = @json_decode($result, TRUE);

    $yandex_log = new YandexLogModel();

    $visit = $this->getOneByYandexId($yandex_id);

    $yandex_log->visit_id = $visit->getId();
    $yandex_log->yandex_id = $yandex_id;
    $yandex_log->method = 'updateBookStatus';
    $yandex_log->direction = 'В Яндекс';
    $yandex_log->request = $post_params;
    $yandex_log->response = $result;
    $yandex_log->dt = date('Y-m-d H:i:s');

    $yandex_log->save();
    //exit();
  }


  public function getListByClinicIdAndVisitStatusId($clinic_id, $visit_status_id)
  {
    $data = $this->orm_model->select()->where('clinic_id = ? AND status_id = ?', (int)$clinic_id, (int)$visit_status_id)->fetchAll();
    return count($data) ? $this->initList($data) : array();
  }

  /**
   * @param $clinic_id
   * @param $visit_status_id
   * @param $date_from
   * @param $date_to
   * @return VisitModel[]
   */
  public function getListByClinicIdAndVisitStatusIdAndDate($clinic_id, $visit_status_id, $date_from, $date_to)
  {
    $sql = 'SELECT *
                    FROM visit
                    WHERE clinic_id = ' . (int)$clinic_id . '
                    AND status_id = ' . (int)$visit_status_id . '
                    AND visit_start_time >= "' . date('Y-m-d 00:00:00', strtotime($date_from)) . '"
                    AND visit_start_time <= "' . date('Y-m-d 23:59:59', strtotime($date_to)) . '"';

    $data = $this->db->query($sql);

    return ($data) ? $this->initList($data) : array();
  }

  /**
   * @param $status_id
   * @param $date
   * @return VisitModel[]
   */
  public function getListForYandexNotificationsByStatusIdAndDate($status_id, $date)
  {
    $sql = 'SELECT *
                    FROM visit v
                    WHERE v.notification_dt = "' . $date . '"
                    AND v.status_id = ' . $status_id . '
                    AND v.yandex_id is not null
                    AND v.visit_start_time is not null';

    $data = $this->db->query($sql);

    return ($data) ? $this->initList($data) : array();
  }

  /**
   * @param ModelSearchCriteria $criteria
   * @return VisitModel[]
   */
  public function getListByModelSearchCriteria(ModelSearchCriteria $criteria)
  {
    /**
     * @var VisitSearchCriteria $criteria
     */
    $search_params = $criteria->getSearchParams();
    if (!$search_params)
      $search_params = new SearchParams();

    if ($criteria->page && $criteria->by_page) {
      $limit = $criteria->by_page;
      $offset = ($criteria->page - 1) * $criteria->by_page;
      $search_params->setOffsetAndLimit($offset, $limit);
    }

    if ($criteria->visit_status_id) {
      $search_params->addParam('status_id', $criteria->visit_status_id);
    }

    if ($criteria->days_count_to_visit_max) {
      $t = time() + ($criteria->days_count_to_visit_max + 1) * 24 * 60 * 60;
      $search_params->addParam('visit_start_time <=', date('Y-m-d', $t));
    }

    if ($criteria->days_count_to_visit_min) {
      $t = time() + $criteria->days_count_to_visit_min * 24 * 60 * 60;
      $search_params->addParam('visit_start_time >=', date('Y-m-d', $t));
    }

    if ($criteria->days_count_after_visit_max !== null) {
      $t = time() - $criteria->days_count_after_visit_max * 24 * 60 * 60;
      $search_params->addParam('visit_start_time >=', date('Y-m-d', $t));
    }

    if ($criteria->days_count_after_visit_min !== null) {
      $t = time() - ($criteria->days_count_after_visit_min - 1) * 24 * 60 * 60;
      $search_params->addParam('visit_start_time <=', date('Y-m-d', $t));
    }

    if ($criteria->minutes_to_visit) {
      $t = time() + $criteria->minutes_to_visit * 60;
      $search_params->addParam('moscow_visit_start_time', date('Y-m-d H:i:00', $t));
    }

    if ($criteria->not_has_doctor_review) {
      $search_params->addJoin('doctor_review', 'doctor_review.visit_id', 'visit.id', 'LEFT OUTER JOIN');
      $search_params->addParam('doctor_review.id', null);
    }

    return $this->getListBySearchParams($search_params);
  }

  public function getOneByAppealId($appeal_id)
  {
    $sql = 'SELECT *
                    FROM visit
                    WHERE appeal_id = ' . (int)$appeal_id;

    $data = $this->db->query($sql);

    return count($data) ? $this->initOne($data[0]) : null;
  }

  public function delByAccountId($account_id)
  {
    $ids = parent::delByAccountId($account_id);

    foreach(['visit_mail', 'visit_rating' ] as $tabName) {
      $manager = ModelManagerFactory::getByName($tabName);
      foreach($ids as $id) {
        $manager->delByAccountId($id);
      }
    }
    return $ids;
  }


}
