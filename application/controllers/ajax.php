<?php

use app\library\classes\MapData\MapDataProviderFactory;

class AjaxController extends BaseController
{
  public function __construct()
  {
    $this->layout = 'ajax';
  }


  public function checkUnique()
  {
	// todo: нормальную проверку делать прийдется
	JsonResponse::result(true);
	return '';


    $value = $this->request('value');
    $fields = $this->request('fields');

    $check_account_id = $this->request('check_account_id', true);

    $fields = explode('.', $fields);

    if (count($fields) != 2) {
      JsonResponse::error(44);
    }

    // todo: сделать номральную проверку на уникальное значение.
    $sql = 'SELECT COUNT(*) as result
                    FROM `' . $fields[0] . '`
                    WHERE `' . $fields[1] . '` = "' . Register::get('db')->escape($value) . '"';

    if ($check_account_id) {
      $sql .= '  AND id != ' . (int)Acc::accountId();
    }

    $data = Register::get('db')->query($sql);

    if ($data[0]['result']) {
      JsonResponse::result(false);
    } else {
      JsonResponse::result(true);
    }
  }

  public function checkUniquePhone()
  {
    $phone = $this->request('phone');

    $sql = 'SELECT COUNT(*) as result
                    FROM `account_phone`
                    WHERE `phone` = "' . Register::get('db')->escape($phone) . '"';

    $data = Register::get('db')->query($sql);

    if ($data[0]['result']) {
      JsonResponse::result(false);
    } else {
      JsonResponse::result(true);
    }

  }

  public function checkEmail()
  {
    $email = $this->request('email');

    if (!$email) {
      JsonResponse::error(55);
    }

    $sql = 'SELECT COUNT(*) as result
                    FROM `account`
                    WHERE `email` = "' . Register::get('db')->escape($email) . '"';

    $data = Register::get('db')->query($sql);

    if ($data[0]['result']) {
      JsonResponse::result(false);
    } else {
      JsonResponse::result(true);
    }
  }

  public function checkUserEmail()
  {
    $email = $_POST['email'];

    if (!$email) {
      JsonResponse::error(55);
    }

    $sql = 'SELECT COUNT(*) as result
                    FROM `user`
                    WHERE `email` = "' . Register::get('db')->escape($email) . '"';

    $data = Register::get('db')->query($sql);

    if ($data[0]['result']) {
      JsonResponse::result(false);
    } else {
      JsonResponse::result(true);
    }
  }

  public function checkLogin()
  {
    $login = $_POST['login'];

    if (!$login) {
      JsonResponse::error(66);
    }

    $sql = 'SELECT COUNT(*) as result
                    FROM `account`
                    WHERE `login` = "' . Register::get('db')->escape($login) . '"';

    $data = Register::get('db')->query($sql);

    if ($data[0]['result']) {
      JsonResponse::result(false);
    } else {
      JsonResponse::result(true);
    }
  }

  public function checkNick()
  {
    $nick = $_POST['nick'];

    if (!$nick) {
      JsonResponse::error(77);
    }

    $sql = 'SELECT COUNT(*) as result
                    FROM `account`
                    WHERE `nick` = "' . Register::get('db')->escape($nick) . '"';

    $data = Register::get('db')->query($sql);

    if ($data[0]['result']) {
      JsonResponse::result(false);
    } else {
      JsonResponse::result(true);
    }
  }

  public function checkPhone()
  {
    $phone = $_POST['phone'];

    if (!$phone) {
      JsonResponse::error(88);
    }

    $sql = 'SELECT COUNT(*) as result
                    FROM `account_phone`
                    WHERE `phone` = "' . Register::get('db')->escape($phone) . '"
                        AND is_confirmed = 1';

    $data = Register::get('db')->query($sql);

    if ($data[0]['result']) {
      JsonResponse::result(false);
    } else {
      JsonResponse::result(true);
    }
  }

  public function checkLoginPhone()
  {
    $phone = PhoneLoginHelper::checkPhone($this->request('phone'));

    if (!$phone) {
      JsonResponse::error(88);
    }

    $sql = 'SELECT COUNT(*) as result
                    FROM `account_phone`
                    WHERE `phone` = "' . Register::get('db')->escape($phone) . '"
                        AND is_confirmed = 1';

    $data = Register::get('db')->query($sql);

    if ($data[0]['result']) {
      JsonResponse::result(false);
    } else {
      JsonResponse::result(true);
    }
  }

  public function getPurposesOfVisitBySpecialtyId()
  {
    $this->layout = 'ajax';

    $specialty_id = $this->request('specialty_id');

    $purpose_manager = new PurposeOfVisitManager();

    $purposes = $purpose_manager->getListBySpecialtyId($specialty_id);

    $this->view->purposes = $purposes;

    $this->view->select_style = 'width: 290px;';
    $html = $this->renderInString('ajax/purposes_select');

    JsonResponse::result($html);
  }

  public function getPurposesOfVisitToDoctorsBySpecialtyId()
  {
    $this->layout = 'ajax';

    $specialty_id = $this->request('specialty_id');

    $purpose_of_visit_to_doctor_manager = new PurposeOfVisitToDoctorManager();
    $purposes = $purpose_of_visit_to_doctor_manager->getListBySpecialtyId($specialty_id);

    $purpose_manager = new PurposeOfVisitManager();

    $purposes = array(
      $purpose_manager->getOneByName('Первичный прием'),
      $purpose_manager->getOneByName('Повторный прием'),
    );
    $this->view->purposes = $purposes;
    $this->view->object_flag = true;
    $html = $this->renderInString('ajax/purposes_select');

    JsonResponse::result($html);
  }

  public function getPurposesOfVisitBySpecialtyIdAndClinicId()
  {
    $this->layout = 'ajax';

    if (!Acc::isAuthed()) {
      JsonResponse::error(3);
    }

    $specialty_id = $this->request('specialty_id');
    $clinic_id = $this->request('clinic_id');

    $purpose_manager = new PurposeOfVisitManager();

    if ($specialty_id) {
      $purposes = $purpose_manager->getListBySpecialtyIdAndClinicId($specialty_id, $clinic_id);
    } else {
      $purposes = array();
      $purposes[] = $purpose_manager->getOneByName('Первичный прием');
      $purposes[] = $purpose_manager->getOneByName('Повторный прием');
    }

    $this->view->purposes = $purposes;

    $html = $this->renderInString('ajax/purposes_select');

    JsonResponse::result($html);
  }

  public function getMapData()
  {
      $datatype = $this->request('datatype');
      if (!$datatype) {
          JsonResponse::error(ValidationErrorCodes::IS_REQUIRED_FIELD, 'datatype required');
      }

      try {
          $dataProvider = MapDataProviderFactory::create($datatype, $this->request, $this->city->id);
      } catch (InvalidArgumentException $e) {
          JsonResponse::error(ValidationErrorCodes::WRONG_DATA, 'Invalid datatype');
      }

      $data = $dataProvider->getData();

      JsonResponse::result($data);
  }

  public function getAboutMapData()
  {
    $file = str_replace('%JUR_ADDRESS_FULL%', JUR_ADDRESS_FULL, file_get_contents(ABS_ROOT . '/media/about/bullet_info.js'));
    JsonResponse::result($file);
  }

  public function getAboutMapAddressData()
  {
    $data = explode(':', str_replace('%JUR_ADDRESS_FULL%', JUR_ADDRESS_FULL, file_get_contents(ABS_ROOT . '/media/about/bullet_info.js')));
    JsonResponse::result($data);
  }

  public function countUnreadedMessage()
  {
    if (!Acc::isAuthed()) {
      JsonResponse::error(3);
    }

    $count = ModelManagerFactory::getByName('message')->getCountUnreadedByAccountId(Acc::accountId());

    JsonResponse::result($count);
  }

  public function getDoctorClinicCard()
  {
    $id = $this->request('id');

    $doctor_id = null;
    $clinic_id = null;

    if (preg_match('/^([0-9]+)\-([0-9]+)$/', $id, $matches)) {
      $doctor_id = $matches[1];
      $clinic_id = $matches[2];
    }

    $doctor = ModelManagerFactory::getByName('doctor')->getOneById($doctor_id);

    $this->view->doctor = $doctor;

    $this->view->clinic_id = $clinic_id;

    //$html = $this->renderInString('doctor/card_small');

    $html = '<div class="doc-popup-sm map-card-block clinic-popup flo citymaps-balloon-content" style="left:-64px; top:23px;z-index:5000"><ul class="citymaps-balloon-buttons citymaps-balloon-buttons2">
                    <li><a class="prev-doctor" style="display: block;"><span class="prev-doctor-way"></span></a></li>
                    <li><a class="next-doctor" style="display: block;"><span class="next-doctor-way"></span></a></li>
                </ul>
                <ul class="nav-doctor-card"></ul>
        <span class="corn-top"></span>';
    $html .= $this->renderInString('doctor/card_small_map');
    $html .= '</div>';


    header('Content-type: application/json');
    echo json_encode(array('result' => $html));
    exit();
  }

  public function sendConfirmedCode()
  {

    $phone_id = $this->request->post('phone_id');
    $account_phone = ModelManagerFactory::getByName('account_phone')->getOneById($phone_id);

    if (!$account_phone) {
      JsonResponse::error(ValidationErrorCodes::ERROR);
    }

    $account_phone->code = PhoneConfirmCodeGeneratorHelper::generate();
    $account_phone->save();
    if ($account_phone && ($account_phone->account_id == Acc::accountId())) {

      if (!SettingsManager::get('send_sms_flag')) {
        $sms_sender = new SmsSender();
        $sms_sender->send('+' . $account_phone->phone, $account_phone->code);
      }
    } else {
      JsonResponse::error(ValidationErrorCodes::ERROR);
    }
  }

  public function getHelps()
  {
    if (!Acc::isAuthed()) {
      JsonResponse::error(3);
    }

    $query = $this->request('query');

    $help_manager = new HelpMaterialManager();
    $materials = $help_manager->getActiveListByTitleOrContent($query, 6, 1);

    $this->layout = 'ajax';
    $this->view->materials = $materials;

    if (!$materials) {
      JsonResponse::error(2);
    }

    $html = $this->renderInString('ajax/materials');

    JsonResponse::result($html);
  }

  public function getClinicMapCard()
  {
    //if (!Acc::isAuthed())
    //  JsonResponse::error(3);

    $clinic_id = $this->request('id');

    $clinic = ModelManagerFactory::getByName('clinic')->getOneById($clinic_id);
#pr($clinic_id, 1);
    $this->view->clinic = $clinic;

    $this->view->clinic_id = $clinic_id;

    $html = '<div class="doc-popup-sm map-card-block clinic-popup flo" style="left:-64px; top:23px;z-index:5000"> <span class="corn-top"></span>';
    $html .= $this->renderInString('clinic/card_map');
    $html .= '</div>';

    header('Content-type: application/json');
    echo json_encode(array('result' => $html));
    exit();
  }

  public function getLaboratoryMapCard()
  {
    //if (!Acc::isAuthed())
    //  JsonResponse::error(3);

    $laboratory_id = $this->request('id');

    $laboratory = ModelManagerFactory::getByName('laboratory')->getOneById($laboratory_id);

    $this->view->laboratory = $laboratory;

    $metro_station_manager = new MetroStationManager();
    $this->view->metro_station = $metro_station_manager->getOneById($laboratory->metro_station_id);

    $this->view->laboratory_id = $laboratory_id;

    $laboratory_service_schedule_manager = new LaboratoryServiceScheduleManager();
    $this->view->results_delivery = $laboratory_service_schedule_manager->getOneByLaboratoryIdAndServiceId($laboratory_id, 6);

    $html = '<div class="doc-popup-sm map-card-block analysis_map_clinic flo" style="left:-64px; top:23px; z-index:5000"> <span class="corn-top"></span>';
    $html .= $this->renderInString('analysis/card_map');
    $html .= '</div>';

    header('Content-type: application/json');
    echo json_encode(array('result' => $html));
    exit();
  }

  public function getAboutMapCard()
  {
    $html = $this->renderInString('index/blocks/about_block');

    header('Content-type: application/json');
    echo json_encode(array('result' => $html));
    exit();
  }


  public function getPopup()
  {
    switch ($this->request('type')) {
      case 'record_to_the_doctor':
        $doctor_id = (int)$this->request('doctor_id');
        /** @var DoctorModel $doctor */
        $doctor = ModelManagerFactory::getByName('doctor')->getOneById($doctor_id);
        $this->view->doctor = $doctor;
        $this->view->family_relations = ModelManagerFactory::getByName('family_relation_status')->getList();

        $schedule_id = (int)$this->request('schedule_id');
        if ($schedule_id) {
          $schedule_day = ModelManagerFactory::getByName('schedule')->getOneById($schedule_id);
          $day = DateViewHelper::date($schedule_day->dt_start, 'with_week_day');

          $selected_day = date_create($schedule_day->dt_start);
          $selected_day = date_format($selected_day, 'Y-m-d');
          $schedule_times = ModelManagerFactory::getByName('schedule')->getListByDoctorIdAndDtStartAndIsBusy($schedule_day->doctor_id, $selected_day);

          $clinic_selected = ModelManagerFactory::getByName('clinic')->getOneById($schedule_day->clinic_id);

          $this->view->day = $day;
          $this->view->clinic_selected = $clinic_selected;
          $this->view->schedule_times = $schedule_times;
        }

        $date_from = date('Y-m-d');
        $date_to = date('Y-m-d', time() + 31 * 86400);

        $schedule_clinics = array();
        $html = $this->renderInString('popup/record_to_the_doctor');
        $formatScheduleDate = function ($dayTs, $time) {
            $t = $time === null ? '00:00' : $time;
            return date('Y-m-d ', $dayTs) . $t . ':00';
        };
        foreach ($doctor->clinics as $clinic) {
          $doctor_clinic_specialties = $doctor->getSpecialtiesByClinicId($clinic->getId());
          $schedule_specialties = array();
          foreach ($doctor_clinic_specialties as $specialty) {
            $unix_datefrom = strtotime($date_from) - 86400;
            $unix_dateto = strtotime($date_to) + 86400;

            $schedule_data = array();//start_time_monday
            for($i = $unix_datefrom; $i<=$unix_dateto; $i+= 24*3600){
              $day = strtolower(date('l', $i));
              $schedule_data[] = array(
                  'schedule_id' => $i.'-'.$clinic->getId().'-'.$specialty->getId(),
                  'dt_start' => $formatScheduleDate($i, $clinic->{'start_time_'.$day}),
                  'dt_end' => $formatScheduleDate($i, $clinic->{'end_time_'.$day}),
              );
            }

            $schedule_specialties[] = array(
              'specialty' => $specialty->getId(),
              'specialty_name' => $specialty->name,
              'schedule' => $schedule_data
            );
          }

          //@TODO Заглушка. Выводим только одно расписание, по требованию заказчика. Не выводим название специальности.
          $copy_schedule_specialties = reset($schedule_specialties);
          $copy_schedule_specialties['specialty_name'] = '';
          $schedule_specialties = array();
          $schedule_specialties[] = $copy_schedule_specialties;

          $schedule_clinics[] = array(
            'clinic' => $clinic->getId(),
            'clinic_name' => $clinic->name,
            'specialties' => $schedule_specialties
          );
        }

        $response = array(
          'html' => $html,
          'schedule' => $schedule_clinics,
        );

        JsonResponse::result($response);
        break;
      case 'record_to_the_doctor_temp':
        $doctor_id = $this->request('doctor_id');
        $doctor = ModelManagerFactory::getByName('doctor')->getOneById($doctor_id);
        $this->view->doctor = $doctor;
        $html = $this->renderInString('popup/record_to_the_doctor_temp');
        JsonResponse::result(array('html' => $html));
        break;
      case 'record_to_the_doctor_simple':
        $doctor_id = $this->request('doctor_id');
        $doctor = ModelManagerFactory::getByName('doctor')->getOneById($doctor_id);
        $this->view->doctor = $doctor;
        $html = $this->renderInString('popup/record_to_the_doctor_simple');
        JsonResponse::result(array('html' => $html));
        break;
      case 'add_review':
        $unique_el_id = $this->request('unique_el_id');
        $this->view->unique_el_id = $unique_el_id;
        $this->view->user = Acc::isAuthed()
            ? ModelManagerFactory::getByName('account')->getOneById(Acc::accountId())
            : null;
        $html = $this->renderInString('review/blocks/add-review-block');

        JsonResponse::result($html);
        break;
      case 'map-city-choice':
        if (preg_match('/analysis/', $_SERVER['HTTP_REFERER'])) {
          $cities = array();
          $city_manager = ModelManagerFactory::getByName('city');
          $all_cities = $city_manager->getHavingLaboratoriesList();
          $city_names = array();

          for ($i = 0; $i < 6; $i++) {
            if ($i > 1) {
              $city_names[] = $all_cities[$i]->name;
            } else {
              $cities[] = $all_cities[$i];
            }
          }

          sort($city_names);
          foreach ($city_names as $name) {
            $cities[] = $city_manager->getOneByName($name);
          }

          $analysis_cities_flag = true;
          $this->view->count_cities = count($all_cities);
          $this->view->cities = $cities;
        } else {
          $this->view->cities = ModelManagerFactory::getByName('city')->getOrderedListByServiceFlag(1);
          $analysis_cities_flag = false;
        }

        $this->view->analysis_cities_flag = $analysis_cities_flag;
        $html = $this->renderInString('map/city_choice');

        JsonResponse::result(array('html' => $html));
        break;
      case 'landing_login':
        $html = $this->renderInString('blocks/landing_login');
        $response = array(
          'html' => $html
        );
        JsonResponse::result($response);
        break;
      case 'landing_registration':
        $html = $this->renderInString('blocks/landing_registration');
        $response = array(
          'html' => $html
        );
        JsonResponse::result($response);
        break;
      case 'landing_license':
        $html = $this->renderInString('blocks/license');
        $response = array(
          'html' => $html
        );
        JsonResponse::result($response);
        break;
      case 'landing_passwrod_recovery':
        $html = $this->renderInString('blocks/landing_forgot_password');
        $response = array(
          'html' => $html
        );
        JsonResponse::result($response);
        break;
      case 'set_new_password':
        $html = $this->renderInString('popup/set_new_password');
        $response = array(
          'html' => $html
        );
        JsonResponse::result($response);
        break;
      case 'social_new_email':
        if (!Acc::isAuthed()) {
          JsonResponse::error(ValidationErrorCodes::NOT_AUTHED);
        }

        $html = $this->renderInString('blocks/social_new_email');
        $response = array(
          'html' => $html
        );
        JsonResponse::result($response);
        break;
      case 'set_new_password_on_landing':
        $html = $this->renderInString('popup/set_new_password_on_landing');
        $response = array(
          'html' => $html
        );
        JsonResponse::result($response);
        break;
      case 'success_record_to_the_doctor':
        $html = $this->renderInString('popup/success_record_to_the_doctor');
        $response = array(
          'html' => $html
        );
        JsonResponse::result($response);
    }

    JsonResponse::error(ValidationErrorCodes::UNKNOWN_POPUP);
  }

  public function getCityChoicePopup()
  {
    $page = $this->request('page', 'main');

    $selected_city_id = $this->request('city_id');

    /**
     * @var CityManager $city_manager
     */
    $city_manager = ModelManagerFactory::getByName('city');

    $selected_city = $city_manager->getOneById($selected_city_id);
    $main_cities = $city_manager->getListBySort();

    $cities = array();

    switch ($page) {
      case 'doctor':
        $cities = $city_manager->getHavingDoctorsListOrderByName();
        break;
      case 'clinic':
        $cities = $city_manager->getHavingClinicsListOrderByName();
        break;
      case 'laboratory':
        $cities = $city_manager->getHavingLaboratoriesListOrderByName();
        //$analysis_cities_flag = true;
        break;
      case 'main':
        $cities = $city_manager->getListWithClinicsOrDoctorsOrLaboratories();
        break;
      default:
        JsonResponse::error(ValidationErrorCodes::WRONG_DATA);
    }

    $this->view->selected_city = $selected_city;
    $this->view->main_cities = $main_cities;
    $this->view->cities = $cities;
    $this->view->page = $page;

    $html = $this->renderInString('map/city_choice');

    JsonResponse::result(array('html' => $html));
  }

  public function selectScheduleId()
  {
    if (!Acc::isAuthed()) {
      JsonResponse::error(ValidationErrorCodes::NOT_AUTHED);
    }

    $schedule_id = (int)$this->request('schedule_id');
    $doctor_id = (int)$this->request('doctor_id');

    $schedule = ModelManagerFactory::getByName('schedule')->getOneById($schedule_id);

    if (!$schedule) {
      JsonResponse::error(ValidationErrorCodes::WRONG_SCHEDULE);
    }

    if ($schedule->doctor_id != $doctor_id) {
      JsonResponse::error(ValidationErrorCodes::WRONG_SCHEDULE_DOCTOR);
    }

    if ($schedule->is_busy != 0) {
      JsonResponse::error(ValidationErrorCodes::SCHEDULE_IS_BUSY);
    }

    $schedule->setReservedStatusByAccountId(Acc::accountId());

    $selected_schedule_id = $this->request('selected_schedule_id');
    $selected_schedule = ModelManagerFactory::getByName('schedule')->getOneById($selected_schedule_id);
    if ($selected_schedule && ($selected_schedule->reserved_account_id == Acc::accountId())) {
      $selected_schedule->setNotBusyStatus();
    }

    JsonResponse::result(true);
  }

  public function recordToTheVisit()
  {


	
      $a = '';
      $schedule_date = '';
      $clinic_id = '';
      $specialty_id = '';

      if (Application::config('captcha.enable')) {
          $gRecaptchaResponse = $this->request->getParam('g-recaptcha-response');
          $recaptcha = new \ReCaptcha\ReCaptcha(GRECAPTCHA_SECRET_KEY);
          $resp = $recaptcha->verify($gRecaptchaResponse, $_SERVER['REMOTE_ADDR']);

          if (!$resp->isSuccess()) {
              JsonResponse::error([]);
          }
      }

    $schedule_id = $this->request->request('schedule_id');
    $doctor_id = $this->request->request('doctor_id');
    $visit_start_time = $this->request->request('visit_start');

      if (preg_match('/([0-9]{2}).([0-9]{2}).([0-9]{4})/is', $visit_start_time, $a)){
          $visit_start_time = $a[3].'-'.$a[2].'-'.$a[1];
      }

    $clinic_id = $this->request->request('clinic_id');
    $after_work = $this->request->request('after_work', 0);
    $disease_id = $this->request->request('disease_id');

    $full_name = trim(strip_tags($this->request->request('full_name')));
    $phone = $this->request->request('phone');
    $family_relation_status_id = $this->request->request('family_relation_status_id');
    $purpose_of_visit_id = $this->request->request('purpose_of_visit_id');
    $visit_id = $this->request->request('visit_id');
    $comment = $this->request->request('comment');

    $schedule_pat = '/([0-9]+)\-([0-9]+)\-([0-9]+)/is';
    if (preg_match($schedule_pat, $schedule_id, $a)){
      list($a, $schedule_date, $clinic_id, $specialty_id) = $a;
    }


    $visit_information = new VisitInformation();
    /*TODO
    remove schedule_id from VisitInformation model
    */
    $visit_information->schedule_id = 13;
    $visit_information->doctor_id = $doctor_id;
    $visit_information->visit_start_time = $visit_start_time;
    $visit_information->disease_id = $disease_id;
    $visit_information->after_work = $after_work;
    $visit_information->full_name = $full_name;
    $visit_information->phone = $phone;
    $visit_information->purpose_of_visit_id = $purpose_of_visit_id;
    $visit_information->account_id = Acc::accountId();
    $visit_information->clinic_id = $clinic_id;
    $visit_information->specialty_id = $specialty_id;
    $visit_information->comment = $comment;
    $visit_information->create_time = date('Y-m-d H:i:s', time());


    $visitStartTimeInt = strtotime($visit_start_time);
    $dinner_hour = date('Y-m-d 12:00:00', $visitStartTimeInt);
    if ($specialty_id > strtotime($dinner_hour)) {
      $visit_information->notification_dt = date('Y-m-d H:i:00', ($visitStartTimeInt - 60 * 60 * SettingsManager::get('notification_visit_evening_h')));
    } else {
      $visit_information->notification_dt = date('Y-m-d H:i:00', ($visitStartTimeInt - 60 * 60 * SettingsManager::get('notification_visit_morning_h')));
    }

    $visit_recorder = new VisitRecorder();
    $status = $visit_recorder->record($visit_information, $visit_id);
    $visit_id = $visit_recorder->getVisit()->getId();

      // Если поле заполнено, значит это робот, уведомление об отклике не отправляем.
      if (empty($this->request->request('email'))) {
          (new EmailSenderHelper())->sendRecordInformation([
              'full_name' => $full_name,
              'phone' => $phone,
              'comment' => $comment,
              'visit_id' => $visit_id,
              'after_work' => $after_work,
              'schedule_date' => $visit_start_time,
              'disease' => $disease_id
                  ? (new DiseaseManager())->getOneById($disease_id)
                  : false,
              'clinic' => $clinic_id
                  ? (new ClinicManager())->getOneById($clinic_id)
                  : false,
              'doctor' => $doctor_id
                  ? (new DoctorManager())->getOneById($doctor_id)
                  : false,
              'account' => $visit_information->account_id
                  ? (new AccountManager())->getOneById($visit_information->account_id)
                  : false,
          ]);

      }

    if ($status == 0) {
      $account_manager = new AccountManager();
      $account = $account_manager->getOneById(Acc::accountId());

      if ($account && $full_name != $account->first_name . ' ' . $account->last_name . ' ' . $account->middle_name) {
        $name_parts = explode(" ", trim($full_name));
        if (isset($name_parts[0])) {
          $first_name = $name_parts[0];
        } else {
          $first_name = '';
        }
        if (isset($name_parts[1])) {
          $last_name = $name_parts[1];
        } else {
          $last_name = '';
        }
        if (isset($name_parts[2])) {
          $middle_name = $name_parts[2];
        } else {
          $middle_name = '';
        }
        $family_account = $account_manager->getOneByFirstNameAndLastNameAndMiddleName($first_name, $last_name, $middle_name);
        if ($family_account) {
          if (!ModelManagerFactory::getByName('family_relation_moderate')->checkExistsByAccountIdAndToAccountIdAndIsConfirmed(Acc::accountId(), $family_account->getId()) && !ModelManagerFactory::getByName('family_relation_moderate')->checkExistsByAccountIdAndToAccountIdAndIsConfirmed($family_account->getId(), Acc::accountId()) && !ModelManagerFactory::getByName('family_relation')->checkExistsByAccount1IdAndAccount2IdAndIsConfirmed(Acc::accountId(), $family_account->getId()) && !ModelManagerFactory::getByName('family_relation')->checkExistsByAccount1IdAndAccount2IdAndIsConfirmed($family_account->getId(), Acc::accountId())
          ) {

            $family_member = new FamilyRelationModerateModel();
            $family_member->account_id = Acc::accountId();
            $family_member->full_name = $full_name;
            $family_member->family_relation_status_id = $family_relation_status_id;
            $family_member->to_account_id = $family_account->getId();
            ModelManagerFactory::getByName('family_relation_moderate')->save($family_member);
          }
        } else {
          if (!ModelManagerFactory::getByName('family_relation_moderate')->checkExistsByAccountIdAndFullNameAndIsConfirmed(Acc::accountId(), $full_name)) {
            $family_member = new FamilyRelationModerateModel();
            $family_member->account_id = Acc::accountId();
            $family_member->full_name = $full_name;
            $family_member->family_relation_status_id = $family_relation_status_id;
            ModelManagerFactory::getByName('family_relation_moderate')->save($family_member);
          }
        }
      }
    }

    if ($status == 0) {
      JsonResponse::result(true, $visit_id);
    } else {
      JsonResponse::error($status);
    }
  }

  public function closeNote()
  {
    if (!Acc::isAuthed()) {
      JsonResponse::error(ValidationErrorCodes::NOT_AUTHED);
    }

    $note_type = $this->request('note_type');

    switch ($note_type) {

      case 'about_note':
        $_SESSION['close_about_note_attribute'] = 1;
        JsonResponse::result(true);
        break;

      case 'settings_note':
        $_SESSION['close_settings_note_attribute'] = 1;
        JsonResponse::result(true);
        break;

      case 'review_note':
        $_SESSION['close_review_note_attribute'] = 1;
        JsonResponse::result(true);
        break;

      case 'doctors_visits_past_note':
        $_SESSION['close_visits_note_attribute'] = 1;
        JsonResponse::result(true);
        break;
    };

    JsonResponse::error(ValidationErrorCodes::ERROR);
  }

  public function setScheduleId()
  {
    if (!Acc::isAuthed()) {
      JsonResponse::error(ValidationErrorCodes::NOT_AUTHED);
    }

    $visit_id = (int)$this->request('visit_id');

    $visit = ModelManagerFactory::getByName('visit')->getOneById($visit_id);

    //$relation = ModelManagerFactory::getByName('family_relation_moderate')->getOneById($visit_id);

    $time = mb_substr(htmlspecialchars($visit->schedule->dt_start), 11, 5, 'UTF-8');
    $time = str_replace(':', '-', $time);

    if ($visit) {
      $response = array(
        'purpose_of_visit' => $visit->purpose_of_visit->name,
        'purpose_of_visit_id' => $visit->purpose_of_visit_id,
        'schedule_id' => $visit->schedule_id,
        'full_name' => $visit->full_name,
        'phone' => $visit->phone,
        'day' => mb_substr(htmlspecialchars($visit->schedule->dt_start), 0, 10, 'UTF-8'),
        'time' => $time,
        'schedule_id' => $visit->schedule_id,
        'dt_start' => mb_substr(htmlspecialchars($visit->schedule->dt_start), 11, 5, 'UTF-8')
      );

      JsonResponse::result($response);
    } else {
      JsonResponse::result(false);
    }
  }

  public function getVisitTime()
  {
    if (!Acc::isAuthed()) {
      JsonResponse::error(ValidationErrorCodes::NOT_AUTHED);
    }

    $visit_id = (int)$this->request('visit_id');

    $visit = ModelManagerFactory::getByName('visit')->getOneById($visit_id);
    if ($visit) {
      $time = mb_substr(htmlspecialchars($visit->schedule->dt_start), 11, 5, 'UTF-8');
      if ($time) {
        JsonResponse::result(array('time' => $time));
      } else {
        JsonResponse::result(false);
      }
    } else {
      JsonResponse::result(false);
    }
  }

  public function changeExistPhone()
  {
    if (!Acc::isAuthed()) {
      JsonResponse::error(ValidationErrorCodes::NOT_AUTHED);
    }

    $old_phone_id = $this->request->post('old_phone_id');
    $new_phone = $this->request->post('new_phone');

    $account_phone_manager = new AccountPhoneManager();
    $account_phone = $account_phone_manager->getOneById($old_phone_id);
    if ($account_phone && $new_phone != '') {
      $account_phone->phone = $new_phone;
      $account_phone_manager->save($account_phone);
      JsonResponse::result(true);
    } else if ($account_phone && $new_phone == '') {
      $account_phone_manager->deleteById($account_phone->id);
      JsonResponse::result(array('deleted' => true));
    }

    JsonResponse::error(ValidationErrorCodes::INVALID_PHONE);
  }

  public function getCities()
  {
    $query = $this->request('query');
    $about_page = $this->request('aboute_page', 0);
    $page = $this->request('page');

    $city_manager = new CityManager();

    if ($about_page) {
      $cities = $city_manager->getListByCity($query, 6, 1);
    } else {
      $city_criteria = new CitySearchCriteria();

      switch ($page) {
        case 'doctor':
          $city_criteria->has_doctors = 1;
          break;
        case 'clinic':
          $city_criteria->has_clinics = 1;
          break;
        case 'laboratory':
          $city_criteria->has_laboratories = 1;
          break;
        case 'main':
          $city_criteria->has_one = 1;
          break;
      }
      $city_criteria->name = $query;
      $city_criteria->page = 1;
      $city_criteria->by_page = 6;

      $cities = $city_manager->getListByModelSearchCriteria($city_criteria);
      $this->layout = 'ajax';
    }

    $this->view->page = $page;
    $this->view->cities = $cities;

    if (!$cities) {
      JsonResponse::error(2);
    }

    $html = $this->renderInString('ajax/cities');

    JsonResponse::result($html);
  }

  public function getCityCoordinates()
  {
    // if (!Acc::isAuthed())
    //   JsonResponse::error(3);

    $latitude = '55.7488';
    $longitude = '37.598';

    $account_manager = new AccountManager();
    $account = $account_manager->getOneById(Acc::accountId());

    if ($account && $account->city_id) {

      $latitude = $account->city->lat;
      $longitude = $account->city->lng;

    }

    JsonResponse::result(array('latitude' => $latitude, 'longitude' => $longitude));
  }

  public function changeCitySearch()
  {
    $city_id = $this->request->post('city_id');
    $save_city_flag = $this->request->post('save_city_flag');

    if ($save_city_flag == 1) {
      if (Acc::isAuthed()) {
        $account_manager = new AccountManager();
        $account = $account_manager->getOneById(Acc::accountId());
        $account->city_id = $city_id;
        $account_manager->save($account);
      } else {
        $_SESSION['city_id'] = $city_id;
      }
    }

    /**
     * @var CityModel $city
     */
    $city = ModelManagerFactory::getByName('city')->getOneById($city_id);

    if ($city) {
      $latitude = $city->lat;
      $longitude = $city->lng;
      $city_name = $city->name;
    } else {
      $latitude = '55.7488';
      $longitude = '37.598';
      $city_id = ModelManagerFactory::getByName('city')->getIdByName('Москва');
      $city_name = 'Москва';
    }

    JsonResponse::result(array(
      'city' => $city_name,
      'city_id' => $city_id,
      'latitude' => $latitude,
      'longitude' => $longitude,
      'city_alias' => $city ? $city->alias : 'moskva'
    ));
  }

  public function getCityId()
  {
    // todo: закомментироваи в связи с откытием страниц для пользователя
    /*
if (!Acc::isAuthed())
      JsonResponse::error(3);
*/

    $city_name = $this->request('city_name');
    $city_id = null;

    if ($city_name) {
      $tags = explode(",", $city_name);

      $city_manager = new CityManager();
      $city_id = $city_manager->getIdByNameAndRegion(trim($tags[0]), trim($tags[1]));
    }

    JsonResponse::result(array('city_id' => $city_id));
  }

  public function getCityIdByCityName()
  {
    if (!Acc::isAuthed()) {
      JsonResponse::error(3);
    }

    $city_name = $this->request('city_name');
    $city_id = null;

    if ($city_name) {

      $city_manager = new CityManager();
      $city_id = $city_manager->getIdByCityName(trim($city_name));
    }

    JsonResponse::result(array('city_id' => $city_id));
  }

  public function logAccountActivity()
  {
    $code = $this->request->post('code');
    $log = $this->request->post('log');
    $no_doctor = null;

    switch ($code) {
      case 'search_metro_or_address':
        $code = Log::SEARCH_METRO_OR_ADDRESS;
        break;
      case 'search_city':
        $code = Log::SEARCH_CITY;

        $city_manager = new CityManager();

        $pos = strripos($log, ',');
        if ($pos) {
          $mas = explode(',', $log);
          $city_name = $mas[0];
        } else {
          $city_name = $log;
        }

        if ($city = $city_manager->getOneByName($city_name)) {
          if (!$city->service_flag) {
            $no_doctor = 1;
          } else {
            $no_doctor = 0;
          }
        } else {
          $no_doctor = 1;
        };

        break;
      case 'account_city':
        $code = Log::FIND_GEOIP_CITY;
        break;
    };

    $log_class = new Log();
    if ($log_class->writeActivityByAccountId($code, $log, Acc::accountId(), $no_doctor)) {
      JsonResponse::result(true);
    } else {
      JsonResponse::error(ValidationErrorCodes::ACCOUNT_ACTIVITY_NOT_WRITE);
    }
  }

  public function getAccountInfo()
  {
    if (!Acc::isAuthed()) {
      JsonResponse::error(3);
    }

    $account_id = $this->request('account_id');

    $account_manager = new AccountManager();
    if ($account = $account_manager->getInfoByAccountId($account_id)) {
      if (isset($account['password_hash'])) {
        unset($account['password_hash']);
      }
      if (isset($account['is_confirm_email'])) {
        unset($account['is_confirm_email']);
      }
      if (isset($account['is_confirmed'])) {
        unset($account['is_confirmed']);
      }
      if (isset($account['email_confirm_code'])) {
        unset($account['email_confirm_code']);
      }
      if (isset($account['image_id'])) {
        unset($account['image_id']);
      }
      JsonResponse::result($account);
    } else {
      JsonResponse::error(ValidationErrorCodes::WRONG_ACCOUNT);
    }
  }

  public function skipReservedTime()
  {
    if (!Acc::isAuthed()) {
      $this->redirectUrl('/');
    }

    $schedule_id = $_POST['schedule_id'];

    ModelManagerFactory::getByName('schedule')->setUnreservedById($schedule_id);
    JsonResponse::result(true);
  }

  public function getMonthVisitDays()
  {
    if (!Acc::isAuthed()) {
      JsonResponse::error(ValidationErrorCodes::NOT_AUTHED);
    }

    $visits = ModelManagerFactory::getByName('visit')->getComingListByAccountId(Acc::accountId());

    $visit_records = array();

    if ($visits) {
      foreach ($visits as $visit) {
        if ($visit->visit_start_time) {
          $date = date_create($visit->visit_start_time);
        } else {
          $date = date_create($visit->schedule->dt_start);
        }
        $year = date_format($date, 'Y');
        $month = date_format($date, 'm');
        $day = date_format($date, 'd');
        $visit_records[] = array(
          'year' => (int)$year,
          'month' => (int)$month - 1,
          'day' => (int)$day
        );
      }
    }

    if ($visit_records) {
      $response = array(
        'visit_records' => $visit_records,
      );

      JsonResponse::result($response);
    } else {
      JsonResponse::result(false);
    }
  }

  public function removeDoctorFromFavorite()
  {

    $doctor_id = $this->request->post('doctor_id');
    if ($doctor_id) {
      $my_doctor_manager = new MyDoctorManager();
      $my_doctor_manager->deleteOneByDoctorIdAndAccountId($doctor_id, Acc::accountId());
      JsonResponse::result(true);
    }

  }

  public function removeClinicFromFavorite()
  {

    $clinic_id = $this->request->post('clinic_id');
    if ($clinic_id) {
      $my_clinic_manager = new MyClinicManager();
      $my_clinic_manager->deleteOneByClinicIdAndAccountId($clinic_id, Acc::accountId());
      JsonResponse::result(true);
    }

  }

  public function readAboutDisease()
  {
    if (!Acc::isAuthed()) {
      JsonResponse::error(ValidationErrorCodes::NOT_AUTHED);
    }

    $disease_id = $this->request('read_disease_id');

    $ready_disease = ModelManagerFactory::getByName('disease')->getActiveOneById($disease_id);

    if ($ready_disease) {
      $response = array(
        'ready_disease' => $ready_disease->id,
      );
    } else {
      $response = array(
        'ready_disease' => false,
      );
    }
    JsonResponse::result($response);
  }

  public function restoreReservedTime()
  {
    if (!Acc::isAuthed()) {
      $this->redirectUrl('/');
    }

    $schedule_id = $_POST['schedule_id'];
    $selected_schedule_id = $_POST['selected_schedule_id'];

    ModelManagerFactory::getByName('schedule')->setUnreservedById($selected_schedule_id);
    ModelManagerFactory::getByName('schedule')->setReservedById($schedule_id);
    JsonResponse::result(true);
  }

  public function changeSpecialtiesListToSearchClinicsByCityId()
  {
    //if (!Acc::isAuthed())
    //$this->redirectUrl('/');

    $city_id = $this->request('city_id', 0);

    $specialty_manager = new SpecialtyManager();
    $specialties = $specialty_manager->getRootListToSearchClinicByCityId($city_id);

    $this->view->specialties = $specialties;

    $options = $this->renderInString('blocks/specialties_options');
    JsonResponse::result(array('option' => $options));
  }

  public function changeSpecialtiesListToSearchDoctorsByCityId()
  {
    // if (!Acc::isAuthed()) $this->redirectUrl('/');

    $city_id = $this->request('city_id', 0);

    $city_manager = new CityManager();
    $city = $city_manager->getOneById($city_id);


    $specialty_manager = new SpecialtyManager();
    $specialties = $specialty_manager->getRootListToSearchDoctorsByCityId($city_id);

    $this->view->specialties = $specialties;
    $this->view->show_all_option = false;
    $options = $this->renderInString('blocks/specialties_options');
    JsonResponse::result(array('option' => $options));
  }

  public function getDoctorsPick()
  {
    if (!Acl::isAuthed(RoleModel::ACCOUNT_ADMIN)) {
      JsonResponse::error(ValidationErrorCodes::NOT_AUTHED);
    }

    /**
     * @var DoctorManager $doctor_manager
     * @var DoctorModel $doctor
     */

    $query = $this->request('query');

    $doctor_search_params = new AdminDoctorSearchParams();
    $doctor_search_params->doctor_name = $query;
    $doctor_search_params->by_page = 50;
    $doctor_search_params->page = 1;

    $doctor_manager = new DoctorManager();
    $doctors = $doctor_manager->getListByDoctorSearchParams($doctor_search_params);

    $this->layout = 'ajax';
    $html = '';
    if ($doctors) {
      foreach ($doctors as $doctor) {
        $html .= '<li data-id="' . $doctor->getId() . '">' . $doctor->full_name . '</li>';
      }
    }

    JsonResponse::result($html);
  }

  public function getSimilarDiseases()
  {
    $disease_id = $this->request('disease_id');
    $disease_title = $this->request('disease_title');
    $specialty_id = $this->request('specialty_id');

    if ($disease_id && $specialty_id) {
      $diseaseManager = ModelManagerFactory::getByName('disease');
      $disease = $diseaseManager->getOneByIdOrAlias($disease_id);

      $disease_block_manager = new DiseaseBlockManager();
      $diseaseForSpecialization = $disease_block_manager->getDiseaseForSpecializationDefinedBySpecialty(intval($specialty_id));
      if (count($diseaseForSpecialization)) {
        $this->view->diseases_specialization = $disease_block_manager->constructSimilarDiseases($disease, $diseaseForSpecialization);
      }
    }

    $html = $this->renderInString('doctor/card_big_list');

    $result = array(
      'html' => $html
    );

    JsonResponse::result($result);

  }

  public function getDoctorsByClinicId()
  {
    $clinic_id = $this->request('clinic_id', 0);

    $doctor_manager = new DoctorManager();

    $doctors = $doctor_manager->getActiveListByClinicId($clinic_id);

    $result = array();


    if ($doctors) {
      foreach ($doctors as $doctor) {
        if (!$doctor->is_virtual) {
          $result[] = array(
            'id' => $doctor->getId(),
            'name' => $doctor->full_name
          );
        }
      }
    }

    JsonResponse::result($result);
  }

  public function getSpecialtiesByDoctorId()
  {
    $doctor_id = $this->request('doctor_id', 0);
    $clinic_id = $this->request('clinic_id', 0);
    $visit_id = $this->request('visit_id', 0);

    $specialty_manager = new SpecialtyManager();

    if ($clinic_id && !$doctor_id) {
      $clinic_manager = new ClinicManager();
      $clinic = $clinic_manager->getOneById($clinic_id);
      $specialties = $clinic->specialties;
    } else {
      $specialties = $specialty_manager->getActiveListByDoctorId($doctor_id);
    }

    $result = array();
    $visit_specialty = null;

    if ($specialties) {
      foreach ($specialties as $specialty) {
        $result[] = array(
          'id' => $specialty->getId(),
          'name' => $specialty->name
        );
      }
      if ($visit_id) {
        $visit_manager = new VisitManager();
        $visit = $visit_manager->getOneById($visit_id);
        $visit_specialty = $visit->specialty_id;
      }
    }

    JsonResponse::result(array('specialties' => $result, 'visit_specialty' => $visit_specialty));
  }

  public function getClinicPhonesByClinicId()
  {
    $clinic_id = $this->request('clinic_id', 0);

    $clinic = ModelManagerFactory::getByName('clinic')->getOneById($clinic_id);

    if ($clinic && $clinic->phones) {
      $this->view->phones = $clinic->phones;
      $html = $this->renderInString('clinic/blocks/clinic_phones');
      JsonResponse::result(array('html' => $html));
    } else {
      JsonResponse::result(array('html' => ''));
    }
  }

  public function getPurposeOfVisitListByScheduleId()
  {
    $this->layout = 'ajax';

    $schedule_id = $this->request('schedule_id');

    $schedule_manager = new ScheduleManager();
    $schedule = $schedule_manager->getOneById($schedule_id);

    $purpose_manager = new PurposeOfVisitManager();
    $purposes = $purpose_manager->getListByDoctorIdAndClinicIdAndSpecialtyId($schedule->doctor_id, $schedule->clinic_id, $schedule->specialty_id);
    $this->view->select_style = 'width: 290px;';
    $this->view->purposes = $purposes;

    $html = $this->renderInString('ajax/purposes_select');

    JsonResponse::result(array('html' => $html));
  }

  public function getSpecialtyListByPurposeOfVisitId()
  {
    $purpose_of_visit_id = $this->request('purpose_of_visit_id');
    $specialty_manager = new SpecialtyManager();
    $specialties = $specialty_manager->getListByPurposeOfVisitId($purpose_of_visit_id);

    $result = array();

    if ($specialties) {
      foreach ($specialties as $specialty) {
        $result[] = array(
          'id' => $specialty->id,
          'name' => $specialty->name
        );
      }
    }

    JsonResponse::result($result);
  }

    public function metroStations()
    {
        $cityId = $this->request('cityId');
        $term = $this->request('term');
        $manager = new MetroStationManager();
        $stations = $manager->getListByNameSampleAndCityId($term, $cityId);

        $result = [];

        if ($stations) {
            foreach ($stations as $station) {
                $result[] = [
                    'id' => $station->id,
                    'label' => $station->name,
                    'stationName' => $station->name,
                    'branchName' => $station->metro_branch->name,
                    'lat' => $station->latitude,
                    'lon' => $station->longitude,
                ];
            }
        }

        JsonResponse::rawResult($result);
        exit(0);
    }

  public function createAppeal()
  {
    if (!$this->current_account || !$this->current_account->is_call_centre_operator) {
      JsonResponse::error(ValidationErrorCodes::ACCESS_DENIED);
    }

    $first_name = $this->request->post('first_name');
    $middle_name = $this->request->post('middle_name');
    $last_name = $this->request->post('last_name');
    $visit_source_id = $this->request->post('visit_source_id');
    $appeal_type_id = $this->request->post('appeal_type_id');
    $specialty_id = $this->request->post('specialty_id');
    $is_with_visit = $this->request->post('is_with_visit');
    $title = $this->request->post('title');
    $phone_number = $this->request->post('phone_number');
    $target_call_id = $this->request->post('target_call_id');

    $appeal = new AppealModel();
    $appeal->first_name = $first_name;
    $appeal->middle_name = $middle_name;
    $appeal->last_name = $last_name;
    $appeal->visit_source_id = $visit_source_id;
    $appeal->appeal_type_id = $appeal_type_id;
    $appeal->specialty_id = $specialty_id;
    $appeal->is_with_visit = $is_with_visit;
    $appeal->title = $title;
    $appeal->phone_number = $phone_number;
    $appeal->target_call_id = $target_call_id;

    $city = SeoLinksHelper::getCityByPageLink();
    $appeal->city_id = $city->id;

    if ($appeal->appeal_type_id == 2) {
      $appeal->do_not_check = array('specialty_id');
    }

    if ($appeal->save()) {
        $info = [];
        $info['full_name'] = $first_name.' '.$middle_name.' '.$last_name;
        $info['phone'] = $phone_number;
        $info['appeal_id'] = $appeal->getId();
        $info['account'] = Acc::accountId() ? (new AccountManager())->getOneById(Acc::accountId()) : false;

        $mail_sender = new EmailSenderHelper();
        $mail_sender->sendAppealInformation($info);
      JsonResponse::result(true, $appeal->is_with_visit ? $appeal->getVisit()->id : null);
    } else {

      JsonResponse::error($appeal->getValidator()->getErrorCodes());
    }
  }

  public function getAccountInfoByPhoneNumber()
  {
    if (!$this->current_account || !$this->current_account->is_call_centre_operator) {
      JsonResponse::error(ValidationErrorCodes::ACCESS_DENIED);
    }

    $phone_number = $this->request('phone_number');

    $account_manager = new AccountManager();
    $account = $account_manager->getOneByPhoneNumber($phone_number);

    if ($account) {
      $result = array(
        'id' => $account->getId(),
        'first_name' => $account->first_name,
        'middle_name' => $account->middle_name,
        'last_name' => $account->last_name
      );

      JsonResponse::result($result);
    } else {
      JsonResponse::error(ValidationErrorCodes::UNKNOWN_PHONE_NUMBER);
    }
  }

  public function getPhoneByClinicId()
  {
    $clinic_id = $this->request('clinic_id', 0);

    $clinic_manager = ModelManagerFactory::getByName('clinic');

    $clinic = $clinic_manager->getOneById($clinic_id);

    $result = '';
    foreach ($clinic->phones as $phone) {
      $phone_number = preg_replace('/\D/', '', $phone->phone_number);
      $result .= ($phone_number . ', ');
    }

    $result = rtrim($result);
    $result = rtrim($result, ',');

    JsonResponse::result($result);
  }

  public function getClinicsByNameOrAddress()
  {
    $query = $this->request('query');

    $clinic_manager = new ClinicManager();
    $clinics = $clinic_manager->getListByNameOrAddress($query);

    $result = array();

    foreach ($clinics as $clinic) {
      $result[] = array(
        'id' => $clinic->getId(),
        'name' => $clinic->name_with_address,
      );
    }

    JsonResponse::result($result);
  }

  public function getSeoInfoByCityIdAndSpecialtyId()
  {
    $city_id = $this->request('city_id');
    $district_id = $this->request('district_id');
    $region_id = $this->request('region_id');
    $street_id = $this->request('street_id');
    $metro_station_id = $this->request('metro_station_id');
    $specialty_id = $this->request('specialty_id');
    $specialization_id = $this->request('specialization_id');
    $location = $this->request('location');


    /**
     * @var CityManager $city_manager
     * @var SpecialtyManager $specialty_manager
     * @var SpecializationManager $specialization_manager
     * @var CityModel $city
     * @var SpecialtyModel $specialty
     */
    $specialty_manager = ModelManagerFactory::getByName('specialty');
    $specialization_manager = ModelManagerFactory::getByName('specialization');
    $city_manager = ModelManagerFactory::getByName('city');
    /** @var MetroStationManager $metro_manager */
    $metro_manager = ModelManagerFactory::getByName('metro_station');
    /** @var StreetManager $street_manager */
    $street_manager = ModelManagerFactory::getByName('street');
    /** @var RegionManager $region_manager */
    $region_manager = ModelManagerFactory::getByName('region');
    /** @var DistrictManager $district_manager */
    $district_manager = ModelManagerFactory::getByName('district');
    $specialty = $specialty_manager->getOneById($specialty_id);
    /** @var SpecializationModel|null $specialization */
    $specialization = $specialization_id ? $specialization_manager->getOneById($specialization_id) : null;

    if ($city_id == 0) {
      $city_id = $this->city->getId();
    }

      /** @var MetroStationModel|StreetModel|RegionModel|DistrictModel|CityModel|null $address_object */
      $address_object = null;

      if ($metro_station_id) {
          $address_object = $metro_manager->getOneById($metro_station_id);
      } elseif ($street_id) {
          $address_object = $street_manager->getOneById($street_id);
      } elseif ($region_id) {
          $address_object = $region_manager->getOneById($region_id);
      } elseif ($district_id) {
          $address_object = $district_manager->getOneById($district_id);
      } elseif ($city_id) {
          $address_object = $city_manager->getOneById($city_id);
      }

      if (!$address_object) {
          JsonResponse::error(ValidationErrorCodes::WRONG_DATA);
      }

      if ($specialization) {
          $page_title = SeoTextViewHelper::getClinicListingPageDescription($specialization, $address_object);
      } else {
          $page_title = '';
      }

    $this->view->address_object = $address_object;
    $this->view->city_id = $city_id;
    $this->view->specialty = $specialty;
    $this->view->specialization = $specialization;
    $this->view->is_seo_page = 1;
    $this->view->specialties = $specialty_manager->getHavingDoctorsListByAddressObject($address_object);
    $this->view->specialties_groups = SpecialtyHelper::getSpecialtiesLetterGroups($this->view->specialties);
    $description = '';
    $h1 = '';
    $breadcrumbs = '';
    switch ($location) {
        case 'clinic':
            $this->view->getSpecializationsGroups = [$specialization_manager, 'getActiveSpecializationsByCity'];
            $description = SeoTextViewHelper::getDescription($specialization, $address_object, []);
            $h1 = SeoTextViewHelper::getSpecializationH1($specialization, $address_object);
            $breadcrumbs = $this->renderBlockInString('clinic/blocks/breadcrumbs');
            break;
        case 'doctor':
            $description = SeoTextViewHelper::getDescription($specialty, $address_object, []);
            $h1 = SeoTextViewHelper::getSpecialtyH1($specialty, $address_object);
            $breadcrumbs = $this->renderBlockInString('doctor/blocks/breadcrumbs');
            break;
        default:
            JsonResponse::error(ValidationErrorCodes::WRONG_DATA);
    }

    $html = $this->renderInString($location . '/blocks/seo_block');

    $data = [
      'html' => $html,
      'description' => $description,
      'h1' => $h1,
      'page_title' => $page_title,
      'breadcrumbs' => $breadcrumbs,
    ];
    JsonResponse::result($data);
  }

  public function addCallToUser()
  {
    $phone = $this->request->post('phone');
    $name = $this->request->post('name');
    $phone = preg_replace('/[^0-9]/', '', $phone);

    if (strlen($phone) == 11) {

      $call_to_user = new CallToUserModel();
      if ($name) {
        $call_to_user->name = $name;
      }
      $call_to_user->phone = $phone;
      $call_to_user->dt = date('Y-m-d H:i:s');

      if ($call_to_user->save()) {
        $_SESSION['sentDiscountRequest'] = true;

        $mail_sender = new EmailSenderHelper();
        $mail_data = [
          'id' => ['title' => 'Заказ звонка', 'value' => $call_to_user->getId(),],
          'phone' => ['title' => 'Телефон пациента', 'value' => $phone, ],
        ];

        if($name && $name != 'Запрос на скидку'){
            $mail_data['fio'] = ['title' => 'Имя пациента', 'value' => $name,];
        }elseif ($name == 'Запрос на скидку'){
            $mail_data['fio'] = ['title' => '', 'value' => $name,];
        }
        $mail_sender->sendVisitCreatedMessage($mail_data);

        JsonResponse::result(true);
      }
    } else {
      JsonResponse::error(ValidationErrorCodes::IS_REQUIRED_FIELD, 'Поля формы не заполнены');
    }
  }

  public function getClinicsByCityId()
  {
    /**
     * @var ClinicManager $clinic_manager
     * @var ClinicSearchParams $clinic_search_params
     */

    $city_id = $this->request('city_id');

    $clinic_manager = ModelManagerFactory::getByName('clinic');
    $clinic_search_params = new ClinicSearchParams();
    $clinic_search_params->city_id = $city_id;
    $clinics = $clinic_manager->getListByClinicSearchParams($clinic_search_params);

    $result = array();
    foreach ($clinics as $clinic) {
      $result[] = array(
        'id' => $clinic->getId(),
        'name' => $clinic->name_with_address,
      );
    }

    JsonResponse::result($result);
  }

  public function getSpecialtiesByClinicId()
  {
    /**
     * @var ClinicManager $clinic_manager
     * @var ClinicModel $clinic
     * @var SpecialtyModel[] $specialties
     * @var VisitManager $visit_manager
     * @var VisitModel $visit
     */

    $clinic_id = $this->request('clinic_id');
    $visit_id = $this->request('visit_id');

    if ($clinic_id) {
      $clinic_manager = ModelManagerFactory::getByName('clinic');
      $clinic = $clinic_manager->getOneById($clinic_id);
      $specialties = $clinic->specialties;

      $result = array();
      foreach ($specialties as $specialty) {
        $result[] = array(
          'id' => $specialty->getId(),
          'name' => $specialty->name
        );
      }

      $visit_specialty = '';
      if ($visit_id) {
        $visit_manager = ModelManagerFactory::getByName('visit');
        $visit = $visit_manager->getOneById($visit_id);

        if ($visit) {
          $visit_specialty = $visit->specialty_id;
        }
      }

      JsonResponse::result(array('specialties' => $result, 'visit_specialty' => $visit_specialty));
    }
  }

  public function getDoctorAndClinicPrices()
  {
    $clinic_id = $this->request('clinic_id');
    $specialty_id = $this->request('specialty_id');
    $doctor_id = $this->request('doctor_id');

    $purpose_of_visit_to_clinic_first_price = '';
    $purpose_of_visit_to_clinic_second_price = '';
    $purpose_of_visit_to_doctor_first_price = '';
    $purpose_of_visit_to_doctor_second_price = '';

    if ($clinic_id && $specialty_id) {
      /**
       * @var PurposeOfVisitManager $purpose_of_visit_manager
       * @var PurposeOfVisitModel $first_visit
       * @var PurposeOfVisitModel $second_visit
       * @var PurposeOfVisitToClinicManager $purpose_of_visit_to_clinic_manager
       * @var PurposeOfVisitToClinicModel $purpose_of_visit_to_clinic_first
       * @var PurposeOfVisitToClinicModel $purpose_of_visit_to_clinic_second
       * @var PurposeOfVisitToDoctorManager $purpose_of_visit_to_doctor_manager
       * @var PurposeOfVisitToDoctorModel $purpose_of_visit_to_doctor_first
       * @var PurposeOfVisitToDoctorModel $purpose_of_visit_to_doctor_second
       */

      $purpose_of_visit_manager = ModelManagerFactory::getByName('purpose_of_visit');
      $first_visit = $purpose_of_visit_manager->getOneByName('Первичный прием');
      $second_visit = $purpose_of_visit_manager->getOneByName('Повторный прием');

      $purpose_of_visit_to_clinic_manager = ModelManagerFactory::getByName('purpose_of_visit_to_clinic');
      $purpose_of_visit_to_clinic_first = $purpose_of_visit_to_clinic_manager->getOneByClinicIdAndSpecialtyIdAndPurposeOfVisitId(
        $clinic_id,
        $specialty_id,
        $first_visit->getId()
      );
      $purpose_of_visit_to_clinic_second = $purpose_of_visit_to_clinic_manager->getOneByClinicIdAndSpecialtyIdAndPurposeOfVisitId(
        $clinic_id,
        $specialty_id,
        $second_visit->getId()
      );

      $purpose_of_visit_to_clinic_first_price = ($purpose_of_visit_to_clinic_first) ? $purpose_of_visit_to_clinic_first->visit_price : '';
      $purpose_of_visit_to_clinic_second_price = ($purpose_of_visit_to_clinic_second) ? $purpose_of_visit_to_clinic_second->visit_price : '';

      if ($doctor_id) {
        $purpose_of_visit_to_doctor_manager = ModelManagerFactory::getByName('purpose_of_visit_to_doctor');
        $purpose_of_visit_to_doctor_first = $purpose_of_visit_to_doctor_manager->getOneByClinicIdAndDoctorIdAndSpecialtyIdAndPurposeOfVisitId(
          $clinic_id,
          $doctor_id,
          $specialty_id,
          $first_visit->getId()
        );
        $purpose_of_visit_to_doctor_second = $purpose_of_visit_to_doctor_manager->getOneByClinicIdAndDoctorIdAndSpecialtyIdAndPurposeOfVisitId(
          $clinic_id,
          $doctor_id,
          $specialty_id,
          $second_visit->getId()
        );

        $purpose_of_visit_to_doctor_first_price = ($purpose_of_visit_to_doctor_first) ? $purpose_of_visit_to_doctor_first->visit_price : '';
        $purpose_of_visit_to_doctor_second_price = ($purpose_of_visit_to_doctor_second) ? $purpose_of_visit_to_doctor_second->visit_price : '';
      }
    }

    JsonResponse::result(
      array(
        'clinic_first' => $purpose_of_visit_to_clinic_first_price,
        'clinic_second' => $purpose_of_visit_to_clinic_second_price,
        'doctor_first' => $purpose_of_visit_to_doctor_first_price,
        'doctor_second' => $purpose_of_visit_to_doctor_second_price,
      )
    );
  }

  public function checkTargetCallPhone()
  {
    $phone = $this->request->post('phone');
    $result = false;

    if ($phone) {
      /**
       * @var TargetCallManager $target_phone_manager
       * @var TargetCallModel $target_phone
       */
      $target_phone_manager = ModelManagerFactory::getByName('target_call');
      $target_phone = $target_phone_manager->getOneByPhone($phone);

      if (!$target_phone) {
        $result = true;
      }
    }

    if ($result) {
      JsonResponse::result(true);
    } else {
      JsonResponse::result(false);
    }
  }

  public function landingRecord()
  {
    $full_name = $this->request('name');
    $phone = $this->request('phone');
    $comment = $this->request('comment');
    $specialty_id = $this->request('specialty_id');

    if (!$full_name || !$phone) {
      JsonResponse::error(ValidationErrorCodes::IS_REQUIRED_FIELD, 'Необходимые поля формы не заполнены');
    }

    /**
     * @var AccountPhoneManager $account_phone_manager
     */
    $account_phone_manager = ModelManagerFactory::getByName('account_phone');
    $account_phone = $account_phone_manager->getOneConfirmedByPhoneNumber($phone);
    if (!$account_phone) {
      $account = new AccountModel();
      $account->disableValidation();
      $account->save();

      $account_phone = new AccountPhoneModel();
      $account_phone->account_id = $account->getId();
      $account_phone->phone = $phone;
      $account_phone->is_confirmed = 1;
      $account_phone->dt = DateHelper::now();
      $account_phone->save();

      $account_id = $account->getId();
    } else {
      $account_id = $account_phone->account_id;
    }

    if (isset($this->city) && $this->city) {
      $city_id = $this->city->getId();
    } else {
      $city_id = null;
    }

    $visit = new VisitModel();
    $visit->full_name = $full_name;
    $visit->phone = $phone;
    $visit->comment = $comment;
    $visit->specialty_id = $specialty_id;
    $visit->account_id = $account_id;
    $visit->city_id = $city_id;
    $visit->visit_channel_id = VisitChannelModel::LANDING;

    if (!$visit->save()) {
      $error_code = $visit->getValidator()->getErrorCodes();
      JsonResponse::error($error_code[0]);
    }

    JsonResponse::result(true);
  }

  public function checkUniqueWidgetName()
  {
    $name = $this->request('value');

    if (!$name) {
      JsonResponse::error(ValidationErrorCodes::IS_REQUIRED_FIELD);
    }

    $sql = 'SELECT COUNT(*) as result
                    FROM widget
                    WHERE name = "' . Register::get('db')->escape($name) . '"';

    $data = Register::get('db')->query($sql);

    if ($data[0]['result']) {
      JsonResponse::result(false);
    } else {
      JsonResponse::result(true);
    }
  }

  public function checkUniqueWidgetFolder()
  {
    $folder = $this->request('value');

    if (!$folder) {
      JsonResponse::error(ValidationErrorCodes::IS_REQUIRED_FIELD);
    }

    $sql = 'SELECT COUNT(*) as result
                    FROM widget
                    WHERE folder = "' . Register::get('db')->escape($folder) . '"';

    $data = Register::get('db')->query($sql);

    if ($data[0]['result']) {
      JsonResponse::result(false);
    } else {
      JsonResponse::result(true);
    }
  }

  public function changeBannerVisibility()
  {
    $key = $this->request('key');
    $value = $this->request('value');
    $_SESSION[$key] = $value;
    JsonResponse::result(true);
  }

  public function content()
  {
      if (is_array($this->request->post('keys'))) {
          $result = [];
          foreach ($this->request->post('keys') as $key) {
              $result[] = ['key' => $key, 'value' => SeoHideHelper::getContent($key)];
          }
          JsonResponse::result($result);
      } else {
          JsonResponse::result([]);
      }
  }
}
