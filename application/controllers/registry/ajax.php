<?php

/**
 *  Контроллер содержит методы для обработки AJAX-запросов
 */
class AjaxRegistryController extends BaseController
{
  public function __construct()
  {
    $this->layout = 'ajax';
  }

  /**
   * Метод служит для сохранения всех модерируемых данных в разделе Регистратура
   */
  public function saveModeratedInfo()
  {
    if (!RegistryAccessHelper::checkAuth())
      JsonResponse::error(ValidationErrorCodes::NOT_AUTHED);

    try {
      // id модерируемой сущности
      $entry_id = $this->request->post('entry_id');
      // название сохраняемой модели
      $model_name = $this->request->post('model_name');

      $status = $this->request->post('status');

      $type_form = $this->request->post('type_form', '');

      // определение нового статуса записи
      switch ($status) {
        case 'problem':
          $moderate_status_id = ModerateStatusModel::EDIT;
          break;
        case 'edit':
          $moderate_status_id = ModerateStatusModel::EDIT;
          break;
        case 'moderate':
          $moderate_status_id = ModerateStatusModel::MODERATE;
          break;
        case 'publish':
          $moderate_status_id = ModerateStatusModel::PUBLISHED;
          break;
        case 'sent_back':
          $moderate_status_id = ModerateStatusModel::SENT_BACK;
          break;
        default:
          JsonResponse::error(ValidationErrorCodes::WRONG_DATA);
      }

      // данный блок сохраняет информацию о списках данных (список специальностей
      // врача)
      if (isset($_POST['lists'])) {
        $data = array(
          'clinic_id' => $this->request->post('clinic_id'),
          'doctor_id' => $this->request->post('doctor_id'),
          'specialization_id' => $this->request->post('specialization_id'),
          'specialty_id' => $this->request->post('specialty_id')
        );

        $list_checkbox_parts = array('moderate_clinic_information');

        $entity_id = $data['clinic_id'] ? $data['clinic_id'] : $data['doctor_id'];

        foreach ($_POST['lists'] as $list_name => $list_elements) {
          if ($list_name == 'doctor_specialty_to_clinic') {
            $list_name = 'specialty_to_doctor';
          }

          if (intval($entity_id) && !empty($model_name) && in_array($model_name, $list_checkbox_parts)) {
            $entity_id = intval($entity_id);
            $model_entity_information_manager = ModelManagerFactory::getByName($model_name);
            $entity_information = $model_entity_information_manager->getCurrentRevision($entity_id);

            $view_processor = new FormViewProcessor('moderate_clinic_information', $entity_information);
            $field_information = $view_processor->getFieldType($list_name);
            if (!empty($field_information) && $field_information['type'] == 'list_checkbox') {
              $cross_entity_manager = ModelManagerFactory::getByName($field_information['cross_table_name']);

              $search_params = new SearchParams();
              $search_params->addParam($field_information['result_field_name'], $entity_id);
              $availableRecords = $cross_entity_manager->getListBySearchParams($search_params);

              foreach ($list_elements AS $leKey => $leValue) {
                $matchIsFound = 0;
                if ($leValue > 0) {
                  foreach ($availableRecords AS $arKey => $arValue) {
                    if ($arValue->$field_information['cross_field_name'] == $leKey) {
                      $matchIsFound = 1;
                      $model = $arValue;
                      unset($availableRecords[$arKey]);
                    }
                  }
                  if (!$matchIsFound) $model = $cross_entity_manager->createModel();

                  $model->$field_information['result_field_name'] = $entity_id;
                  $model->$field_information['cross_field_name'] = $leKey;
                  $cross_entity_manager->save($model);
                  unset($model);

                }
              }
              foreach ($availableRecords AS $arKey => $arValue) {
                $arValue->delete();
              }
              unset($field_information);
            }
          }

          /**
           * @var ListModerateModelManager $list_manager
           */
          $list_manager = ModelManagerFactory::getByName('moderate_' . $list_name);
          if ($list_manager) {

            $revision_conditions_fields = $list_manager->getRevisionConditions();
            $revision_condition = array();

            foreach ($revision_conditions_fields as $condition_field_name) {
              $revision_condition[$condition_field_name] = $data[$condition_field_name];
            }

            $list_revision = $list_manager->getCurrentRevision($revision_condition);

            if ($list_revision->elements) {
              foreach ($list_revision->elements as $element) {
                $list_manager->deleteById($element->getId());
              }
            }

            if ($list_elements == 'clear')
              continue;

            if ($list_elements) {
              foreach ($list_elements as $element) {
                $list_model = $list_manager->createModel();
                foreach ($element as $field_name => $field_value) {
                  $list_model->$field_name = ($field_value !== '') ? strip_tags($field_value) : null;
                }

                foreach ($revision_condition as $condition_field_name => $condition_field_value) {
                  $list_model->{$condition_field_name} = $condition_field_value;
                }

                $list_model->revision_number = $list_revision->revision_info->revision_number;
                $list_model->save();
              }
            }

            if ($moderate_status_id != ModerateStatusModel::PUBLISHED) {
              $list_revision->revision_info->moderate_status_id = $moderate_status_id;
            }
            $list_revision->revision_info->save();
          }
        }
      }

      $response = array(
        'role_id' => Acl::userRole()
      );

      // сохранение основной информации
      if ($model_name) {

        $model = null;

        /**
         * @var ModerateModelManager $model_manager
         */
        $model_manager = ModelManagerFactory::getByName($model_name);

        if ($entry_id)
          $model = $model_manager->getCurrentRevision($entry_id);
        else {
          $form = $this->request->getParam('form');
          if ($model_name == 'moderate_doctor_information' && count($form) > 0) {
            /**
             * @var DoctorModel $doctor_model
             * @var DoctorManager $doctor_manager
             */
            $doctor_model = new DoctorModel();
            $doctor_model->first_name = $form['first_name'];
            $doctor_model->second_name = $form['second_name'];
            $doctor_model->last_name = $form['last_name'];
            $doctor_model->is_active = $form['is_active'];
            $doctor_model->to_validate = 1;
            $doctor_model->not_work = !empty($form['not_work']) ? $form['not_work'] : 0;
            $doctor_model->redirect_list = !empty($form['redirect_list']) ? $form['redirect_list'] : 0;

            if (!$doctor_model->validate()) {
              JsonResponse::error(ValidationErrorCodes::EXISTING_DOCTOR_FIO);
            }
          }

          $model = $model_manager->createModel();
          $model->revision_number = 1;
        }

        $notWorkBeforeChange = $model->not_work ? $model->not_work : 0;
//                    if(!empty($_REQUEST['lists']['clinic_type_id']) && count($_REQUEST['lists']['clinic_type_id']) > 0) {
//                        $model->clinic_type_id = $_REQUEST['lists']['clinic_type_id'];
//                    }

        if ($this->request->isPost()) {
          $request_processor = new RequestProcessor($model_name);

          if ($moderate_status_id != ModerateStatusModel::PUBLISHED)
            $model->moderate_status_id = $moderate_status_id;

          $request_processor->processPostData($model);

          $notWork = $model->not_work ? $model->not_work : 0;

          if (get_class($model) == 'ModerateClinicInformationModel' && $notWorkBeforeChange != $notWork) {
            $doctor_manager = ModelManagerFactory::getByName('doctor');
            $doctors = $doctor_manager->getDoctorsByClinicId($model->clinic_id);

            if (count($doctors) > 0) {
              foreach ($doctors AS $dValue) {
                $dValue->not_work = $notWork;
                $dValue->save();
              }
            }
          }
          if ($model->save()) {
            $response['moderate_entity_id'] = $model->doctor_id;
            $response['revision_number'] = $model->revision_number;
          } else {
            JsonResponse::error(ValidationErrorCodes::WRONG_VALUE_FORMAT);
          }
        } else {
          JsonResponse::result(TRUE);
        }
      }

      if ($status == 'problem') {
        if ($entry_id) {
          $clinic_manager = new ClinicManager();
          $clinic = $clinic_manager->getOneById($entry_id);
          $clinic->clinic_status_id = ClinicStatusModel::PROBLEM;
          $clinic->save();
        }
      }

      // публикация данных
      if ($moderate_status_id == ModerateStatusModel::PUBLISHED) {
        if (isset($model_manager)) {
          $name = $model_manager->getModerateEntityName();
        } else {
          $name = $list_manager->getModeratedEntityName();
        }

        if ($name == 'doctor') {
          $clinic_id = RegistryAccessHelper::checkAccessAndDetermineClinicId();
          ModerateDataPublishHelper::publishDoctor($entry_id, $clinic_id);
        } elseif ($name == 'clinic') {
          ModerateDataPublishHelper::publishClinic($entry_id);
        }
      }

      $result = array(
        'role_id' => Acl::userRole()
      );

      JsonResponse::result($response);
    } catch (Exception $e) {
      Test::dump($e);
      JsonResponse::error(ValidationErrorCodes::ERROR);
    }
  }

  // Метод обработки запроса на публикацию клиники
  public function publishClinic()
  {
    $clinic_id = $_POST['clinic_id'];

    if (!RegistryAccessHelper::checkAccessToClinic($clinic_id))
      JsonResponse::error(ValidationErrorCodes::NOT_AUTHED);

    $clinic = ModelManagerFactory::getByName('clinic')->getOneById($clinic_id);
    if ($clinic->is_active == 1) {
      $clinic->is_active = 0;
      $published = FALSE;
    } else {
      $clinic->is_active = 1;
      $published = TRUE;
    }
    if (ModelManagerFactory::getByName('clinic')->save($clinic)) {
      JsonResponse::result(array('published' => $published));
    } else {
      JsonResponse::error(2);
    }
  }

  // Метод обработки запроса на публикацию доктора
  public function publishDoctor()
  {
    $doctor_id = $this->request->post('doctor_id');

    if (!RegistryAccessHelper::checkAccessToDoctor($doctor_id))
      JsonResponse::error(ValidationErrorCodes::NOT_AUTHED);

    $doctor = ModelManagerFactory::getByName('doctor')->getOneById($doctor_id);
    if ($doctor->is_active == 1) {
      $doctor->is_active = 0;
      $published = FALSE;
    } else {
      $doctor->is_active = 1;
      $published = TRUE;
    }
    if (ModelManagerFactory::getByName('doctor')->save($doctor)) {
      JsonResponse::result(array('published' => $published));
    } else {
      JsonResponse::error(2);
    }
  }

  // Метод обработки запроса на поиск врача в администраторской части (Регистратура)
  public function searchDoctor()
  {
    $clinic_id = $this->request('clinic_id');
    if (!RegistryAccessHelper::checkAccessToClinic($clinic_id)) {
      JsonResponse::error(ValidationErrorCodes::NOT_AUTHED);
    }

    $doctor_name = $this->request('doctor_name');
    $specialty_id = $this->request('specialty_id');
    $page = $this->request('page');

    $search_params = new SearchParams();

    if ($specialty_id) {
      $search_params->addJoin('doctor_specialty_to_clinic');
      $specialty_manager = new SpecialtyManager();
      $specialty = $specialty_manager->getOneById($specialty_id);

      $specialty_id_list = array();

      $specialty_id_list[] = $specialty->getId();

      $search_params->addParam('doctor_specialty_to_clinic.specialty_id IN', $specialty_id_list, 'specialty');
      $search_params->addJoin('clinic', 'doctor_specialty_to_clinic.clinic_id', 'clinic.id');
    }

    if ($clinic_id) {
      $search_params->addJoin('doctor_to_clinic');
      $search_params->addParam('doctor_to_clinic.clinic_id', $clinic_id, 'clinic.id');
    }

    if ($doctor_name) {
      $search_params->addParam('full_lower_name LIKE', '%' . str_replace('%', '\%', $doctor_name) . '%', 'name');
    }

    $search_params->setOffsetAndLimit(($page - 1) * 10, 11);
    $search_params->addSortParam('last_name', 'ASC');
    $doctors = ModelManagerFactory::getByName('doctor')->getListBySearchParams($search_params);

    $this->view->doctors = $doctors;
    $this->view->page = $page;
    $this->view->clinic_id = $clinic_id;
    $html = $this->renderInString('/registry/doctor/blocks/doctor_results');

    $result = array(
      'html' => $html
    );

    JsonResponse::result($result);
  }

  /**
   * Метод обработки запроса на удаление врача
   */
  public function deleteDoctor()
  {
    if (!RegistryAccessHelper::checkAuth())
      JsonResponse::error(ValidationErrorCodes::NOT_AUTHED);

    $doctor_id = $_POST['doctor_id'];
    $purpose_text = $_POST['purpose_text'];

    $clinic_id = (isset($_POST['clinic_id'])) ? $_POST['clinic_id'] : ClinicUserHelper::getClinicIdByUserId(Acl::userId());

    /**
     * @var DoctorToClinicManager $doctor_to_clinic_manager
     * @var DoctorToClinicModel $doctor_to_that_clinic
     */

    $doctor_to_clinic_manager = new DoctorToClinicManager();

    if (!$clinic_id) {
      $doctor_to_that_clinic = $doctor_to_clinic_manager->getOneByDoctorId($doctor_id);
      $clinic_id = $doctor_to_that_clinic ? $doctor_to_that_clinic->clinic_id : null;
    }

    $doctor = ModelManagerFactory::getByName('doctor')->getOneById($doctor_id);

    if ($doctor) {
      $deleted_doctor = new DeletedDoctorModel();
      $deleted_doctor->clinic_id = $clinic_id;
      $deleted_doctor->first_name = $doctor->first_name;
      $deleted_doctor->second_name = $doctor->second_name;
      $deleted_doctor->last_name = $doctor->last_name;
      $deleted_doctor->purpose = $purpose_text;
      $deleted_doctor->dt = date('Y-m-d H:i:s');

      if (ModelManagerFactory::getByName('deleted_doctor')->save($deleted_doctor)) {
        ModelManagerFactory::getByName('doctor')->deleteById($doctor_id);
        JsonResponse::result(array('deleted' => TRUE));
      } else {
        JsonResponse::error(2);
      }
    } else {
      JsonResponse::error(3);
    }
  }

  /**
   * Метод обработки запроса на добавление нового сервиса в клинике
   */
  public function addNewFeature()
  {
    if (!RegistryAccessHelper::checkAuth())
      JsonResponse::error(ValidationErrorCodes::NOT_AUTHED);

    $status = $this->request->post('status');
    $entry_id = $this->request->post('entry_id');
    $new_features = $this->request->getParam('new_service');

    if ($this->request->isPost()) {
      if (count($new_features) && $entry_id) {

        foreach ($new_features as $new_feature) {

          $suggested_feature = new SuggestedFeaturesModel();

          $suggested_feature->feature_name = $new_feature;
          $suggested_feature->status_id = ModerateStatusModel::EDIT;
          $suggested_feature->clinic_id = $entry_id;

          if (!$suggested_feature->save())
            JsonResponse::error(ValidationErrorCodes::ERROR);
        }
        JsonResponse::result(TRUE);
      } else {
        JsonResponse::error(ValidationErrorCodes::WRONG_COUNT);
      }
    }
  }

  /**
   * Метод обработки запроса на удаление сервиса в клинике
   */
  public function deleteFeature()
  {
    if (!RegistryAccessHelper::checkAuth())
      JsonResponse::error(ValidationErrorCodes::NOT_AUTHED);

    $feature_name = $this->request->post('feature_name');

    if ($feature_name) {
      $suggasted_features_manager = new SuggestedFeaturesManager();
      $suggasted_features_manager->deleteOneByName($feature_name);
      if (!$suggasted_features_manager->checkExistsByFeatureName($feature_name))
        JsonResponse::result(TRUE);
      else
        JsonResponse::error(ValidationErrorCodes::ERROR);
    } else
      JsonResponse::error(ValidationErrorCodes::WRONG_DATA);
  }

  /**
   * Метод получения выпадающего списка станций метров
   */
  public function getMetroStationsSelectByCityId()
  {
    if (!RegistryAccessHelper::checkAuth())
      JsonResponse::error(ValidationErrorCodes::NOT_AUTHED);

    $city_id = $this->request('city_id');

    /* Вводим дополнительную проверку, т.к. параметры передаваемые с клиента не попадают в $_REQUEST */
    if (empty($city_id)) {
      $city_id = $_GET['city_id'];
    }

    $metro_station_manager = new MetroStationManager();

    $this->view->metro_stations = $metro_station_manager->getListByCityId($city_id);
    $this->view->city_id = $city_id;

    $html = $this->renderInString('registry/ajax/metro_stations_select');

    $data = array(
      'html' => $html
    );
    JsonResponse::result($data);
  }

  /**
   * Метод получения списка целей визита конкретного врача
   */
  public function getPurposeOfVisitsBlockByDoctorId()
  {
    if (!RegistryAccessHelper::checkAuth())
      JsonResponse::error(ValidationErrorCodes::NOT_AUTHED);

    $clinic_id = RegistryAccessHelper::checkAccessAndDetermineClinicId(false);

    $doctor_id = $this->request('doctor_id');

    $specialty_id = $this->request('specialty_id');

    $moderate_purpose_manager = new ModeratePurposeOfVisitToDoctorManager();
    $purposes = $moderate_purpose_manager->getListByDoctorIdAndClinicIdAndSpecialtyId($doctor_id, $clinic_id, $specialty_id);

    $this->view->purposes = $purposes;
    $this->view->clinic_id = $clinic_id;

    $html = $this->renderInString('registry/ajax/doctor-purposes');

    JsonResponse::result(array('html' => $html));
  }

  /**
   * Метод загрузки изображений
   */
  public function uploadImage()
  {
    $file_data = array();

    if (!empty($_FILES)) {
      if (isset($_FILES['Filedata'])) {
        $file_data = $_FILES['Filedata'];
      } elseif (isset($_FILES['file'])) {
        $file_data = $_FILES['file'];
      } else {
        JsonResponse::error(ValidationErrorCodes::ERROR);
      }

      if ($file_data['error'] != 0)
        JsonResponse::error(ValidationErrorCodes::BIG_FILE_SIZE);

      $targetFolder = 'clinic/license/';

      $upload_data = array(
        'upload_folder' => $targetFolder,
      );

      $alias = $this->request('alias');
      if (!is_string($alias)) {
        $alias = null;
      }

      if ($image_id = ImageUploader::upload($upload_data, $file_data, $alias)) {
        $image_manager = new ImageManager();
        $image = $image_manager->getOneById($image_id);

        $response = array(
          'image_id' => $image_id,
          'image_path' => MEDIA_UPLOAD_PATH . $image->folder . $image->filename,
          'resized_image' => array(
            'path' => $image->resize(700, 500)->path,
            'width' => $image->resize(700, 500)->image_info[0],
            'height' => $image->resize(700, 500)->image_info[1],
          ),
          'original_image' => array(
            'path' => $image->path,
            'width' => $image->width,
            'height' => $image->height,
          ),
        );
        JsonResponse::result($response);
      } else {
        JsonResponse::error(ValidationErrorCodes::INVALID_FILE_TYPE);
      }
    }
  }

  /**
   * Метод загрузки файлов
   */
  public function uploadServicesFiles()
  {
    if (!empty($_FILES)) {

      $targetFolder = 'clinic/services/';

      $upload_data = array(
        'upload_folder' => $targetFolder,
      );

      if ($filename = FileUploader::upload($upload_data, $_FILES['Filedata'])) {

        $responce = array(
          'filename' => $filename,
          'filepath' => SITE_URL . MEDIA_UPLOAD_PATH . 'clinic/services/' . $filename
        );
        JsonResponse::result($responce);
      } else {
        JsonResponse::error(ValidationErrorCodes::INVALID_FILE_TYPE);
      }
    }
  }

  /**
   * Метод обрезки изображений
   */
  public function cropImage()
  {
    $image_id = $this->request->post('image_id');
    $x = $this->request->post('x');
    $y = $this->request->post('y');
    $width = $this->request->post('w');
    $height = $this->request->post('h');

    $resize_width = $this->request('width');;
    $resize_height = $this->request('height');;

    $image_manager = new ImageManager();

    $image = $image_manager->getOneById($image_id);

    $simple_image = new SimpleImage();
    $simple_image->load(ABS_ROOT . $image->path);

    $simple_image->cropXY($width, $height, $x, $y);

    $name = time() . '-' . StringGeneratorHelper::generate(10) . '.jpg';

    $simple_image->save(ABS_ROOT . MEDIA_UPLOAD_PATH . $image->folder . $name);

    $new_image = new ImageModel();
    $new_image->folder = $image->folder;
    $new_image->filename = $name;
    $new_image->save();


    JsonResponse::result(array(
      'original_image' => array(
        'image_id' => $new_image->getId(),
        'path' => $new_image->path,
        'width' => $new_image->width,
        'height' => $new_image->height
      ),
      'resized_image' => array(
        'image_id' => $new_image->getId(),
        'path' => $new_image->crop($resize_width, $resize_height)->path,
        'width' => $resize_width,
        'height' => $resize_height
      ),
    ));
  }

  /**
   * Метод для сохранения комментария модератора
   */
  public function saveModerateComment()
  {
    if (!Acl::isAuthed(RoleModel::ACCOUNT_SUPER_MANAGER)
      && !Acl::isAuthed(RoleModel::ACCOUNT_MANAGER)
    )
      JsonResponse::error(ValidationErrorCodes::NOT_AUTHED);

    $entry_id = $this->request->post('entry_id');
    $moderate_comment_type_id = $this->request->post('moderate_comment_type_id');
    $revision_number = $this->request->post('revision_number');
    $comment = strip_tags(trim($this->request->post('comment')));

    $moderate_comment_manager = new ModerateCommentManager();

    $moderate_comment = $moderate_comment_manager->getComment($entry_id, $moderate_comment_type_id, $revision_number);

    if (!$moderate_comment) {
      $moderate_comment = new ModerateCommentModel();
      $moderate_comment->entry_id = $entry_id;
      $moderate_comment->moderate_comment_type_id = $moderate_comment_type_id;
      $moderate_comment->revision_number = $revision_number;
    }

    if ($comment) {
      $moderate_comment->comment = $comment;
      $moderate_comment->save();

      JsonResponse::result($comment);
    } else {
      JsonResponse::error(ValidationErrorCodes::IS_REQUIRED_FIELD);
    }
  }

  /**
   * Метод удаления комментария модератора
   */
  public function deleteModerateComment()
  {
    if (!Acl::isAuthed(RoleModel::ACCOUNT_SUPER_MANAGER)
      && !Acl::isAuthed(RoleModel::ACCOUNT_MANAGER)
    )
      JsonResponse::error(ValidationErrorCodes::NOT_AUTHED);

    $entry_id = $this->request->post('entry_id');
    $moderate_comment_type_id = $this->request->post('moderate_comment_type_id');
    $revision_number = $this->request->post('revision_number');

    $moderate_comment_manager = new ModerateCommentManager();
    $moderate_comment = $moderate_comment_manager->getComment($entry_id, $moderate_comment_type_id, $revision_number);

    if ($moderate_comment) {
      $moderate_comment_manager->delete($moderate_comment);
    }

    JsonResponse::result(true);
  }

  /**
   * Метод служит для добавления клиники
   */
  public function addClinic()
  {
    if (!Acl::isAuthed(RoleModel::ACCOUNT_SUPER_MANAGER)
      && !Acl::isAuthed(RoleModel::ACCOUNT_MANAGER)
    )
      RedirectManager::redirect('/admin');

    $clinic_name = $this->request->post('clinic_name');
    $city_id = $this->request->post('city_id');
    $address = $this->request->post('address');
    $latitude = $this->request->post('latitude');
    $longitude = $this->request->post('longitude');

    $user_name = $this->request->post('user_name');
    $user_phone = $this->request->post('user_phone');
    $user_email = $this->request->post('user_email');

    $flag = true;

    $user = new UserModel();
    $user->login = $user_email;
    $user->fio = $user_name;
    $user->phone = str_replace('-', '', $user_phone);
    $user->email = $user_email;
    $user->role_id = RoleModel::ACCOUNT_REGISTRY;
    $user->disableValidation();

    if (!$user->save()) {
      $flag = false;
    }

    $clinic = new ClinicModel();
    $clinic->name = $clinic_name;
    $clinic->city_id = $city_id;
    $clinic->address = $address;
    $clinic->latitude = $latitude;
    $clinic->longitude = $longitude;
    $clinic->is_region = 0;
    $clinic->representative_user_id = $user->getId();

    if (!$clinic->save()) {
      $flag = false;
    }

    if ($flag) {

      $clinic_to_user = new ClinicToUserModel();
      $clinic_to_user->user_id = $user->getId();
      $clinic_to_user->clinic_id = $clinic->getId();
      $clinic_to_user->save();

      // если аккаунт-менеджер добавил клинику, то она привязывается к нему
      if (Acl::userRole() == RoleModel::ACCOUNT_MANAGER) {
        $clinic_to_user = new ClinicToUserModel();
        $clinic_to_user->clinic_id = $clinic->getId();
        $clinic_to_user->user_id = Acl::userId();
        $clinic_to_user->save();
      }

      JsonResponse::result($clinic->getId());
    } else {
      $validator = $clinic->getValidator();
      $errors = $validator->getErrorMessages();
      JsonResponse::error(ValidationErrorCodes::ERROR, $errors[0]);
    }

  }

  public function getDoctorsOrClinics()
  {
    if (!Acl::isAuthed(RoleModel::ACCOUNT_SUPER_MANAGER)
      && !Acl::isAuthed(RoleModel::ACCOUNT_MANAGER)
    )
      JsonResponse::error(ValidationErrorCodes::NOT_AUTHED);

    $query = $this->request('query');
    $container = $this->request('container');

    $this->layout = 'ajax';
    $doctors = '';
    $clinics = '';

    if ($container == 'doctor') {
      $doctor_manager = new DoctorManager();
      $doctors = $doctor_manager->getListByFullLowerNameWithLimit($query, 6);
      $this->view->doctors = $doctors;
    } else if ($container == 'clinic') {
      $clinic_manager = new ClinicManager();
      $clinics = $clinic_manager->getListByNameWithLimit($query, 6);
      $this->view->clinics = $clinics;
    } else {
      $clinic_manager = new ClinicManager();
      $clinics = $clinic_manager->getRegionsListByNameWithLimit($query, 6);
      $this->view->clinics = $clinics;
    }

    if (!$doctors && !$clinics) {
      JsonResponse::error(2);
    }

    if ($container == 'doctor') {
      $html = $this->renderInString('registry/ajax/doctors');
    } else {
      $html = $this->renderInString('registry/ajax/clinics');
    }

    JsonResponse::result($html);
  }

  public function getClinics()
  {
    if (!in_array(Acl::userRole(), array(RoleModel::ACCOUNT_MANAGER, RoleModel::ACCOUNT_SUPER_MANAGER, RoleModel::FREELANCE_MANAGER))) {
      JsonResponse::error(ValidationErrorCodes::ACCESS_DENIED);
    }

    $query = $this->request('query');
    $registry_user_id = Acl::userId();

    $clinic_search_params = new ClinicSearchParams();
    $clinic_search_params->clinic_name = $query;
    $clinic_search_params->page = 1;
    $clinic_search_params->by_page = 6;
    $clinic_search_params->registry_user_id = $registry_user_id;
    $clinic_search_params->city_id = $this->registry_city;

    $clinic_manager = new ClinicManager();
    $clinics = $clinic_manager->getListByClinicSearchParams($clinic_search_params);

    $this->view->clinics = $clinics;

    $html = $this->renderInString('registry/ajax/clinics');
    JsonResponse::result($html);
  }

  public function getDoctors()
  {
    if (!in_array(Acl::userRole(), array(RoleModel::ACCOUNT_MANAGER, RoleModel::ACCOUNT_SUPER_MANAGER, RoleModel::FREELANCE_MANAGER))) {
      JsonResponse::error(ValidationErrorCodes::ACCESS_DENIED);
    }

    $query = $this->request('query');
    $registry_user_id = Acl::userId();

    $doctor_search_params = new DoctorSearchParams();
    $doctor_search_params->doctor_name = $query;
    $doctor_search_params->page = 1;
    $doctor_search_params->by_page = 6;
    $doctor_search_params->registry_user_id = $registry_user_id;
    $doctor_search_params->is_active = null;
    $doctor_search_params->is_has_active_clinic = false;

    if ($this->registry_city && $this->registry_city != 100000) {
      $doctor_search_params->city_id = $this->registry_city;
    } else if ($this->registry_city && $this->registry_city == 100000) {
      $doctor_search_params->is_has_clinic = false;
    }

    $doctor_manager = new DoctorManager();
    $doctors = $doctor_manager->getListByDoctorSearchParams($doctor_search_params);

    $this->view->doctors = $doctors;

    $html = $this->renderInString('registry/ajax/doctors');
    JsonResponse::result($html);
  }

  /**
   * Метод поиска врачей (используется только для менеджеров)
   */
  public function searchDoctorByManager()
  {
    if (!RegistryAccessHelper::checkAuth())
      JsonResponse::error(ValidationErrorCodes::NOT_AUTHED);

    $query = $_POST['query'];
    $page = $_POST['page'];
    $by_page = 10;
    $get_extra_entry = 1;

    $doctor_manager = new DoctorManager();

    $doctor_search_params = new DoctorSearchParams();
    $doctor_search_params->page = $page;
    $doctor_search_params->by_page = $by_page;
    $doctor_search_params->not_virtual = 1;
    $doctor_search_params->doctor_name = $query;
    $doctor_search_params->is_has_active_clinic = false;

    if ($this->registry_city && $this->registry_city != 100000) {
      $doctor_search_params->city_id = $this->registry_city;
    } else if ($this->registry_city && $this->registry_city == 100000) {
      $doctor_search_params->is_has_clinic = false;
    }

    $doctors = $doctor_manager->getListByDoctorSearchParams($doctor_search_params);

    $this->view->doctors = $doctors;
    $this->view->page = $page;
    $html = $this->renderInString('/registry/manage/blocks/doctor_results');

    $result = array(
      'html' => $html
    );

    JsonResponse::result($result);
  }

  /**
   * Метод поиска клиник (в регистратуре)
   */
  public function searchClinicByManager()
  {
    if (!RegistryAccessHelper::checkAuth())
      JsonResponse::error(ValidationErrorCodes::NOT_AUTHED);

    $query = $_POST['query'];
    $page = $_POST['page'];
    $by_page = 10;
    $get_extra_entry = 1;

    $clinic_manager = new ClinicManager();
    $clinic_search_params = new ClinicSearchParams();
    $clinic_search_params->page = $page;
    $clinic_search_params->by_page = $by_page;
    $clinic_search_params->clinic_name = $query;
    $clinic_search_params->calc_found_rows = true;
    $clinic_search_params->city_id = $this->registry_city;

    $clinics = $clinic_manager->getListByClinicSearchParams($clinic_search_params);
    //$clinics = $clinic_manager->getListByNameWithPaging($query, $by_page, $page, $get_extra_entry);

    $this->view->clinics = $clinics;
    $this->view->page = $page;
    $html = $this->renderInString('/registry/manage/blocks/clinic_results');

    $result = array(
      'html' => $html
    );

    JsonResponse::result($result);
  }

  /**
   * Метод обновления изображения на карточке врача
   */
  public function updateDoctorCardPhoto()
  {
    if (!RegistryAccessHelper::checkAuth())
      JsonResponse::error(ValidationErrorCodes::NOT_AUTHED);

    $doctor_id = $this->request('doctor_id');
    $image_id = $this->request('image_id');

    $doctor_manager = new DoctorManager();

    if ($doctor = $doctor_manager->getOneById($doctor_id)) {
      $doctor->card_image_id = $image_id;
      if ($doctor->save())
        JsonResponse::result(true);
      else
        JsonResponse::error(ValidationErrorCodes::ERROR);
    }
  }

  /**
   * Метод получения попапа для задания времени работы
   */
  public function getTimingPopup()
  {
    if (!Acl::isAuthed(RoleModel::ACCOUNT_REGISTRY) && !Acl::isAuthed(RoleModel::ACCOUNT_SUPER_MANAGER))
      JsonResponse::error(ValidationErrorCodes::NOT_AUTHED);

    $html = $this->renderInString('/registry/doctor/blocks/timing_popup');

    $result = array(
      'html' => $html
    );

    JsonResponse::result($result);
  }

  /**
   * Метод получения попапа выбора диапазона дней
   */
  public function getDayRangeSchedulePopup()
  {
    if (!RegistryAccessHelper::checkAuth())
      JsonResponse::error(ValidationErrorCodes::NOT_AUTHED);

    $html = $this->renderInString('/registry/doctor/blocks/day_range_popup');

    $result = array(
      'html' => $html
    );

    JsonResponse::result($result);
  }

  /**
   * Метод получения попапа для редактирования времени приема врача
   */
  public function getTimingEditPopup()
  {
    if (!RegistryAccessHelper::checkAuth())
      JsonResponse::error(ValidationErrorCodes::NOT_AUTHED);

    $html = $this->renderInString('/registry/doctor/blocks/timing_edit_popup');

    $result = array(
      'html' => $html
    );

    JsonResponse::result($result);
  }

  /**
   * Метод получения попапа для добавления образования врача
   */
  public function getEducationAddPopup()
  {
    if (!RegistryAccessHelper::checkAuth())
      JsonResponse::error(ValidationErrorCodes::NOT_AUTHED);

    $html = $this->renderInString('/registry/doctor/blocks/education_add_popup');

    $result = array(
      'html' => $html
    );

    JsonResponse::result($result);
  }

  /**
   * Метод для добавления нового учебного заведения для врача
   */
  public function addNewInstitution()
  {
    if (!RegistryAccessHelper::checkAuth())
      JsonResponse::error(ValidationErrorCodes::NOT_AUTHED);

    $name = $this->request('name');
    $type = $this->request('type');

    $university_model = new UniversityModel();
    $university_model->name = $name;
    $university_model->type = $type;
    $university_model->save();

    $university_manager = new UniversityManager();
    $universities = $university_manager->getListByType($type);

    $this->view->universities = $universities;
    $this->view->type = $type;

    $html = $this->renderInString('/registry/doctor/blocks/universities');

    $result = array(
      'html' => $html
    );

    JsonResponse::result($result);
  }

  /**
   * Метод для получения информации о враче по его идентификатору
   */
  public function getDoctorById()
  {
    $doctor_id = $this->request('doctor_id', 0);

    $doctor_manager = new DoctorManager();
    $doctor = $doctor_manager->getOneById($doctor_id);

    $result = array();

    if ($doctor) {
      $result[] = array(
        'last_name' => $doctor->last_name,
        'first_name' => $doctor->first_name,
        'second_name' => $doctor->second_name,
        'sex_id' => $doctor->sex_id,
        'is_adult' => $doctor->is_adult,
        'is_pregnant' => $doctor->is_pregnant,
        'is_children' => $doctor->is_children,
        'is_leave_the_house' => $doctor->is_leave_the_house,
        'about' => $doctor->about,
        'rate' => $doctor->rate,
      );
    }

    JsonResponse::result($result);
  }

  public function getRegionListByFreelancerId()
  {
    if (!RegistryAccessHelper::checkAuth())
      JsonResponse::error(ValidationErrorCodes::NOT_AUTHED);

    $registry_user_id = $this->request->post('registry_user_id');
    $query = $this->request->post('query');
    $status = $this->request->post('status');
    $city_id = $this->request->post('city_id', null);

    $page = 1;
    $by_page = 10;

    if (!$registry_user_id && !in_array(Acl::userRole(), array(RoleModel::ACCOUNT_SUPER_MANAGER, RoleModel::ACCOUNT_MANAGER)))
      $registry_user_id = Acl::userRole();

    $clinic_manager = new ClinicManager();

    $this->view->page = $page;
    $this->view->by_page = $by_page;

    $this->view->current_page = $page;
    $this->view->page_url = $this->request->post('page_url');

    $clinic_search_params = new ClinicSearchParams();
    $clinic_search_params->clinic_name = $query;
    $clinic_search_params->registry_user_id = $registry_user_id;
    $clinic_search_params->is_region = 1;
    $clinic_search_params->city_id = $city_id;

    $cached_data = RegionCountersCache::getRegionsCountersCache(Acl::userId(), $registry_user_id, $city_id);

    $clinic_search_params->status = $status;
    $total_count = count($clinic_manager->getListByClinicSearchParams($clinic_search_params));

    $clinic_search_params->page = $page;
    $clinic_search_params->by_page = $by_page;

    $clinics = $clinic_manager->getListByClinicSearchParams($clinic_search_params);

    $this->view->total_count = $total_count;
    $this->view->query = $query;
    $this->view->clinics = $clinics;
    $this->view->city_id = $city_id;

    $this->view->pages_total = ($total_count % $by_page) ? (int)($total_count / $by_page) + 1 : $total_count / $by_page;

    $html = $this->renderInString('/registry/manage/blocks/region_results');

    $result = array(
      'html' => $html,
      'cache' => $cached_data
    );

    JsonResponse::result($result);
  }

  public function getDoctorListByName()
  {
    if (!in_array(Acl::userRole(), array(RoleModel::ACCOUNT_MANAGER, RoleModel::ACCOUNT_SUPER_MANAGER, RoleModel::FREELANCE_MANAGER))) {
      JsonResponse::error(ValidationErrorCodes::ACCESS_DENIED);
    }

    $query = $this->request('query');

    if ($query) {
      $doctor_search_params = new DoctorSearchParams();
      $doctor_search_params->doctor_name = $query;
      $doctor_search_params->page = 1;
      $doctor_search_params->by_page = 50;
      $doctor_search_params->not_virtual = 1;
      $doctor_search_params->is_active = null;
      $doctor_search_params->is_has_clinic = false;

      $doctor_manager = new DoctorManager();
      $doctors = $doctor_manager->getListByDoctorSearchParams($doctor_search_params);

      $this->view->doctors = $doctors;
      $html = $this->renderInString('/registry/ajax/doctor_list');

      JsonResponse::result(array('html' => $html));
    } else {
      JsonResponse::error(ValidationErrorCodes::WRONG_DATA);
    }
  }

  public function getClinicPostIndexByCityAndAddress()
  {
    if (!RegistryAccessHelper::checkAuth())
      JsonResponse::error(ValidationErrorCodes::NOT_AUTHED);

    $city = $this->request('city');
    $address = $this->request('address');

    if ($city && $address) {
      $kladr_api = new KladrApi();
      $html = $kladr_api->getPostCodeByCityAndAddress($city, $address);

      if ($html['status'] == 0) {
        JsonResponse::result(array('html' => $html['result']));
      } else {
        JsonResponse::error(ValidationErrorCodes::WRONG_DATA, $html['error']);
      }

      JsonResponse::result(array('html' => $html));
    } else {
      JsonResponse::error(ValidationErrorCodes::WRONG_DATA, 'Проверьте заполнение полей Город и Адрес');
    }
  }

  public function deleteDoctorToClinic()
  {
    if (!RegistryAccessHelper::checkAuth())
      JsonResponse::error(ValidationErrorCodes::NOT_AUTHED);

    $doctor_id = $this->request->post('doctor_id');
    $clinic_id = $this->request->post('clinic_id');

    if ($doctor_id && $clinic_id) {

      /**
       * @var DoctorToClinicModel $doctor_to_clinic
       * @var DoctorToClinicManager $doctor_to_clinic_manager
       */
      $doctor_to_clinic_manager = ModelManagerFactory::getByName('doctor_to_clinic');
      $doctor_to_clinic = $doctor_to_clinic_manager->getOneByClinicIdAndDoctorId($clinic_id, $doctor_id);
      if ($doctor_to_clinic) {
        $doctor_to_clinic_manager->delete($doctor_to_clinic);
      }
      JsonResponse::result(array('html' => 1));
    } else {
      JsonResponse::error(ValidationErrorCodes::WRONG_DATA);
    }
  }

  public function restoreDoctorToClinic()
  {
    if (!RegistryAccessHelper::checkAuth())
      JsonResponse::error(ValidationErrorCodes::NOT_AUTHED);

    $doctor_id = $this->request->post('doctor_id');
    $clinic_id = $this->request->post('clinic_id');
    $specialty_id = $this->request->post('specialty_id');
    $first_visit_price = $this->request->post('first_visit_price');
    $second_visit_price = $this->request->post('second_visit_price');

    if ($doctor_id && $clinic_id) {

      /**
       * @var DoctorToClinicModel $doctor_to_clinic
       * @var DoctorSpecialtyToClinicModel[] $doctor_specialties_to_clinic
       * @var PurposeOfVisitToDoctorModel[] $purposes_of_visit_to_doctor
       * @var DoctorSpecialtyToClinicManager $doctor_specialty_to_clinic_manager
       * @var PurposeOfVisitToDoctorManager $purpose_of_visit_to_doctor_manager
       */
      $doctor_to_clinic = new DoctorToClinicModel();

      $doctor_to_clinic->clinic_id = $clinic_id;
      $doctor_to_clinic->doctor_id = $doctor_id;
      $doctor_to_clinic->specialty_id = $specialty_id;
      $doctor_to_clinic->first_visit_price = $first_visit_price;
      $doctor_to_clinic->second_visit_price = $second_visit_price;

      if ($doctor_to_clinic->save()) {
        $doctor_specialty_to_clinic_manager = ModelManagerFactory::getByName('doctor_specialty_to_clinic');
        $doctor_specialties_to_clinic = $doctor_specialty_to_clinic_manager->getListByClinicIdAndDoctorId($clinic_id, $doctor_id);
        if ($doctor_specialties_to_clinic) {
          foreach ($doctor_specialties_to_clinic as $doctor_specialty_to_clinic) {
            $doctor_specialty_to_clinic->is_to_delete = null;
            $doctor_specialty_to_clinic->save();
          }
        }

        $purpose_of_visit_to_doctor_manager = ModelManagerFactory::getByName('purpose_of_visit_to_doctor');
        $purposes_of_visit_to_doctor = $purpose_of_visit_to_doctor_manager->getListByDoctorIdAndClinicId($doctor_id, $clinic_id);
        if ($purposes_of_visit_to_doctor) {
          foreach ($purposes_of_visit_to_doctor as $purpose_of_visit_to_doctor) {
            $purpose_of_visit_to_doctor->is_to_delete = null;
            $purpose_of_visit_to_doctor->save();
          }
        }
      }

      JsonResponse::result(array('html' => 1));
    } else {
      JsonResponse::error(ValidationErrorCodes::WRONG_DATA);
    }
  }

  public function getExistingDoctors()
  {
    if (!RegistryAccessHelper::checkAuth())
      JsonResponse::error(ValidationErrorCodes::NOT_AUTHED);

    $last_name = $this->request('last_name');
    $first_name = $this->request('first_name');
    $second_name = $this->request('second_name');
    $to_clinic_id = $this->request('clinic_id');

    /**
     * @var DoctorModel[] $doctors
     * @var DoctorManager $doctor_manager
     */

    $clinic_id = RegistryAccessHelper::checkAccessAndDetermineClinicId();

    $full_lower_name = mb_strtolower($first_name . ' ' . $second_name . ' ' . $last_name, 'utf-8');
    $full_name = $first_name . ' ' . $second_name . ' ' . $last_name;

    $doctor_search_params = new DoctorSearchParams();
    $doctor_search_params->doctor_name = $full_lower_name;
    $doctor_search_params->is_has_clinic = null;
    $doctor_search_params->is_has_active_clinic = null;
    $doctor_search_params->is_active = null;

    $doctor_manager = ModelManagerFactory::getByName('doctor');
    $doctors = $doctor_manager->getListByDoctorSearchParams($doctor_search_params);

    if ($doctors) {
      $this->view->doctors = $doctors;
      $this->view->full_name = $full_name;
      $this->view->clinic_id = $clinic_id;
      $this->view->to_clinic_id = $to_clinic_id;

      $html = $this->renderInString('/registry/doctor/blocks/existing_doctors');

      JsonResponse::result(array('html' => $html));
    } else {
      JsonResponse::error(ValidationErrorCodes::NO_RESULT);
    }
  }

  public function addDoctorToClinic()
  {
    if (!RegistryAccessHelper::checkAuth())
      JsonResponse::error(ValidationErrorCodes::NOT_AUTHED);

    $clinic_id = $this->request->post('clinic_id');
    $doctor_id = $this->request->post('doctor_id');

    /**
     * @var DoctorToClinicModel $doctor_to_clinic
     * @var DoctorToClinicManager $doctor_to_clinic_manager
     */

    if ($clinic_id && $doctor_id) {
      $doctor_to_clinic_manager = ModelManagerFactory::getByName('doctor_to_clinic');
      $doctor_to_clinic = $doctor_to_clinic_manager->getOneByClinicIdAndDoctorId($clinic_id, $doctor_id);

      if (!$doctor_to_clinic) {
        $doctor_to_clinic = new DoctorToClinicModel();
        $doctor_to_clinic->clinic_id = $clinic_id;
        $doctor_to_clinic->doctor_id = $doctor_id;

        if ($doctor_to_clinic->save()) {
          JsonResponse::result(TRUE);
        } else {
          JsonResponse::error(ValidationErrorCodes::NOT_SAVED);
        }
      } else {
        JsonResponse::result(TRUE);
      }
    } else {
      JsonResponse::error(ValidationErrorCodes::WRONG_DATA);
    }
  }

  public function getDoctorAndClinicsListByCityId()
  {
    if (!RegistryAccessHelper::checkAuth())
      JsonResponse::error(ValidationErrorCodes::NOT_AUTHED);

    $city_id = $this->request->post('city_id');
    setcookie('city_id', $city_id, time() + 60 * 60 * 24 * 365, '/');
    /**
     * @var DoctorManager $doctor_manager
     * @var ClinicManager $clinic_manager
     */

    $clinic_manager = ModelManagerFactory::getByName('clinic');
    $doctor_manager = ModelManagerFactory::getByName('doctor');

    $doctors = $doctor_manager->getUnboundedListWithPagingByRegistryUserId(Acl::userId(), $city_id);
    $this->view->instances = $doctors;
    $this->view->instance_name = 'doctor';

    $doctors_list = $this->renderInString('/registry/manage/blocks/clinic_or_doctor_instance_list');

    $clinic_search_params = new ClinicSearchParams();
    $clinic_search_params->registry_user_id = Acl::userId();
    $clinic_search_params->city_id = $city_id;
    $clinic_search_params->page = 1;
    $clinic_search_params->by_page = 5;
    $clinics = $clinic_manager->getListByClinicSearchParams($clinic_search_params);

    $this->view->instances = $clinics;
    $this->view->instance_name = 'clinic';
    $clinics_list = $this->renderInString('/registry/manage/blocks/clinic_or_doctor_instance_list');

    JsonResponse::result(array('doctors' => $doctors_list, 'clinics' => $clinics_list));
  }

  public function getDoctorsListByCityId()
  {
    if (!RegistryAccessHelper::checkAuth())
      JsonResponse::error(ValidationErrorCodes::NOT_AUTHED);

    $city_id = $this->request->post('city_id');
    setcookie('city_id', $city_id, time() + 60 * 60 * 24 * 365, '/');

    /**
     * @var DoctorManager $doctor_manager
     */

    $doctor_manager = ModelManagerFactory::getByName('doctor');

    $page = 1;
    $by_page = 10;

    $this->view->page = $page;
    $this->view->query = '';

    $this->view->current_page = $page;
    $this->view->page_url = '/registry/manage/doctors';

    Environment::set('get_total_count', true);

    $doctor_search_params = new DoctorSearchParams();
    $doctor_search_params->registry_user_id = Acl::userId();
    $doctor_search_params->page = $page;
    $doctor_search_params->by_page = $by_page;
    $doctor_search_params->not_virtual = 1;
    $doctor_search_params->is_active = null;
    $doctor_search_params->is_has_active_clinic = false;

    if ($city_id && $city_id != 100000) {
      $doctor_search_params->city_id = $city_id;
    } else if ($city_id && $city_id == 100000) {
      $doctor_search_params->is_has_clinic = false;
    }

    $doctors = $doctor_manager->getListByDoctorSearchParams($doctor_search_params);
    $total_count = $doctor_manager->getTotalHits();

    $this->view->doctors = $doctors;
    $this->view->pages_total = ($total_count % $by_page) ? (int)($total_count / $by_page) + 1 : $total_count / $by_page;

    $doctors_list = $this->renderInString('/registry/manage/blocks/doctor_list_results');

    JsonResponse::result(array('doctors' => $doctors_list));
  }

  public function getClinicsListByCityId()
  {
    if (!RegistryAccessHelper::checkAuth())
      JsonResponse::error(ValidationErrorCodes::NOT_AUTHED);

    $city_id = $this->request->post('city_id');
    setcookie('city_id', $city_id, time() + 60 * 60 * 24 * 365, '/');

    /**
     * @var ClinicManager $clinic_manager
     */
    $clinic_manager = ModelManagerFactory::getByName('clinic');

    $by_page = 10;
    $page = 1;

    $this->view->query = '';
    $this->view->page = $page;

    $this->view->current_page = $page;
    $this->view->page_url = '/registry/manage/clinics';

    Environment::set('get_total_count', true);

    $clinic_search_params = new ClinicSearchParams();
    $clinic_search_params->page = $page;
    $clinic_search_params->by_page = $by_page;
    $clinic_search_params->registry_user_id = Acl::userId();
    $clinic_search_params->city_id = $city_id;

    $clinics = $clinic_manager->getListByClinicSearchParams($clinic_search_params);
    $this->view->clinics = $clinics;
    $total_count = $clinic_manager->getTotalHits();
    $this->view->pages_total = ($total_count % $by_page) ? (int)($total_count / $by_page) + 1 : $total_count / $by_page;

    $clinics_list = $this->renderInString('/registry/manage/blocks/clinic_list_results');

    JsonResponse::result(array('clinics' => $clinics_list));
  }
}
