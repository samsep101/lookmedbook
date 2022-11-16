<?php

/**
 * @property int $id
 * @property string $first_name
 * @property string $last_name
 * @property string $middle_name
 * @property string $full_name
 * @property string $nick
 * @property int $sex_id
 * @property SexModel $sex
 * @property string $birthday
 * @property string $email
 * @property int $is_confirm_email
 * @property string $email_confirm_code
 * @property string $password_hash
 * @property string $password
 * @property int $image_id
 * @property ImageModel $image
 * @property int $city_id
 * @property CityModel $city
 * @property datetime $dt
 * @property int $is_confirmed
 * @property int $is_system_access
 *
 * @property int $age
 * @property AccountPhoneModel[] $phones
 * @property AccountPhoneModel[] $not_confirmed_phones
 * @property NotificationSettingsModel $notifications
 * @property VisitModel[] $coming_visits
 * @property VisitModel $last_uncommented_visit
 * @property ClinicModel[] $favorite_clinics
 * @property DoctorModel[] $favorite_doctors
 * @property DiseaseModel[] $selected_diseases
 * @property string $display_name
 * @property NotificationSettingsModel $notification_settings
 * @property int $closed_visit
 * @property int $open_visit
 * @property int $is_call_centre_operator
 * @property MobileNotificationTokenModel $mobile_notification_token
 * @property string $password_hash_temp
 * @property string $session_hash
 * @property string $last_succes_callup
 * @property int $is_product_admin
 */
class AccountModel extends DynamicModel
{
  protected function _field_age()
  {
    $this->age = Date::getAge($this->birthday);
    return $this->age;
  }

  protected function _field_phones()
  {
    $account_phone_manager = new AccountPhoneManager();
    $this->phones = $account_phone_manager->getConfirmedListByAccountId($this->id);
    return $this->phones;
  }

  protected function _field_not_confirmed_phones()
  {
    $account_phone_manager = new AccountPhoneManager();
    $this->not_confirmed_phones = $account_phone_manager->getNotConfirmedListByAccountId($this->id);
    return $this->not_confirmed_phones;
  }

  public function save_phones($acc_id, $phones)
  {
    if (!$acc_id) {
      return false;
    }
    $phone_list = array_map(function ($a) {
      return trim($a);
    }, explode(',', $phones));

    $account_phone_manager = new AccountPhoneManager();
    $orig_phones = $account_phone_manager->getAllByAccountId($acc_id);
    $or_phone_list = [];
    foreach($orig_phones as $ph_id=>$phone_obj) {
      $ph_number = $phone_obj->phone;
      $or_phone_list[$ph_number] = $ph_id;
    }
    //новые телефоны добавляем
    foreach($phone_list as $ph_number) {
      if(isset($or_phone_list[$ph_number])) {
        unset($or_phone_list[$ph_number]);
      }else{
        $account_phone = new AccountPhoneModel();
        $account_phone->account_id = $acc_id;
        $account_phone->phone = $ph_number;
        $account_phone->dt = date('Y-m-d H:i');
        if(!$account_phone->save()) {
          return $account_phone->getValidator()->getErrorMessages();
        }
      }
    }
    //старые убиваем
    $account_phone_manager->deleteByPhones(array_keys($or_phone_list));

    return 0;
  }


  protected function _field_notifications()
  {
    $this->notifications = ModelManagerFactory::getByName('notification_settings')->getOneByAccountId($this->id);
    return $this->notifications;
  }

  protected function _field_coming_visits()
  {
    $visit_manager = ModelManagerFactory::getByName('visit');
    $this->coming_visits = $visit_manager->getComingListByAccountId($this->id);

    return $this->coming_visits;
  }

  protected function _field_last_uncommented_visit()
  {
    $visit_manager = new VisitManager();
    return $visit_manager->getOneLastUncommentedByAccountId($this->id);
  }

  protected function _field_favorite_clinics()
  {
    $clinic_manager = new ClinicManager();

    $this->favorite_clinics = $clinic_manager->getFavoriteListByAccountId($this->id);
    return $this->favorite_clinics;
  }

  protected function _field_favorite_doctors()
  {
    $doctor_manager = new DoctorManager();

    $this->favorite_doctors = $doctor_manager->getFavoriteListByAccountId($this->id);
    return $this->favorite_doctors;
  }

  protected function _field_selected_diseases()
  {
    $disease_manager = new DiseaseManager();
    $this->selected_diseases = $disease_manager->getSelectedListByAccountId($this->id);
    return $this->selected_diseases;
  }

  protected function _field_display_name()
  {
    if (!isset($this->display_name)) {
      if ($this->nick) {
        $this->display_name = $this->nick;
      } elseif ($this->first_name) {
        $this->display_name = $this->first_name;
      } else {
        $this->display_name = 'Анонимный пациент';
      }
    }

    return $this->display_name;
  }

  public function _field_notification_settings()
  {
    if (!isset($this->notification_settings)) {
      $notification_settings_manager = new NotificationSettingsManager();
      $this->notification_settings = $notification_settings_manager->getOneByAccountId($this->getId());
    }

    return $this->notification_settings;
  }

  public function wasConfirmAction()
  {
    return ($this->is_confirmed == 1 && ($this->params['is_confirmed'] == 0)) ? true : false;
  }


  public function wasChangeEmail()
  {
    return ($this->email != $this->params['email']) ? true : false;
  }

  public function getPreviousEmail()
  {
    return $this->params['email'];
  }

  protected function _field_closed_visit()
  {
    $visit_manager = new VisitManager();
    $closed_visits = $visit_manager->getClosedVisitByAccountId($this->getId());
    $this->closed_visit = count($closed_visits);
    return $this->closed_visit;
  }

  protected function _field_open_visit()
  {
    $visit_manager = new VisitManager();
    $open_visits = $visit_manager->getOpenVisitByAccountId($this->getId());
    $this->open_visit = count($open_visits);
    return $this->open_visit;
  }

  protected function _field_city()
  {
    $city_manager = new CityManager();
    $city = $city_manager->getOneById($this->city_id);

    return $city;
  }

  protected function _field_image()
  {
    $image_manager = new ImageManager();
    $this->image = $image_manager->getOneById($this->image_id);
    return $this->image;
  }

  protected function _field_mobile_notification_token()
  {
    if ($this->mobile_notification_token == null) {
      /**
       * @var MobileNotificationTokenManager $mobile_notification_token_manager
       */
      $mobile_notification_token_manager = ModelManagerFactory::getByName('mobile_notification_token');
      $this->mobile_notification_token = $mobile_notification_token_manager->getOneByAccountId($this->getId());
    }

    return $this->mobile_notification_token;
  }

  protected function _field_session_hash()
  {
    /**
     * @var AccountSessionManager $account_session_manager
     */

    $account_session_manager = ModelManagerFactory::getByName('account_session');
    return $account_session_manager->getOneByAccountId($this->getId());
  }
}
