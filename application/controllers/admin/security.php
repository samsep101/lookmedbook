<?php

class SecurityAdminController extends Controller
{

  public $layout = 'admin';

  //авторизация пользователя
  function login()
  {
    if ($this->request->isPost()) {
      $login = $this->request('login');
      $password = $this->request('password');

      $user_manager = new UserManager();

      $user = $user_manager->getOneByLoginAndPassword($login, sha1($password));

      if ($user) {
        $grant_manager = new GrantManager();
        $user_grants = $grant_manager->getListByRoleId($user->role_id);
        Acl::saveAuthData($user, $user_grants);

        $admin_role = array(
          RoleModel::ACCOUNT_ADMIN,
          RoleModel::CALL_CENTRE_OPERATOR,
          RoleModel::ESHOP_MANAGER,
          RoleModel::ESHOP_CONTENT_MANAGER
        );

        if (in_array(Acl::userRole(), $admin_role))
          $this->redirectUrl(ADMIN_FOLDER);
        elseif (Acl::isAuthed(RoleModel::ACCOUNT_SUPER_MANAGER)
          || Acl::isAuthed(RoleModel::ACCOUNT_MANAGER)
          || Acl::isAuthed(RoleModel::FREELANCE_MANAGER)
        )
          $this->redirectUrl(MANAGE_FOLDER);
        elseif (Acl::isAuthed(RoleModel::ACCOUNT_REGISTRY))
          $this->redirectUrl(REGISTRY_FOLDER);
      }

      $this->view->msg = 'Неверные эл. почта или пароль.';
      $this->view->login = $login;
      $this->view->pass = $password;
    }

    AdminAuthHelper::redirectToDefaultPage();

  }

  //
  function hashKeylogin()
  {
    Acl::logout();
    $onlyWorkAccess = (int)SettingsManager::get('work_access');
    if ($onlyWorkAccess) {
      $hashKey = $this->request('hashKey', '');
      if ($hashKey) {
        $_SESSION['hashKey'] = $hashKey;
      }
    }
    $this->redirect('security', 'login');
  }

  //завершение работы пользователем
  function logout()
  {
    setcookie('city_id', 0, time() + 60 * 60 * 24 * 365, '/');

    if (Acl::userLogin()) {
      $logger = new Logger(array(), 'user_logout', Acl::userLogin(), array());
      $logger->send();
    }
    Acl::logout();

    $this->redirectUrl(ADMIN_FOLDER . '/security/login');
  }

  //при отказе в доступе
  function denied()
  {
    $this->view->setLayout('simple');
    $this->render('admin/security/denied');
  }

  //
  public function error404()
  {
    $this->view->setLayout('simple');
    $this->render('error404');
  }
}