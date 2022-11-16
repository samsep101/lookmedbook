<?php

class AccountManageController extends BaseController
{
  private $requestFieldList = ['first_name', 'middle_name', 'last_name', 'phone', 'email', 'id',
    'nick', 'password', 'is_confirmed', 'is_system_access', 'is_call_centre_operator', 'is_product_admin',
    'last_succes_callup'];

  public function __construct()
  {
    $this->layout = 'registry';
    parent::__construct();
  }

  private function delAcccount($del_id) {
    foreach(['account_session', 'account_phone', 'appeal', 'my_clinic', 'my_doctor', 'my_disease', 'visit',
              'fb_account', 'ok_account', 'vk_account'] as $tabName) {
      $manager = ModelManagerFactory::getByName($tabName);
      $manager->delByAccountId($del_id);
    }
    $manager = new AccountManager();
    $manager->deleteById($del_id);
  }

  public function index() {
    if (!Acl::isAuthed(RoleModel::ACCOUNT_SUPER_MANAGER) && !Acl::isAuthed(RoleModel::ACCOUNT_MANAGER))
      RedirectManager::redirect(ADMIN_FOLDER);

    $del_id = $this->request->get('del_id');
    if($del_id>0) {
      $this->delAcccount($del_id);
    }

    $sortBy = $this->request->get('sort');
    $sortAsc = $this->request->get('asc');

    $account_search_params = new AccountSearchParams();
    $search_line = '';
    foreach($this->requestFieldList as $fName) {
      //$fValue = $this->request($fName);
      $fValue = $this->request->get($fName);
      $account_search_params->$fName = $fValue;
      if($fValue) $search_line .= ($search_line?'&':'').$fName.'='.urlencode($fValue);
      $this->view->$fName = $fValue;
    }
    $page = (int)$this->request->get('page');
    if($page) {
      $account_search_params->page=$page;
    }

    if(in_array($sortBy, ['first_name', 'middle_name', 'last_name', 'nick', 'phone', 'email'])) {
      $account_search_params->sort_by = $sortBy;
    }elseif($sortBy=='regdate'){
      $account_search_params->sort_by = 'dt';
    }
    if($account_search_params->sort_by){
      $asc = 1;
      if($sortAsc==-1) {
        $asc = -1;
        $account_search_params->sort_asc = -1;
      }
      $search_line .= ($search_line?'&':'').'sort='.$sortBy.'&asc='.$asc;
    }

    $account_manager = new AccountManager();
    $accounts = $account_manager->getListByModelSearchCriteria($account_search_params);
    $count = $account_manager->getTotalHits();

    $this->view->accounts = $accounts;
    $this->view->records_count = $count;
    $this->view->page_nm = $page?$page:1;
    $this->view->page_size = $account_search_params->by_page;
    $this->view->search_line = $search_line;
  }

  public function callup()
  {
    $acc_id = $this->request('id');
    $account_manager = new AccountManager();
    if($acc_id && $account = $account_manager->getOneById($acc_id)){

      $account->last_succes_callup = date('Y-m-d H:i:s');
      $account->save();
      die('Данные обновлены');

    }else{
        throw new Exception('no user found');
    }
  }

  public function create()
  {
    $this->edit();
  }


  public function edit()
  {
    if (!RegistryAccessHelper::checkAuth())
      JsonResponse::error(ValidationErrorCodes::NOT_AUTHED);

    $acc_id = $this->request('id');
    if($acc_id) {
      $account_manager = new AccountManager();
      $account = $account_manager->getOneById($acc_id, 'w_phone');
    }else{
      $account = new stdClass();
    }

//    pr($account->last_succes_callup, 1);

    foreach($this->requestFieldList as $field) {
      $fld_val = @$account->$field;
      $this->view->$field = empty($fld_val)?'':$fld_val;
    }

//    $by_page = 15;
//    $page = $this->request('page', 1);

    //visit conf
    require(ABS_ROOT.'/application/config/cms_generator_configs/visit.cfg.php');
    $visit_conf = ['status_id'=>$visit['fields']['status_id']['values']];
    $this->view->visit_conf = $visit_conf;
    //$this->view->last_access_callup = $account->last_succes_callup;

    $visitManager = new VisitManager();
    $visits = $visitManager->getListByAccountId($acc_id);
    $visit_list_fld = [
      'visit_number'=>'ID',
      'full_name'=>'ФИО',
      'processed_user'=>'Регистратор',
      'status_id'=>'Статус',
      'visit_start_time'=>'Начало посещения',
      'create_time'=>'Время создания',
      'city_id'=>'Город',
    ];


    foreach($visits as $i=>$visit) {
      $visits[$i]->item_id = $visit->getId();
      foreach($visit_list_fld as $fld_nm) {
        $val = @$visit->$fld_nm;
        $visits[$i]->$fld_nm = $val;
      }
    }

    $acc_manager = new AccountManager();
    $city_manager = new CityManager();
    foreach($visits as $i=>$visit) {
      $account = $acc_manager->getOneById($visit->processed_user);
      $visit->processed_user =  $account?$account->email:'';
      $city = $city_manager->getOneById($visit->city_id);
      $visit->city_id =  $city?$city->name:'';
    }


    $this->view->visit_fields = $visit_list_fld;
    $this->view->visits = $visits;

    //appeal conf
    //require($_SERVER['DOCUMENT_ROOT'].'/application/config/cms_generator_configs/appeal.cfg.php');

    $appManager = new AppealManager();
    $appeals = $appManager->getListByAccountId($acc_id);

    $appeal_list_fld = [
      'phone_number'=>'Телефон',
      'full_name'=>'ФИО',
      'appeal_type_id'=>'Тип',
      'visit_source_id'=>'Источник',
      'specialty_id'=>'Специальность',
      'is_with_visit'=>'С посещением',
      'dt_create'=>'Дата создания',];

    foreach($appeals as $i=>$appeal) {
      $appeals[$i]->item_id = $appeal->getId();
      foreach($appeal_list_fld as $fld_nm) {
        $val = @$appeal->$fld_nm;
        $appeals[$i]->$fld_nm = $val;
      }
    }

    $appeal_type_manager = ModelManagerFactory::getByName('appeal_type');
    $visit_source_manager = ModelManagerFactory::getByName('visit_source');
    $specialty_manager = ModelManagerFactory::getByName('specialty');
    foreach($appeals as $i=>$appeal) {
      $type = $appeal_type_manager->getOneById($appeal->appeal_type_id);
      $appeal->appeal_type_id = $type?$type->name:'';
      $visit_source = $visit_source_manager->getOneById($appeal->visit_source_id);
      $appeal->visit_source_id = $visit_source?$visit_source->name:'';
      $specialty = $specialty_manager->getOneById($appeal->specialty_id);
      $appeal->specialty_id = $specialty?$specialty->name:'';
      $appeal->is_with_visit = $appeal->is_with_visit?'Да':'Нет';

    }

    $this->view->appeal_fields = $appeal_list_fld;
    $this->view->appeals = $appeals;
  }

  public function ajaxEditAccount()
  {
    if (!RegistryAccessHelper::checkAuth())
      JsonResponse::error(ValidationErrorCodes::NOT_AUTHED);

    $acc_id = $this->request->post('account_id');
    if($acc_id>0) {
      $account_manager = new AccountManager();
      $account = $account_manager->getOneById($acc_id);

      foreach($this->requestFieldList as $fName) {
        if($fName=='id') continue;
        $fld_val = @$account->$fName;
        $account->$fName = $this->request->post($fName);
      }

    }

    if(empty($account)) {
      $account = new AccountModel();
    }
    foreach($this->requestFieldList as $fName) {
      if($fName=='id') continue;
      $fld_val = $this->request->post($fName);
      $account->$fName = $fld_val?$fld_val:'';
    }

    if ($account->save() and $acc_id=$account->getId()) {
      $errors = $account->save_phones($acc_id, $account->phone);
      if($errors) {
        $ans = ['account_id' => $acc_id];
        foreach($errors as $i=>$err) {
          $ans['err'.$i] = $err;
        }
        JsonResponse::error(ValidationErrorCodes::WRONG_DATA, $ans);
      }else {
        JsonResponse::result(['account_id' => $acc_id]);
      }
    } else {
      JsonResponse::error(ValidationErrorCodes::WRONG_DATA, $account->getValidator()->getErrorMessages());
    }
  }


}