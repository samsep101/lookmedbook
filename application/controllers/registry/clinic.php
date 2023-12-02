<?php

class ClinicRegistryController extends BaseController
{
  public function __construct()
  {
    parent::__construct();
    $this->layout = 'registry';
  }

  /**
   * Метод для страницы управления реквизитами клиники
   */
  public function requisites()
  {
    $clinic_id = RegistryAccessHelper::checkAccessAndDetermineClinicId();

    $clinic_manager = new ClinicManager();
    $clinic = $clinic_manager->getOneById($clinic_id);
    $this->view->clinic = $clinic;

    $moderate_clinic_requisites_manager = new ModerateClinicRequisitesManager();
    $clinic_requisites = $moderate_clinic_requisites_manager->getCurrentRevision($clinic_id);

    $view_processor = new FormViewProcessor('moderate_clinic_requisites', $clinic_requisites);
    $this->view->view_processor = $view_processor;

    $this->view->entry_id = $clinic_id;
    $this->view->model_name = 'moderate_clinic_requisites';
    $this->view->model = $clinic_requisites;
    $this->view->menu_type = 'clinic';
    $this->view->menu_active = 'requisites';
    $this->view->clinic_id = $clinic_id;
  }

  /**
   * Метод для страницы управления описанием клиники
   */
  public function description()
  {
    $clinic_id = RegistryAccessHelper::checkAccessAndDetermineClinicId();

    $clinic_manager = new ClinicManager();
    $clinic = $clinic_manager->getOneById($clinic_id);
    $this->view->clinic = $clinic;

    $moderate_clinic_description_manager = new ModerateClinicDescriptionManager();
    $clinic_description = $moderate_clinic_description_manager->getCurrentRevision($clinic_id);

    $view_processor = new FormViewProcessor('moderate_clinic_description', $clinic_description);
    $this->view->view_processor = $view_processor;

    $this->view->entry_id = $clinic_id;
    $this->view->model_name = 'moderate_clinic_description';
    $this->view->model = $clinic_description;
    $this->view->menu_type = 'clinic';
    $this->view->menu_active = 'description';
    $this->view->clinic_id = $clinic_id;
  }

  /**
   * Метод для страницы управления основной информацией о клинике
   */
  public function information()
  {
    $clinic_id = RegistryAccessHelper::checkAccessAndDetermineClinicId();

    $clinic_manager = new ClinicManager();
    $clinic = $clinic_manager->getOneById($clinic_id);
    $this->view->clinic = $clinic;

    $moderate_clinic_information_manager = new ModerateClinicInformationManager();
    $clinic_information = $moderate_clinic_information_manager->getCurrentRevision($clinic_id);

    $view_processor = new FormViewProcessor('moderate_clinic_information', $clinic_information);
    $this->view->view_processor = $view_processor;

    $this->view->entry_id = $clinic_id;
    $this->view->model_name = 'moderate_clinic_information';
    $this->view->model = $clinic_information;
    $this->view->menu_type = 'clinic';
    $this->view->menu_active = 'information';
    $this->view->clinic_id = $clinic_id;

    $moderate_clinic_phone_manager = new ModerateClinicPhoneManager();
    $revision_condition = array(
      'clinic_id' => $clinic_id
    );
    $phones_revision = $moderate_clinic_phone_manager->getCurrentRevision($revision_condition);

    $this->view->phones = $phones_revision->elements;

    $moderate_clinic_email_manager = new ModerateClinicEmailManager();
    $emails_revision = $moderate_clinic_email_manager->getCurrentRevision($revision_condition);
    $this->view->emails = $emails_revision->elements;

    $moderate_metro_station_to_clinic_manager = new ModerateMetroStationToClinicManager();
    $metro_station_revision = $moderate_metro_station_to_clinic_manager->getCurrentRevision($revision_condition);
    $this->view->metro_stations = $metro_station_revision->elements;
  }

  /**
   * Метод для страницы управления лицензициями клиники
   */
  public function license()
  {
    $clinic_id = RegistryAccessHelper::checkAccessAndDetermineClinicId();

    $clinic_manager = new ClinicManager();
    $clinic = $clinic_manager->getOneById($clinic_id);
    $this->view->clinic = $clinic;

    $revision_condition = array(
      'clinic_id' => $clinic->getId()
    );

    $moderate_clinic_license_manager = new ModerateClinicLicenseManager();
    $clinic_license = $moderate_clinic_license_manager->getCurrentRevision($clinic_id);


    $view_processor = new FormViewProcessor('moderate_clinic_license', $clinic_license);
    $this->view->view_processor = $view_processor;

    $moderate_clinic_license_image_manager = new ModerateClinicLicenseImageManager();
    $image_revision = $moderate_clinic_license_image_manager->getCurrentRevision($revision_condition);
    $this->view->license_images = $image_revision->elements;


    $this->view->entry_id = $clinic_id;
    $this->view->model_name = 'moderate_clinic_license';
    $this->view->model = $clinic_license;
    $this->view->menu_type = 'clinic';
    $this->view->menu_active = 'license';
    $this->view->clinic_id = $clinic_id;

    $specialization_to_clinic_manager = new ModerateSpecializationToClinicManager();
    $revision = $specialization_to_clinic_manager->getCurrentRevision($revision_condition);

    $this->view->specializations = $specialization_to_clinic_manager->getListByClinicIdAndRevisionNumber($clinic->getId(), $revision->revision_info->revision_number);
  }

  /**
   * Метод для страницы управления сервисами клиники
   */
  public function service()
  {
    $clinic_id = RegistryAccessHelper::checkAccessAndDetermineClinicId();

    $clinic_manager = new ClinicManager();
    $clinic = $clinic_manager->getOneById($clinic_id);
    $this->view->clinic = $clinic;

    $revision_condition = array(
      'clinic_id' => $clinic_id
    );

    $moderate_feature_to_clinic_manager = new ModerateFeatureToClinicManager();
    $feature_to_clinic = $moderate_feature_to_clinic_manager->getCurrentRevision($revision_condition);

    $revision_number = $moderate_feature_to_clinic_manager->getLastRevisionNumberByRevisionCondition($revision_condition);
    $this->view->revision_number = $revision_number;

    $moderate_clinic_information_manager = new ModerateClinicInformationManager();
    $clinic_information = $moderate_clinic_information_manager->getCurrentRevision($clinic_id);

    $view_processor = new FormViewProcessor('moderate_clinic_information', $clinic_information);
    $this->view->view_processor = $view_processor;

    $this->view->entry_id = $clinic_id;
    $this->view->model_name = null;
    $this->view->model = $feature_to_clinic;
    $this->view->menu_type = 'clinic';
    $this->view->menu_active = 'service';
    $this->view->features = $moderate_feature_to_clinic_manager->getListByClinicId($clinic_id);
    $this->view->clinic_id = $clinic_id;
    $this->view->model_name = 'moderate_clinic_information';

    $suggested_features_manager = new SuggestedFeaturesManager();
    $this->view->suggested_features = $suggested_features_manager->getListByStatusIdAndClinicId(ModerateStatusModel::EDIT, $clinic_id);
  }

  /**
   * Метод для страницы управления услугами клиники
   */
  public function services()
  {
    $clinic_id = RegistryAccessHelper::checkAccessAndDetermineClinicId();

    $clinic_manager = new ClinicManager();
    $clinic = $clinic_manager->getOneById($clinic_id);
    $this->view->clinic = $clinic;

    $this->view->menu_type = 'clinic';
    $this->view->menu_active = 'services';

    $revision_condition = array(
      'clinic_id' => $clinic_id
    );

    $moderate_specialty_to_clinic_manager = new ModerateSpecialtyToClinicManager();
    $moderate_specialty_to_clinic_manager->getCurrentRevision($revision_condition);

    $moderate_specialty_to_clinic = $moderate_specialty_to_clinic_manager->getCurrentRevision($revision_condition);
    $this->view->model = $moderate_specialty_to_clinic;

    $moderate_specialization_to_clinic_manager = new ModerateSpecializationToClinicManager();
    $revision_number = $moderate_specialization_to_clinic_manager->getLastRevisionNumberByRevisionCondition($revision_condition);
    $this->view->specializations = $moderate_specialization_to_clinic_manager->getListByClinicIdAndRevisionNumber($clinic_id, $revision_number);

    $this->view->entry_id = $clinic_id;
    $this->view->revision_number = $revision_number;
    $this->view->clinic_id = $clinic_id;

    $moderate_clinic_pricelist_manager = new ModerateClinicPricelistManager();
    $pricelist_revision = $moderate_clinic_pricelist_manager->getCurrentRevision($revision_condition);
    $this->view->pricelist_files = $pricelist_revision->elements;
  }


  /**
   * Метод для страницы управления
   */
  public function brif_information()
  {
    $clinic_id = RegistryAccessHelper::checkAccessAndDetermineClinicId();

    $clinic_manager = new ClinicManager();
    $clinic = $clinic_manager->getOneById($clinic_id);
    $this->view->clinic = $clinic;

    $moderate_clinic_user_manager = new ModerateClinicUserManager();
    $clinic_user = $moderate_clinic_user_manager->getCurrentRevision($clinic_id);
    $phone_number = $clinic_user->phone;
    $clinic_user->phone = substr($phone_number, 1);

    $view_processor = new FormViewProcessor('moderate_clinic_user', $clinic_user);
    $this->view->view_processor = $view_processor;

    $this->view->entry_id = $clinic_id;
    $this->view->model_name = 'moderate_clinic_user';
    $this->view->model = $clinic_user;
    $this->view->menu_type = 'clinic';
    $this->view->menu_active = 'brif_information';
    $this->view->clinic_id = $clinic_id;

  }

  public function seo(){
    $clinic_id = RegistryAccessHelper::checkAccessAndDetermineClinicId();

    $clinic_manager = new ClinicManager();
    $clinic = $clinic_manager->getOneById($clinic_id);
    $this->view->clinic = $clinic;
    $this->view->seo_title = SeoTextViewHelper::GetClinicSeoTitle($clinic);
    $this->view->seo_description = SeoTextViewHelper::newGetClinicPageDescription($clinic);

    $moderate_clinic_user_seo = new ModerateClinicSeoManager();
    $seo = $moderate_clinic_user_seo->getByClinicId($clinic_id);
    //var_dump($seo); exit;


    $view_processor = new FormViewProcessor('moderate_clinic_seo', $seo);

    $this->view->view_processor = $view_processor;

    $this->view->entry_id = $clinic_id;
    $this->view->model_name = 'moderate_clinic_seo';
    $this->view->model = $clinic_user;
    $this->view->menu_type = 'clinic';
    $this->view->menu_active = 'seo';
    $this->view->clinic_id = $clinic_id;
    $this->view->seo = $seo;
  }


  public function actionDelete(){
      $delete_action = (new ActionManager())->getOneById(intval($this->request('delete_action_id')));
      $manager = new ActionManager();
      $manager->delete($delete_action);

      RedirectManager::redirect('/registry/clinic/action?clinic_id='.intval($this->request('clinic_id')));
  }

    public function actionSave(){

        if ($this->request('edit_action_id')){
            $new_action = (new ActionManager())->getOneById(intval($this->request('edit_action_id')));
        }else{
            $new_action = new ActionModel();
        }


        $data = $_REQUEST['form'];
        $new_action->name = $data['name'];
        $new_action->date_from = DateHelper::changeFormat($data['date_from'], '-') ;
        $new_action->date_to = DateHelper::changeFormat($data['date_to'], '-');
        $new_action->info = $data['info'];
        $new_action->clinic_id = $this->request('clinic_id');

        $manager = new ActionManager();
        $manager->save($new_action);


        $targetFolder = 'clinic/actions/';
        $alias = 'icon_'.$new_action->id;
        $alias_full_width = 'icon_'.$new_action->id;

        $upload_data = array(
            'upload_folder' => $targetFolder,
        );

        $file_data = array();

        if (!empty($_FILES)) {

          if (isset($_FILES['icon'])) {
            $file_data = $_FILES['icon'];
          }

          if ($file_data['error'] == 0){
            $new_action->image_id = $image_id = ImageUploader::upload($upload_data, $file_data, $alias);
            $manager = new ActionManager();
            $manager->save($new_action);
          }

          if (isset($_FILES['icon_full_width'])) {
            $file_data = $_FILES['icon_full_width'];
          }

          if ($file_data['error'] == 0){
            $new_action->image_full_width_id = $image_id = ImageUploader::upload($upload_data, $file_data, $alias_full_width);
            $manager = new ActionManager();
            $manager->save($new_action);
          }



        }





        if (isset($_REQUEST['specialization_id']) && is_array($_REQUEST['specialization_id'])){
            /**TODO
             * переделать на ORM
             */
            $q = "delete from action_to_specialization where action_id = '".$new_action->getId()."' ";
            Register::get('db')->query($q);
            foreach ($_REQUEST['specialization_id'] as $spec_id){
                $q = "insert into action_to_specialization set action_id='".$new_action->getId()."', specialization_id='".intval($spec_id)."' ";
                Register::get('db')->query($q);
            }
        }

        RedirectManager::redirect('/registry/clinic/action?clinic_id='.$new_action->clinic_id);
    }

    public function action(){
        $clinic_id = RegistryAccessHelper::checkAccessAndDetermineClinicId();

        $clinic = (new ClinicManager())->getOneById($clinic_id);
        $this->view->clinic = $clinic;

        $this->view->entry_id = $clinic_id;
        $this->view->model_name = 'moderate_clinic_user';
        $this->view->model = $clinic;
        $this->view->menu_type = 'clinic';
        $this->view->menu_active = 'action';
        $this->view->clinic_id = $clinic_id;

        $action_manager = new ActionManager();
        $clinic_actions = $action_manager->getListForClinic($clinic->id);


        $view_processor = new FormViewProcessor('moderate_clinic_license', $clinic);
        $this->view->view_processor = $view_processor;

        $this->view->specializations = (new SpecializationToClinicManager())->getListByClinicId($clinic->getId());
        $this->view->clinic_actions = $clinic_actions;

        if ($edit_action_id = $this->request('edit_action_id')){
            $this->view->edit_action = (new ActionManager())->getOneById($edit_action_id);
        }else{
            $this->view->edit_action = new ActionModel();
        }
    }



  /**
   * Метода для страницы управления списком врачей клиники
   */
  public function doctors()
  {
    $clinic_id = RegistryAccessHelper::checkAccessAndDetermineClinicId();

    $clinic_manager = new ClinicManager();
    $clinic = $clinic_manager->getOneById($clinic_id);
    $this->view->clinic = $clinic;

    $moderate_clinic_information_manager = new ModerateClinicInformationManager();
    $clinic_information = $moderate_clinic_information_manager->getCurrentRevision($clinic_id);

    $this->view->entry_id = $clinic_id;
    $this->view->model_name = 'moderate_clinic_user';
    $this->view->model = $clinic_information;
    $this->view->menu_type = 'clinic';
    $this->view->menu_active = 'doctors';
    $this->view->clinic_id = $clinic_id;

    $doctor_manager = new DoctorManager();

    $doctors = $doctor_manager->getListByClinicId($clinic_id);
    $this->view->doctors = $doctors;

    $specialty_manager = new SpecialtyManager();
    $specialties = $specialty_manager->getListByClinicId($clinic_id);

    $this->view->specialties = $specialties;
  }

  /**
   * Метод для страницы добавления клиники
   */
  public function add()
  {
    if (!Acl::isAuthed(RoleModel::ACCOUNT_SUPER_MANAGER)
      && !Acl::isAuthed(RoleModel::ACCOUNT_MANAGER)
    )
      RedirectManager::redirect('/admin');

    $city_manager = new CityManager();
    $this->view->cities = $city_manager->getSortedList('name');
  }

  /**
   * Метод для страницы управления фотографиями клиники
   */
  public function photos()
  {
    $clinic_id = RegistryAccessHelper::checkAccessAndDetermineClinicId();

    $clinic_manager = new ClinicManager();
    $clinic = $clinic_manager->getOneById($clinic_id);
    $this->view->clinic = $clinic;

    $moderate_clinic_card_image_manager = new ModerateClinicCardImageManager();

    $clinic_card_image = $moderate_clinic_card_image_manager->getCurrentRevision($clinic_id);
    $clinic->card_image_id = $clinic_card_image->card_image_id;

    $moderate_image_to_clinic_manager = new ModerateImageToClinicManager();

    $revision_condition = array(
      'clinic_id' => $clinic_id
    );

    $clinic_images = $moderate_image_to_clinic_manager->getCurrentRevision($revision_condition);
    $this->view->clinic_images = $clinic_images->elements;


    $this->view->entry_id = $clinic_id;
    $this->view->model_name = 'moderate_clinic_card_image';
    $this->view->model = $clinic_card_image;
    $this->view->menu_type = 'clinic';
    $this->view->menu_active = 'photos';
    $this->view->clinic_id = $clinic_id;
  }

  /**
   * Метода для страницы управления расписанием клиники
   */
  public function time()
  {
    $clinic_id = RegistryAccessHelper::checkAccessAndDetermineClinicId();

    $moderate_clinic_information_manager = new ModerateClinicInformationManager();
    $clinic_information = $moderate_clinic_information_manager->getCurrentRevision($clinic_id);

    $clinic_manager = new ClinicManager();
    $clinic = $clinic_manager->getOneById($clinic_id);

    $this->view->entry_id = $clinic_id;
    $this->view->model = $clinic_information;
    $this->view->menu_type = 'clinic';
    $this->view->menu_active = 'time';
    $this->view->clinic = $clinic;
    $this->view->schedule = $clinic;
  }

  /**
   * Метод для сохранения расписания клиники
   */
  public function ajaxSaveSchedule()
  {
    if (!Acl::isAuthed(RoleModel::ACCOUNT_SUPER_MANAGER) && !Acl::isAuthed(RoleModel::ACCOUNT_REGISTRY) && !Acl::isAuthed(RoleModel::FREELANCE_MANAGER) && !Acl::isAuthed(RoleModel::ACCOUNT_MANAGER))
      JsonResponse::error(ValidationErrorCodes::NOT_AUTHED);

    $clinic_id = $this->request->post('clinic_id');

    $schedule_info = $this->request->post('schedule_info');

    $clinic_manager = new ClinicManager();

    if ($clinic_id) {
      $clinic_model = $clinic_manager->getOneById($clinic_id);
    } else {
      $clinic_model = new ClinicModel();
    }

    $clinic_model->setId($clinic_id);

    $days_of_week = DateHelper::getWeekDaysNames();

    //Количество дней, когда клиника работает некруглосуточно
    $countDayAndNights = 0;

    foreach ($days_of_week as $day_of_week) {
      if (isset($schedule_info['week'][$day_of_week]) && $schedule_info['week'][$day_of_week]) {
        $clinic_model->{'start_time_' . $day_of_week} = $schedule_info['week'][$day_of_week]['start_time'];
        $clinic_model->{'end_time_' . $day_of_week} = $schedule_info['week'][$day_of_week]['end_time'];
        $clinic_model->{$day_of_week . '_break_start_time'} = $schedule_info['week'][$day_of_week]['break']['start_time'];
        $clinic_model->{$day_of_week . '_break_end_time'} = $schedule_info['week'][$day_of_week]['break']['end_time'];
        if (($schedule_info['week'][$day_of_week]['start_time'] == '00:00') && ($schedule_info['week'][$day_of_week]['end_time'] == '00:00')) {
          $clinic_model->{'is_day_and_night'} = 1;
        } else {
          $countDayAndNights++;
        }
      } else {
        $clinic_model->{'start_time_' . $day_of_week} = null;
        $clinic_model->{'end_time_' . $day_of_week} = null;
      }
    }
    if ($countDayAndNights == count($days_of_week)) {
      $clinic_model->{'is_day_and_night'} = null;
    }

    if ($clinic_model->save()) {
      $data = array(
        'clinic_id' => $clinic_model->getId()
      );
      JsonResponse::result($data);
    } else {
      JsonResponse::result(ValidationErrorCodes::WRONG_DATA);
    }
  }

  public function ajaxCheckMetroStationForClinic()
  {
    if (!RegistryAccessHelper::checkAuth())
      JsonResponse::error(ValidationErrorCodes::NOT_AUTHED);

    $clinic_id = $this->request('clinic_id');
    $metro_station_id = $this->request('metro_station_id');

    $revision_condition = array(
      'clinic_id' => $clinic_id
    );

    $moderate_metro_station_to_clinic_manager = new ModerateMetroStationToClinicManager();
    $metro_stations = $moderate_metro_station_to_clinic_manager->getCurrentRevision($revision_condition);

    $metro_station_ids = array();
    foreach ($metro_stations->elements as $station) {
      $metro_station_ids[] = $station->metro_station_id;
    }

    if (!in_array($metro_station_id, $metro_station_ids)) {
      $metro_station = ModelManagerFactory::getByName('metro_station')->getOneById($metro_station_id);
      $result = array('metro_station_id' => $metro_station_id, 'metro_station_name' => $metro_station->name_with_city_name);
      JsonResponse::result($result);
    } else {
      JsonResponse::error(ValidationErrorCodes::WRONG_DATA);
    }
  }


  public function ajaxSaveSeoModerate(){
        if (!RegistryAccessHelper::checkAuth())
            JsonResponse::error(ValidationErrorCodes::NOT_AUTHED);

        $form = (object)$this->request("form");
        $message = "";
        $data = array();
        if(!empty($form->clinic_id)){

            $clinic_manager = new ClinicManager();
            $clinic = $clinic_manager->getOneById($form->clinic_id);

            if( !empty( $form->id )    ){

                $seo = ( new ModerateClinicSeoManager() ) -> getOneById($form->id);

            }else{

                $seo = new ModerateClinicSeoModel();

            }


            $seo->address_to_title = (int)$form->address_to_title;
            $seo->metro_to_description = (int)$form->metro_to_description;
            $seo->address_to_description = (int)$form->address_to_description;
            $seo->metro_to_title = (int)$form->metro_to_title;
            $seo->seo_title = $form->seo_title;

            $seo->seo_descritpion = $form->seo_descritpion;
            $seo->seo_address = $form->seo_address;
            $seo->clinic_id = (int) $form->clinic_id;
            $seo->save();

            $data['title'] = SeoTextViewHelper::GetClinicSeoTitle( $clinic );
            $data['description'] = SeoTextViewHelper::newGetClinicPageDescription( $clinic );
        }else{

            $message = "Не указан clinic_id";

        }

       JsonResponse::result(array('message' => $message, 'data' => $data));
  }
}