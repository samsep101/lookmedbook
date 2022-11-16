<?php

/**
 * Base controller for cms controllers.
 * This controller implemers base actions with models described in cmsGenerator
 * entities.
 */
class CmsGenerator extends AdminBaseController
{

  public $modelName = NULL;

  /**
   * @var CmsGeneratorConfig
   */
  protected $dataModel = NULL;
  protected $model = NULL;

  protected $breadCrumbs;

  /**
   * Create a controller
   *
   * @param string $modelName
   */
  public function __construct($modelName = NULL)
  {
    $this->modelName = str_replace('-', '_', $modelName);
  }

  /**
   * List action by default
   */
  public function index()
  {
    $this->prepareIndexData();
    if (file_exists(ABS_ROOT . '/application/templates' . ADMIN_FOLDER . '/' . $this->modelName . '/list.tpl')) {
      $this->render('admin/' . $this->modelName . '/list');
    } else {
      $this->render('admin/generator/list');
    }
  }

  public function prepareIndexData()
  {
    $page = (int)$this->request('page', 1);
    $this->view->title = $this->dataModel->getListTitle();

    $fields = $this->dataModel->getListFields();

    $filter_values = $this->request('filter');

    $search_params = new SearchParams();

    $filters_settings = $this->dataModel->getListFilters();
    if ($filters_settings) {
      if (isset($filters_settings['use_class_params'])) {
        /**
         * @var ModelSearchCriteria $class_search_params
         */
        $class_name = $filters_settings['use_class_params'];
        $class_search_params = new $class_name();
        $class_search_params->setSearchParams($search_params);


        $cmsFilters = [];
        foreach ($filters_settings['filters'] as $filter) {
            foreach ($filter as $fieldName => $fieldSettings) {
                $cmsFilters[$fieldName] = $fieldSettings;
            }
        }

        if ($filter_values) {
          foreach ($filter_values as $filter_field => $filter_value) {
            $class_search_params->{$filter_field} = $filter_value;
              if (!empty($filter_value) && !in_array($filter_field, $class_search_params->getDirectIgnoredKeys())) {
                $bonus_params = isset($cmsFilters[$filter_field]['bonus_params']) ? $cmsFilters[$filter_field]['bonus_params'] : [];
                $search_params->addParam($filter_field, $filter_value, '', $bonus_params);
            }
          }
        }
      }
    }

    $joins = $this->dataModel->getListJoins();


    if ($joins)
      foreach ($joins as $join) {
        $search_params->addJoin($join['table'], $join['join_field'], $join['joined_field'], $join['join_type']);
      }


    $search_params->calcFoundRows();

    $fieldTitles = array();

    $params = $this->request('params', '');
    $params = $this->getParamsQueryString($params);

    $hide_fields = $this->request('hide_fields');
    $this->view->hide_fields = $hide_fields;

    $this->view->params = $params;

    $destination = $this->request('destination', '');
    $destination = str_replace('&', '%26', $destination);

    $this->view->destination = $destination;

    $sort_by = (isset($_GET['sort_by']) && $_GET['sort_by']) ? $_GET['sort_by'] : $this->dataModel->getSortBy();

    if ($sort_by)
      foreach ($sort_by as $sort) {
        $field_order = (isset($sort['field_order'])) ? $sort['field_order'] : 'ASC';
        $search_params->addSortParam($sort['field'], $sort['desc'], $field_order);
      }

    $where = $this->request('where');
    if ($where) {
      foreach ($where as $field => $value) {
        $search_params->addParam($field, $value);
      }
    }

    $where = $this->dataModel->getWhere();

    if ($where) {
      foreach ($where as $field => $value) {
        $search_params->addParam($field, $value);
      }
    }

    foreach ($fields as $fieldName => $field) {
      // todo: сделать фильтры
      if (!($this->dataModel->checkUserFilter($fieldName) && Acl::userGrant($this->dataModel->getModelName() . '_list_my'))) {
        if ($hide_fields && in_array($fieldName, $hide_fields))
          continue;
        $fieldTitles[$fieldName] = $this->dataModel->getFieldLabel($fieldName);
      }
    }

    $this->view->getAdditionalHTML = $this->dataModel->getAdditionalHTML();

    $this->view->fieldTitles = $fieldTitles;
    $this->view->addUrl = '/' . $this->modelName . '/add/';
    $this->view->addTitle = $this->dataModel->getAddTitle();

    $rules = $this->dataModel->getListRules();

    if ($rules) {
      $by_page = 100;


      $search_params->setPagingParams($page, $by_page);
      $this->view->page = $page;

      if (isset($class_search_params)) {
        $modelName = $this->dataModel->getModelName();
        $manager = ModelManagerFactory::getByName($modelName);
        if ($this->modelName == 'doctor' && $filter_values) {
          $data = $manager->getListByDoctorSearchParams($class_search_params);
        } else {
          $data = $manager->getListByModelSearchCriteria($class_search_params);
        }
      } else {
        $data = ModelManagerFactory::getByName($this->dataModel->getModelName())
          ->getListBySearchParams($search_params);
      }

      $this->view->data = $data;

      $cc = Db::getCountWithoutLimit();

      $this->view->filter_values = $filter_values;
      $this->view->pages_num = (int)(($cc - 1) / $by_page) + 1;
      $this->view->dataModel = $this->dataModel;
      $this->view->total_count = $cc;

      $this->view->indexField = $this->dataModel->getIndexField();
      $this->addBreadCrumb($this->dataModel->getListTitle(), '/' . $this->dataModel->getModelName());
    } else {
      $this->redirectUrl(ADMIN_FOLDER . '/security/denied/');
    }

  }

  private function getParamsQueryString($params)
  {
    $str = array();
    if ($params)
      foreach ($params as $k => $v) {
        $str[] = 'params[' . $k . ']=' . $v;
      }

    $str = join('&', $str);
    return $str;
  }


  public function xls()
  {
    $fields = $this->dataModel->getListFields();
    $fieldTitles = array();
    $filterSql = '';

    foreach ($fields as $fieldName => $field) {
      if ((int)$this->request($fieldName, '')) {
        if ($filterSql) {
          $filterSql = $filterSql . ' and ' . $fieldName . '=' . ((int)$this->request($fieldName));
        } else {
          $filterSql = ' ' . $fieldName . '=' . ((int)$this->request($fieldName));
        }
      }

      $fieldTitles[$fieldName] = $this->dataModel->getFieldLabel($fieldName);
    }
    $this->view->fieldTitles = $fieldTitles;
    $rules = $this->dataModel->getListRules();

    if ($filterSql) {
      if ($this->dataModel->userFilter) {
        $filterSql = $filterSql . ' and ' . $this->dataModel->userFilter;
      }
    } else {
      $filterSql = $this->dataModel->userFilter;
    }

    $filterSql = $this->getFilterSql($filterSql);

    if ($rules) {
      $order = $this->dataModel->getListOrder();
      if (!$order) {
        $order = 'id desc';
      }
      if ($filterSql) {
        $data = $this->model->select()->where($filterSql)->order($order)->fetchAll();
      } else {
        $data = $this->model->select()->order($order)->fetchAll();
      }

      $data = $this->dataModel->prepereForPrint($data);

      $xls = new ExcelXml('UTF-8', FALSE, $this->dataModel->getListTitle());
      $xls->addArray($data);
      $xls->generateXML($this->modelName);
      exit;

    } else {
      $this->redirectUrl(ADMIN_FOLDER . '/security/denied/');
    }


  }

  /**
   * Add action
   */
  public function add()
  {
    $this->prepareAddData();

    if (file_exists(ABS_ROOT . '/application/templates' . ADMIN_FOLDER . '/' . $this->modelName . '/add.tpl')) {
      $this->render('admin/' . $this->modelName . '/add');
    } else {
      $this->render(ADMIN_FOLDER . '/generator/add');
    }
  }

  public function prepareAddData()
  {
    $model = ModelFactory::getByName($this->dataModel->getModelName());

    $destination = $this->request('destination');
    $destination = str_replace('&', '%26', $destination);

    $this->view->destination = $destination;

    $params = $this->request('params');
    if ($params)
      foreach ($params as $param_name => $param_value) {
        $model->{$param_name} = $param_value;
      }

    if (isset($_POST['form'])) {
      if ($this->save($_POST['form'], $model)) {
        if ($destination) {
          $this->redirectUrl(preg_replace('/%26/', '&', $destination, 1));
        } else {
          $this->redirectUrl(ADMIN_FOLDER . '/' . $this->dataModel->getModelName());
        }
      }
    }

    $this->view->tabs = $this->dataModel->getAddTabs();
    $this->view->tabFields = array();
    $tabFields = array();


    foreach ($this->view->tabs as $tabName) {
      $tabFields[$tabName] = $this->dataModel->getAddTabFields($tabName);
    }

    $this->view->tabFields = $tabFields;
    $this->view->dataModel = $this->dataModel;
    $this->view->model = $model;
    $this->view->title = $this->dataModel->getAddTitle();
  }

  /**
   * Edit action
   */
  public function edit()
  {
    $this->prepareEditData();
    if (file_exists(ABS_ROOT . '/application/templates' . ADMIN_FOLDER . '/' . $this->modelName . '/edit.tpl')) {
      $this->render('admin/' . $this->modelName . '/edit');
    } else {
      $this->render(ADMIN_FOLDER . '/generator/edit');
    }
  }

  public function prepareEditData()
  {
    $indexField = $this->dataModel->getIndexField();
    $id = $this->request('id', 0);
    $id = Register::get('db')->escape($id);

    $modelName = $this->dataModel->getModelName();
    $manager = ModelManagerFactory::getManagerOrDefaultManager($modelName);
    $model = $manager->getOneById($id);

    $destination = $this->request('destination');
    $destination = str_replace('&', '%26', $destination);

    $this->view->destination = $destination;

    if (isset($_POST['form'])) {
      if ($this->save($_POST['form'], $model)) {
        if ($destination) {
          $this->redirectUrl(preg_replace('/%26/', '&', $destination));
        } else {
          $this->redirectUrl(ADMIN_FOLDER . '/' . $this->dataModel->getModelName());
        }
      }
    }

    if (!$model) $this->error404();

    $this->view->model = $model;
    $this->view->tabs = $this->dataModel->getEditTabs();
    $this->view->tabFields = array();
    $tabFields = array();
    foreach ($this->view->tabs as $tabName) {
      $tabFields[$tabName] = $this->dataModel->getEditTabFields($tabName);
    }
    $this->view->tabFields = $tabFields;
    $this->view->dataModel = $this->dataModel;

    $this->view->indexField = $indexField;
    $this->view->indexValue = $id;
  }

  /**
   * Save action
   */
  protected function save($data, DynamicModel $model)
  {
    foreach ($data as $field_name => $field_value) {
      if ($field_name != $this->dataModel->getIndexField()) {
        $model->{$field_name} = $this->dataModel->fields[$field_name]->getSaveValue($field_value, $model);
      }
    }

    if (ModelManagerFactory::getManagerByModel($model)->save($model)) {
      return TRUE;
    } else {
      $this->view->validation_errors = $model->getValidator()->getErrorMessages();
      return FALSE;
    }

  }

  public function translitIt($str)
  {
    $tr = ["А" => "a", "Б" => "b", "В" => "v", "Г" => "g",
      "Д" => "d", "Е" => "e", "Ж" => "g", "З" => "z", "И" => "i",
      "Й" => "j", "К" => "k", "Л" => "l", "М" => "m", "Н" => "n",
      "О" => "o", "П" => "p", "Р" => "r", "С" => "s", "Т" => "t",
      "У" => "u", "Ф" => "f", "Х" => "h", "Ц" => "ts", "Ч" => "ch",
      "Ш" => "sh", "Щ" => "sch", "Ъ" => "", "Ы" => "y", "Ь" => "",
      "Э" => "e", "Ю" => "ju", "Я" => "ja", " " => "_", "." => "",
      "/" => "_",];
    foreach($tr as $source=>$dest) {
      $tr[mb_strtolower($source, 'UTF-8')] = $dest;
    }

    return strtr($str, $tr);
  }

  public function doTraslit($urlstr)
  {
    if (preg_match('/[^A-Za-z0-9_\-]/', $urlstr)) {
      $urlstr = $this->translitIt($urlstr);
      $urlstr = preg_replace('/[^A-Za-z0-9_\-]/', '', $urlstr);
    }
    return $urlstr;
  }

  public function trimA($str, $set = NULL)
  {
    if (is_Array($str) || is_Object($str)) {
      foreach ($str as &$s) {
        $s = $this->trimA($s, $set);
      }
    }elseif ($set === NULL) {
      $str = trim($str);
    }else {
      $str = trim($str, $set);
    }
    return $str;
  }

  /**
   * Delete action
   */
  public function delete()
  {
    $indexField = $this->dataModel->getIndexField();
    $id = $this->request($indexField, 0);

    if (!empty($id))
      ModelManagerFactory::getManagerOrDefaultManager($this->dataModel->getModelName())->deleteById($id);

    $destination = $this->request('destination', '');

    if ($destination)
      $this->redirectUrl($destination);
    else
      $this->redirectUrl(ADMIN_FOLDER . '/' . $this->dataModel->getModelName());
  }

  /**
   * Delete list action
   */
  public function delete_list()
  {
    $indexField = $this->dataModel->getIndexField();
    $ids = $this->request("delete_list", 0);
    if (!empty($ids)) {
      foreach ($ids as $id) {
        if (!empty($id)) {
          $manager = ModelManagerFactory::getByName($this->modelName);
          $manager->deleteById($id);
        }
      }
    }

    $destination = $this->request('destination', '');

    if ($destination)
      $this->redirectUrl($destination);
    else
      $this->redirectUrl(ADMIN_FOLDER . '/' . $this->dataModel->getModelName());
  }

  /**
   * Initialize action
   */
  public function beforeAction()
  {
    parent::beforeAction();
    $this->view->_controller = $this->controller;
    $this->view->_action = $this->action;
    $this->modelName = str_replace('-', '_', $this->controller);

    if (!Acl::hasAccessToAdminPanel()) {
      if ($this->request->isAJAX()) {
        echo '<script>window.location = "' . ADMIN_FOLDER . '/security/login"</script>';
        die();
      } else {
        $this->redirectUrl(ADMIN_FOLDER . '/security/login');
      }
    }

    $userId = Acl::userId();
    $acl = new Acl($userId);
    $this->view->acl = $acl;


    if (!$acl->hasRights($this->controller, $this->action)) {
      $this->redirectUrl('/admin/security/denied');
    }

    if ((int)$this->request('ajax')) {
      $this->view->setLayout('ajax');
      $this->view->ajax = 1;
    } else {
      $this->view->setLayout('admin');
      $this->view->ajax = 0;
    }
    $this->dataModel = new CmsGeneratorConfig($this->modelName);
    $this->model = new CmsGeneratorModel($this->dataModel);

    if ($this->controller != 'index') {
      $parentTitle = $this->dataModel->getParentTitle();
      if (!empty($parentTitle)) {
        $this->addBreadCrumb($parentTitle, '/' . $this->dataModel->getParentUrl());
      }
      $this->addBreadCrumb($this->dataModel->getTitle(), '/' . $this->dataModel->getModelName() . '/');
    }

  }

  /**
   * Prepare data for rendering
   */
  public function beforeRender()
  {
    global $menu, $menu_settings;
    $this->checkMenu();

    $this->view->menu = $menu;
    $this->view->breadCrumbs = $this->breadCrumbs;
  }

  /**
   * Add item to breadrumbs
   *
   * @param string $title
   * @param string $url
   */
  public function addBreadCrumb($title, $url = '#')
  {
    $this->breadCrumbs[$title] = $url;
  }

  public function checkMenu()
  {
    if (count($GLOBALS['menu']))
      foreach ($GLOBALS['menu'] as $key => $val) {
        if (is_array($val)) {

          foreach ($val as $skey => $sval) {
            if (is_array($sval)) {
              if (@$sval[1] == 'add') {
                if (!$this->view->acl->canViewMenuItem($sval[0])) {
                  unset($GLOBALS['menu'][$key][$skey]);
                }
              } else {
                foreach ($sval as $sskey => $ssval) {
                  if (is_array($ssval)) {
                    if (!$this->view->acl->canViewMenuItem($ssval[0])) {
                      unset($GLOBALS['menu'][$key][$skey][$sskey]);
                    }
                  } else {
                    if (!$this->view->acl->canViewMenuItem($ssval)) {
                      unset($GLOBALS['menu'][$key][$skey][$sskey]);
                    }
                  }
                }

                if (!count($GLOBALS['menu'][$key][$skey]))
                  unset($GLOBALS['menu'][$key][$skey]);
              }
            } else {
              if (is_array($sval)) {
                if (!$this->view->acl->canViewMenuItem($sval[0])) {
                  unset($GLOBALS['menu'][$key][$skey]);
                }
              } else {
                if (!$this->view->acl->canViewMenuItem($sval)) {
                  unset($GLOBALS['menu'][$key][$skey]);
                }
              }
            }
          }
          if (!count($GLOBALS['menu'][$key]))
            unset($GLOBALS['menu'][$key]);
        } else {
          if (!$this->view->acl->canViewMenuItem($val)) {
            unset($GLOBALS['menu'][$key]);
          }
        }
      }
  }

  function _log($flag, $code, $dataArr)
  {
    $logger = new Logger($flag, $code, Acl::userLogin(), $dataArr);
    $logger->send();
  }


  function error404($exception = NULL)
  {
    if (debug) {
      echo '
        <html>
          <head>
            <style>
            * { font-family: Verdana, Tahoma, Arial; font-size: 13px; }
            body {}
            .error {width: 860px;text-align: left;}
            .error h1 {font-size: 18px;margin-left: 36px;}
            .error-num {float: left;width: 30px;background-color: #fff;padding: 3px;text-align: left;}
            .error-descr {float: left;background-color: #f5f5f5;margin-bottom: 10px;padding: 3px;width: 800px;text-align: left;}
            .error-descr span {font-weight: bold;}
            </style>
          </head>
        ';
      if ($exception) {
        echo '<center>
          <div class="error"><h1>' . $exception->getMessage() . '</h1>';
        foreach ($exception->getTrace() as $key => $item) {
          echo '<div class="error-item">';
          echo '<div class="error-num">' . ($key + 1) . '.</div>';
          echo '<div class="error-descr">';
          echo '<span>' . $item['class'] . ' ' . $item['type'] . ' ' . $item['function'] . ' ' . '( ' . join(', ', $item['args']) . ' )' . '</span><br>';
          echo $item['file'] . ' (Line: ' . $item['line'] . ')<br>';
          echo '</div>';
          echo '<br clear="all" />';
          echo '</div>';
        }
        echo '</center>';
      }
      echo '</body></html>';
    } else {
      $this->redirectUrl(ADMIN_FOLDER . '/index/error/');
    }
  }

  protected function getFilterSql($filter)
  {
    return $filter;
  }

  public function setFlash($name, $value)
  {
    $_SESSION['_flashes'][$name] = $value;
  }

  public function hasFlash($name)
  {
    if (!empty($_SESSION['_flashes'][$name])) {
      return TRUE;
    } else {
      return FALSE;
    }
  }

  public function getFlash($name)
  {
    $flashText = $_SESSION['_flashes'][$name];
    unset($_SESSION['_flashes'][$name]);
    return $flashText;

  }

  public function setInfFlash($infMsgCode)
  {
    $infMsg = Register::get('infMsg');
    $this->setFlash('infMes', $infMsg[$infMsgCode]);
  }

  public function getInfFlash()
  {
    return $this->getFlash('infMes');
  }

  public function setErrFlash($errMsgCode)
  {
    $errMsg = Register::get('errMsg');
    $this->setFlash('errMes', $errMsg[$errMsgCode]);
  }

  public function getErrFlash()
  {
    return $this->getFlash('errMes');
  }


}
