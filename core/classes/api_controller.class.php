<?php

class ApiController extends Controller implements ICachedController
{
  protected $access_validator;
  protected $response;
  protected $secret_key;
  public $account_id;
  protected $if_none_match = '';

  protected $e_tag;

  protected $authorized_account;

  protected $session;

  public function __construct()
  {
    $this->secret_key = SettingsManager::get('mobile_secret_key');
    $this->session = new ApiSession();

    if (!isset($_REQUEST['token'])) {
      ApiHeader::error(ApiRequestErrors::INCORRECT_TOKEN);
    }

    if (md5($this->secret_key) != $_REQUEST['token']) {
      ApiHeader::error(ApiRequestErrors::INCORRECT_TOKEN);
    }

    $session = isset($_REQUEST['session']) ? $_REQUEST['session'] : false;

    if ($session) {
      /**
       * @var AccountManager $account_manager
       */
      $account_manager = ModelManagerFactory::getByName('account');
      $this->authorized_account = $account_manager->getOneByApiSessionHash($session);
    }

    ApiLogger::log();
    parent::__construct();
  }

  public function getCachedMethods()
  {
    return array();
  }

  public function setETag($e_tag)
  {
    $this->e_tag = $e_tag;
  }

  public function checkCookieToken()
  {
    if (isset($_COOKIE['token'])) {
      $account = ModelManagerFactory::getByName('account')->getOneByToken($_COOKIE['token']);
      if (!count($account)) {
        ApiHeader::error(401, '');
        exit;
      } else {
        $this->account_id = $account->id;
      }
    } else {
      ApiHeader::error(401, '');
      exit;
    }
  }

  public function setAccessValidator(AccessValidator $access_validator)
  {
    $this->access_validator = $access_validator;
  }

  public function setResponse(ApiResponse $response)
  {
    $this->response = $response;
  }

  protected function isModifyData($etag)
  {
    if (($etag != $this->if_none_match))
      return true;
  }

  // установка сессии аккаунта
  public static function setAccountSession($account_id, $session_hash)
  {
    $account_session = new AccountSessionModel();
    $account_session->account_id = $account_id;
    $account_session->session_hash = $session_hash;
    $account_session->dt_start = date('Y-m-d H:i:s');
    $account_session->dt_end = date("Y-m-d H:i:s", mktime(0, 0, 0, date("m") + 1, date("d"), date("Y")));

    if (!$account_session->save()) {
      $error_codes = $account_session->getValidator()->getErrorCodes();
      ApiHeader::error($error_codes[0]);
    }

    return true;
  }

  public function isAuthorizationUser()
  {
    return (bool)$this->authorized_account;
  }

  protected function checkAuth()
  {
    if (!$this->isAuthorizationUser()) {
      JsonResponse::error(ValidationErrorCodes::NOT_AUTHED);
    }
  }
}