<?php

class AjaxAdminController extends Controller
{
  public function __construct()
  {
    $this->layout = 'ajax';
  }

  public function setCheckbox()
  {
    $table = $this->request->post('table');
    $id = $this->request->post('id');
    $field_name = $this->request->post('field_name');
    $value = $this->request->post('value');

    if (!Acl::userGrant($table . '_edit')) {
      JsonResponse::error(ValidationErrorCodes::ACCESS_DENIED);
    }

    $manager = ModelManagerFactory::getByName($table);

    if ($manager) {
      $model = $manager->getOneById($id);

      if ($model) {
        $model->{$field_name} = $value;
        $model->checkbox_set = 1;
        if ($model->save()) {
          JsonResponse::result($model->{$field_name});
        }
      }
    }

    JsonResponse::result(true);
  }

  public function setCheckboxes()
  {
    $table = $this->request('table');
    $field_name = $this->request('field_name');
    $values = $this->request('values');

    $manager = ModelManagerFactory::getByName($table);


    if ($values) {
      foreach ($values as $id => $value) {
        if ($manager) {
          $model = $manager->getOneById($value['id']);

          if ($model) {
            $model->{$field_name} = $value['value'];
            $model->save();
          }
        }
      }

      JsonResponse::result(true);
    }

  }

  public function saveItemsOrder()
  {
    if (!Acl::isAuthed()) {
      JsonResponse::error(ValidationErrorCodes::NOT_AUTHED);
    }

    $model_name = $this->request->post('model_name');
    $order = $this->request->post('order');

    $manager = ModelManagerFactory::getByName($model_name);

    $manager->setOrder($order);

    JsonResponse::result();
  }

  public function getPurposesOfVisitBySpecialtyId()
  {
    if (!Acl::isAuthed())
      JsonResponse::result(ValidationErrorCodes::NOT_AUTHED);

    $specialty_id = $this->request('specialty_id');

    $purpose_of_visit_manager = new PurposeOfVisitManager();

    $purposes_of_visit = $purpose_of_visit_manager->getListBySpecialtyId($specialty_id);

    $result = array();

    if ($purposes_of_visit)
      foreach ($purposes_of_visit as $purpose_of_visit) {
        $result[] = array(
          'id' => $purpose_of_visit->getId(),
          'name' => $purpose_of_visit->name
        );
      }

    JsonResponse::result($result);
  }

  public function saveToken()
  {
    if (!Acl::isAuthed())
      JsonResponse::error(4);

    $auth_code = $this->request('token');

    $google_api_adapter = new GoogleClientAuthAdapter();
    $token = $google_api_adapter->getTokenByAuthCode($auth_code);

    if ($token) {
      SettingsManager::set('google_api_token', $token);
      JsonResponse::result(true);
    } else {
      JsonResponse::error(5);
    }
  }

  public function getYandexContentStatistic()
  {
    if (!Acl::isAuthed())
      JsonResponse::result(ValidationErrorCodes::NOT_AUTHED);

    $disease_manager = new DiseaseManager();
    $yandex_counters = new YandexContentCounters();
    $yandex_counters->content_active = $disease_manager->getCountActiveList();
    $yandex_counters->content_not_in_yandex = $disease_manager->getCountNotInYandex();
    $yandex_counters->content_in_yandex = $disease_manager->getCountInYandex();
    $yandex_counters->content_to_update = $disease_manager->getCountInYandexToUpdate();

    $this->view->yandex_counters = $yandex_counters;
    $html = $this->renderInString('/admin/blocks/yandex_content_counters');
    JsonResponse::result(array('html' => $html));
  }


  public function getSelectList()
  {
    ini_set("memory_limit", "156M");

    $cross_table = $this->request->post('cross_table');
    $cross_name = $this->request->post('cross_name');
    $cross_index = $this->request->post('cross_index');
    $sort_by = $this->request->post('sort_param');
    $params = $this->request->post('search_param');
    $value = $this->request->post('value');
    $title = $this->request->post('title');

//			$cross_table = 'doctor';
//			$cross_name = 'full_name';
//			$sort_by = 'full_lower_name';
//			$params = '';

    if (!$cross_table or !$cross_name) {
      return JsonResponse::result([]);
    }

    $search_params = new SearchParams();

    if ($sort_by) {
      $search_params->addSortParam($sort_by, 'ASC');
      $search_params->addParam($sort_by . '!=', '');
      $search_params->addParam($sort_by . '!=', ' ');
      $search_params->addParam($sort_by . '!=', '  ');
      $search_params->addParam($sort_by . '!=', '   ');
    }
    if ($params) foreach ($params as $param) {
      list($par1, $par2) = $param;
      if ($par1 == 'join') {
        $search_params->addJoin($par2);
      } else {
        $search_params->addParam($par1, $par2);
      }
    }

    if ($value) {
      if ($cross_index) {
        $search_params->addParam($cross_index, $value);
      } else {
        $search_params->addParam('id', (int)$value);
      }
    }
    if ($title) {
      $search_params->addParam($sort_by . ' LIKE ', '%' . $title . '%');
    }

    $i = 1;
    $result = [];
    do {
      $search_params->setPagingParams($i++, 2000);

      $manager = ModelManagerFactory::getByName($cross_table);
      $aData = $manager->getListBySearchParams($search_params);
      foreach ($aData as $value) {
        if ($cross_index) {
            $value_id = $value->{$cross_index};
        } else {
            $value_id = $value->getId();
        }
        $value_title = trim(htmlspecialchars(str_replace('<br />', '', $value->{$cross_name})));
        if ($value_title) {
          $result[$value_id] = "$value_title [{$value_id}]";
        }
      }
    } while (count($aData) and $i <= 5);
    unset($aData);

    return JsonResponse::result($result);
  }

}