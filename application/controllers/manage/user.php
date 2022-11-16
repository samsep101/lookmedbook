<?php

class UserManageController extends BaseController
{
  public function __construct()
  {
    $this->layout = 'registry';
    parent::__construct();
  }

  public function index()
  {
    if (!Acl::isAuthed(RoleModel::ACCOUNT_SUPER_MANAGER) && !Acl::isAuthed(RoleModel::ACCOUNT_MANAGER))
      RedirectManager::redirect(ADMIN_FOLDER);

    $user_search_params = new UserSearchParams();
    $role_id = $this->request('role_id');
    $user_search_params->role_id = $role_id;

    $clinic_id = $this->request('clinic_id');
    $user_search_params->clinic_id = $clinic_id;

    $user_search_params->registry_user_id = Acl::userId();

    $login = $this->request('login');
    $user_search_params->login = $login;

    $city_id = $this->request->get('city_id');
    $this->view->city_id = $city_id;
    $user_search_params->city_id = $city_id;

    $user_manager = new UserManager();
    $users = $user_manager->getListByUserSearchParams($user_search_params);

    $this->view->login = $login;
    $this->view->role_id = $role_id;

    $user_manager = new UserManager();
    $user = $user_manager->getOneByid($user_search_params->registry_user_id);
    if ($user->role_id == RoleModel::ACCOUNT_MANAGER) {
      $users = array_merge(array('0' => $user), $users);
    }

    $this->view->clinic_id = $clinic_id;
    $clinic_manager = new ClinicManager();
    $this->view->clinics = $clinic_manager->getListByManagerUserId(Acl::userId());
    $this->view->users = $users;

    /**
     * @var CityManager $city_manager
     * @var CityModel[] $cities
     */
    $city_manager = ModelManagerFactory::getByName('city');

    $cities = $city_manager->getListWithUsersByManagerUserId(Acl::userId());
    $this->view->cities = $cities;
  }

  public function create()
  {
    if (!Acl::isAuthed(RoleModel::ACCOUNT_SUPER_MANAGER) && !Acl::isAuthed(RoleModel::ACCOUNT_MANAGER))
      RedirectManager::redirect(ADMIN_FOLDER);

    $clinic_manager = new ClinicManager();
    $clinics = $clinic_manager->getListByManagerUserId(Acl::userId());

    $this->view->clinics = $clinics;
  }

  public function ajaxCreateAccount()
  {
    if (!in_array(Acl::userRole(), array(RoleModel::ACCOUNT_SUPER_MANAGER, RoleModel::ACCOUNT_MANAGER))) {
      JsonResponse::error(ValidationErrorCodes::NOT_AUTHED);
    }

    $login = $this->request->post('login');
    $password = $this->request->post('password');
    $role_id = $this->request->post('role_id');

    $clinic_id = $this->request->post('clinic_id');

    if (!$clinic_id) {
      JsonResponse::error(ValidationErrorCodes::WRONG_DATA, 'Клиника обязательно должна быть указана');
    }

    $user = new UserModel();
    $user->login = $login;
    $user->password = sha1($password);
    $user->role_id = $role_id;

    if (!$user->save()) {
      JsonResponse::error(ValidationErrorCodes::WRONG_DATA, $user->getValidator()->getErrorMessages());
    }

    $clinic_to_user = new ClinicToUserModel();
    $clinic_to_user->clinic_id = $clinic_id;
    $clinic_to_user->user_id = $user->getId();
    $clinic_to_user->save();

    JsonResponse::result(true);
  }

  public function edit()
  {
    if (!RegistryAccessHelper::checkAuth())
      JsonResponse::error(ValidationErrorCodes::NOT_AUTHED);

    $user_id = $this->request('user_id');

    $additional_access = RegistryAccessHelper::getAdditionalAccessToAccountManager($user_id);
    if (!RegistryAccessHelper::checkAccessToUser($user_id, $additional_access)) {
      JsonResponse::error(ValidationErrorCodes::ACCESS_DENIED);
    }

    $my_account = ($user_id == Acl::userId()) ? 1 : 0;
    $this->view->my_account = $my_account;

    $user_manager = new UserManager();
    $user = $user_manager->getOneById($user_id);

    $this->view->user = $user;

    $clinic_manager = new ClinicManager();
    $this->view->clinics = $clinic_manager->getListByManagerUserId(Acl::userId(), $additional_access);

    $by_page = 15;
    $page = $this->request('page', 1);

    $this->view->page = $page;

    $this->view->current_page = $page;
    $this->view->page_url = '/manage/user/edit';

    $city_id = $this->request->get('city_id', null);
    $this->view->city_id = $city_id;

    Environment::set('get_total_count', true);

    $clinic_search_params = new ClinicSearchParams();
    $clinic_search_params->page = $page;
    $clinic_search_params->by_page = $by_page;
    $clinic_search_params->registry_user_id = $user->getId();
    $clinic_search_params->city_id = $city_id;

    $user_clinics = $clinic_manager->getListByClinicSearchParams($clinic_search_params);
    $this->view->user_clinics = $user_clinics;
    $total_count = $clinic_manager->getTotalHits();

    $this->view->pages_total = ($total_count % $by_page) ? (int)($total_count / $by_page) + 1 : $total_count / $by_page;

    /**
     * @var CityManager $city_manager
     * @var CityModel[] $cities
     */
    $city_manager = ModelManagerFactory::getByName('city');

    $cities = $city_manager->getListToEditByUserId($user_id);
    $this->view->cities = $cities;
  }

  public function ajaxEditAccount()
  {
    if (!RegistryAccessHelper::checkAuth())
      JsonResponse::error(ValidationErrorCodes::NOT_AUTHED);

    $user_id = $this->request->post('user_id');
    $additional_access = RegistryAccessHelper::getAdditionalAccessToAccountManager($user_id);
    if (!RegistryAccessHelper::checkAccessToUser($user_id, $additional_access)) {
      JsonResponse::error(ValidationErrorCodes::ACCESS_DENIED);
    }
    $user_manager = new UserManager();
    $user = $user_manager->getOneById($user_id);

    $login = $this->request->post('login');
    $user->login = $login;

    $password = $this->request->post('password');
    if ($password)
      $user->password = sha1($password);

    $role_id = $this->request->post('role_id');
    $user->role_id = $role_id;

    if ($user->save()) {
      JsonResponse::result();
    } else {
      JsonResponse::error(ValidationErrorCodes::WRONG_DATA, $user->getValidator()->getErrorMessages());
    }
  }

  public function ajaxDeleteClinic()
  {
    if (!RegistryAccessHelper::checkAuth())
      JsonResponse::error(ValidationErrorCodes::NOT_AUTHED);

    $user_id = $this->request->post('user_id');
    $clinic_id = $this->request->post('clinic_id');

    $additional_access = RegistryAccessHelper::getAdditionalAccessToAccountManager($user_id);
    if (!RegistryAccessHelper::checkAccessToClinic($clinic_id, $additional_access) || !RegistryAccessHelper::checkAccessToUser($user_id, $additional_access)) {
      JsonResponse::error(ValidationErrorCodes::ACCESS_DENIED);
    }

    $clinic_to_user_manager = new ClinicToUserManager();
    $clinic_to_user_manager->deleteByUserIdAndClinicId($user_id, $clinic_id);

    JsonResponse::result();
  }

  public function ajaxAddClinic()
  {
    if (!RegistryAccessHelper::checkAuth())
      JsonResponse::error(ValidationErrorCodes::NOT_AUTHED);

    $user_id = $this->request->post('user_id');
    $clinic_id = $this->request->post('clinic_id');

    $additional_access = RegistryAccessHelper::getAdditionalAccessToAccountManager($user_id);
    if (!RegistryAccessHelper::checkAccessToClinic($clinic_id, $additional_access) || !RegistryAccessHelper::checkAccessToUser($user_id, $additional_access)) {
      JsonResponse::error(ValidationErrorCodes::ACCESS_DENIED);
    }

    $clinic_to_user_model = new ClinicToUserModel();
    $clinic_to_user_model->clinic_id = $clinic_id;
    $clinic_to_user_model->user_id = $user_id;
    $clinic_to_user_model->save();
    JsonResponse::result();
  }

  public function ajaxGetClinicsByUserId()
  {
    if (!RegistryAccessHelper::checkAuth())
      JsonResponse::error(ValidationErrorCodes::NOT_AUTHED);

    $user_id = $this->request('user_id');
    $page = $this->request('page');

    $clinic_manager = ModelManagerFactory::getByName('clinic');

    $clinic_search_params = new ClinicSearchParams();
    $clinic_search_params->is_region = null;
    $clinic_search_params->registry_user_id = $user_id;

    $clinics = $clinic_manager->getListByClinicSearchParams($clinic_search_params);

    $this->view->clinics = $clinics;
    $this->view->page = $page;

    $html = $this->renderInString('/registry/manage/blocks/clinic_results');

    $result = array(
      'html' => $html
    );

    JsonResponse::result($result);
  }
}