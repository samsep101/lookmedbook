<?php

use app\library\helpers\Morpher;
use app\library\resources\Style;

class ClinicController extends BaseController
{
    /**
     * @var SpecialtyModel|null
     */
    protected $specialization = null;

    /**
     * @var DistrictModel|null
     */
    protected $district = null;

    /**
     * @var RegionModel|null
     */
    protected $region = null;

    /**
     * @var StreetModel|null
     */
    protected $street = null;

    /**
     * @var MetroModel|null
     */
    protected $metro = null;

    public function get()
    {
        $urlVars = $this->fillUrlVars();

        end($urlVars);

        if (count($urlVars) < 3) { // val1/val2 - может быть alias'ом клиники

            // 1. лэндинг
            if ($this->landingPage(current($urlVars))) {
                exit();
            }
        }

        // val2 - maybe specialty
        /** @var SpecialtyManager $specializationManager */
        $specializationManager = ModelManagerFactory::getByName('specialization');
        /** @var SpecialtyModel|null $specialization */
        $specialization = $specializationManager->getOneByAlias(current($urlVars));
        if ($specialization) {
            $this->specialization = $specialization;
            prev($urlVars);
        }

        $district = null;
        if (current($urlVars) !== false) {
            /** @var DistrictManager $districtManager */
            $districtManager = ModelManagerFactory::getByName('district');
            $district = $districtManager->getOneByAlias(current($urlVars));
            if ($district) {
                $this->district = $district;
                prev($urlVars);
            }
        }

        $region = null;
        if (current($urlVars) !== false) {
            /** @var RegionManager $regionManager */
            $regionManager = ModelManagerFactory::getByName('region');
            $region = $regionManager->getOneByAlias(current($urlVars));
            if ($region) {
                $this->region = $region;
                prev($urlVars);
            }
        }

        $street = null;
        if (current($urlVars) !== false) {
            /** @var StreetManager $streetManager */
            $streetManager = ModelManagerFactory::getByName('street');
            $street = $streetManager->getOneByAlias(current($urlVars));
            if ($street) {
                $this->street = $street;
                prev($urlVars);
            }
        }

        $metro = null;
        if (current($urlVars) !== false) {
            /** @var MetroStationManager $metroManager */
            $metroManager = ModelManagerFactory::getByName('metro_station');
            /** @var MetroStationModel|null $metro */
            $metro = $metroManager->getOneByAlias(current($urlVars));
            if ($metro) {
                $this->metro = $metro;
                prev($urlVars);
            }
        }

        if ($specialization) {
            if ($district)
                $this->view->district = $district;
            if ($region) {
                $this->view->region=$region;
            }
            if ($street)
                $this->view->street=$street;
            if ($metro) {
                if ($metro->main_station) {
                    RedirectManager::redirect301(
                        SeoLinkViewHelper::getSpecialtyPageLink(
                            $specialization,
                            $metro,
                            'clinic'
                        )
                    );
                }
                $this->view->metro_station = $metro;
            }

            $this->index($specialization->alias);
        } else {
            $this->clinic(end($urlVars), prev($urlVars));
        }
    }

    /**
     * Заполнение переменных из URL
     * @return array
     */
    protected function fillUrlVars()
    {
        $urlVars = [];
        $v = $this->request('val4');
        if ($v) {
            $urlVars[] = $v;
        }
        $v = $this->request('val3');
        if ($v) {
            $urlVars[] = $v;
        }
        $v = $this->request('val2');
        if ($v) {
            $urlVars[] = $v;
        }
        $v = $this->request('val1');
        if ($v) {
            $urlVars[] = $v;
        }

        return $urlVars;
    }

  public function ajaxAddToMyClinicList()
  {
    $clinic_id = $this->request->post('clinic_id');
    $account_id = Acc::accountId();
    $date = date('Y-m-d H:i:s');

    if (!ModelManagerFactory::getByName('my_clinic')->checkExistsByClinicIdAndAccountId($clinic_id, $account_id)) {
      $my_clinic = new MyClinicModel();
      $my_clinic->account_id = $account_id;
      $my_clinic->clinic_id = $clinic_id;
      $my_clinic->dt = $date;
      if (ModelManagerFactory::getByName('my_clinic')->save($my_clinic)) {
        JsonResponse::result(array('my_clinic' => TRUE));
      } else {
        JsonResponse::error(2);
      }
    } else {
      $my_clinic = ModelManagerFactory::getByName('my_clinic')->getOneByClinicIdAndAccountId($clinic_id, $account_id);
      ModelManagerFactory::getByName('my_clinic')->delete($my_clinic);
      JsonResponse::result(array('my_clinic' => FALSE));
    }
  }

  public function getClinicPageDescription() {

    $seo_specialization= $this->view->specialization ? $this->view->specialization->name : null;

    if (isset($this->view->clinic->name)) {

        return  SeoTextViewHelper::newGetClinicPageDescription($this->view->clinic);

    } else {
      if (empty($seo_specialization)) {
          return 'Ищете медицинские центры и клиники '.$this->getSeoAddress().'? '.SITE_NAME.' поможет выбрать лучшие клиники и медицинские центры по отзывам, рейтингу и стоимости. Заходите!';
      } else if (extension_loaded('morpher')) {
        return 'Ищете медицинские центры и клиники '.Morpher::inflect($seo_specialization,'rod').' '.$this->getSeoAddress().'? '.SITE_NAME.' поможет выбрать лучшие клиники и медицинские центры по отзывам, рейтингу и стоимости. Заходите!';
      } else {
        return 'Все клиники и центры '. WordDeclination::getInstance()->toGenitive($seo_specialization) .' '.$this->getSeoAddress().' на одном сайте! Запишись на прием онлайн!';
      }

    }
  }
  public function index($specialization_alias = NULL)
  {
      if ($_SERVER['REQUEST_URI'] == '/clinic/search') {
          ErrorPageViewHelper::page404('404');
      }
    $landing = $this->request('landing');

    /**
     * @var SpecialtyManager $specialty_manager
     * @var SpecializationManager $specialization_manager
     */
    $specialization_manager = ModelManagerFactory::getByName('specialization');
    $specialty_manager = ModelManagerFactory::getByName('specialty');

    if (isset($_GET['specialty_id'])) {
      $specialty = $specialty_manager->getOneById($_GET['specialty_id']);

      $query_string = preg_replace('/specialty_id=([0-9]+)?&?/', '', $_SERVER['QUERY_STRING']);

      $url = '';

      if ($specialty) {
        if ($query_string) {
          if ($query_string{0} != '?') {
            $query_string = '?' . $query_string;
          }
        }
        $url = '//' . $_SERVER['HTTP_HOST'] . '/clinic/' . $specialty->alias . $query_string;
      } else {
        $url = '//' . $_SERVER['HTTP_HOST'] . '/clinic' . $query_string;
      }

      RedirectManager::redirect301($url);
    }

      if (!$specialization_alias) {
          $specialization_alias = $this->request('specialty');
      }

      if ($this->specialization) {
          $specialization = $this->specialization;
      } else {
          $specialization = null;

          if ($specialization_alias) {
              $this->view->is_seo_page = 1;

              $specialization = $specialization_manager->getOneByAlias($specialization_alias);

              if (!$specialization) {
                  ErrorPageViewHelper::page404('404');
              }
          }
      }

    $city = $this->city;
    $latitude = $this->city->lat;
    $longitude = $this->city->lng;
    $city_id = $this->city->getId();

    $address_object = null;

    if ($this->metro) {
      $address_object = $this->metro;
    } elseif ($this->street) {
        $address_object = $this->street;
        // #4227 Временно убираем страницы улиц
        if ($this->specialization) {
            $urlVarsStr = "/{$this->specialization->alias}";
        } else {
            $urlVarsStr = "";
        }
        RedirectManager::redirect301(LinkHelper::getSiteUrlByCity($this->city) . "/clinic$urlVarsStr");
    } elseif ($this->region && is_object($specialization)) {
        $address_object = $this->region;
    } elseif ($this->district) {
        $address_object = $this->district;
    } elseif ($city) {
        $address_object = $city;
    }

    $params = ClinicSearchHelper::initClinicSearchParams($this->request, $this->city->id);
    $params->specialization_id = isset($specialization->id) ? $specialization->id : null;

    $clinic_search_algorithm = new ClinicSearchAlgorithm();
    $clinic_search_algorithm->setIsSearchNearestAllowed(true);
    $clinics = $clinic_search_algorithm->search($params);
    $clinics = $this->processClinics($clinics, $params, $specialization);
    $clinicTotalCount = $clinic_search_algorithm->getTotalClinicCount();
    $additionalClinics = $clinic_search_algorithm->getAdditionalClinics();

    $this->view->clinicsSearchErrorMessage = $this->getClinicSearchErrorMessage($clinic_search_algorithm, $params, $additionalClinics);
    $this->view->nextPageFlag = (($params->page - 1) * $params->by_page + count($clinics)) < $clinicTotalCount;
    $this->view->address_object = $address_object;

    $this->view->clinics = $clinics;
    $this->view->clinicTotalCount = $clinicTotalCount;
    $this->view->specialization = $specialization;
    $this->view->city = $city;
    $this->view->city_id = $city_id;
    $this->view->landing_page = $landing;
    $this->view->specialization = $specialization;
    $this->view->specializations = $specialization_manager->getSpecializationForCityIDInWhichHaveDoctors($city_id);
    $this->view->getSpecializationsGroups = [$specialization_manager, 'getActiveSpecializationsByCity'];

    $this->view->specialties = $specialty_manager->getHavingDoctorsListByCityId($city_id);
    $this->view->specialties_groups = SpecialtyHelper::getSpecialtiesLetterGroups($this->view->specialties, array(), 1);
    $this->view->menu_active = 'clinic';

    $this->view->city_id = $city_id;
    $this->view->latitude = $latitude;
    $this->view->longitude = $longitude;

    $this->view->load_map = TRUE;

    $this->view->page_title = $this->getClinicPageTitle(null);
    $this->view->h1 = $this->getClinicPageH1($specialization ? $specialization->name : null);
    $this->view->page_description = $this->getClinicPageDescription();
    $this->view->search_page_description = SeoTextViewHelper::getClinicPageDescription($specialization, $address_object);

    if ($specialization) {
        $this->view->canonical_link = SeoLinkViewHelper::getSpecialtyPageLink($specialization, $address_object, 'clinic');
        $this->view->site_url_not_using = true;
    } else {
        $this->view->canonical_link = '/clinic';
    }
    $this->view->page_type = 'clinic';

    $metroManager = new MetroManager();
    $this->view->hasMetro = !empty($metroManager->getListByCityId($city_id));

    $this->render('clinic/search');
  }

  public function ajaxSearch()
  {
    $this->layout = 'ajax';

    $landing = $this->request('landing');

    $this->view->city = $this->city;
    $this->view->landing_page = $landing;

    $params = ClinicSearchHelper::initClinicSearchParams($this->request, $this->city->id);
    $specialization_manager = ModelManagerFactory::getByName('specialization');
    $specialization = $specialization_manager->getOneById($params->specialization_id);

    $clinic_search_algorithm = new ClinicSearchAlgorithm();
    $clinic_search_algorithm->setIsSearchNearestAllowed(true);
    $clinics = $clinic_search_algorithm->search($params);
    $clinics = $this->processClinics($clinics, $params, $specialization);
    $clinicTotalCount = $clinic_search_algorithm->getTotalClinicCount();
    $additionalClinics = $clinic_search_algorithm->getAdditionalClinics();

    // Получаем новый тайтл
    $page_title = '';
    if ($params->specialty_id || $params->specialization_id) {
      $item = $params->specialty_id;

      if ($params->specialization_id) {
        $item = $specialization;
      }
      $page_title = $this->getClinicPageTitle($item);
    }

    $this->view->clinics = $clinics;
    $this->view->specialization = $specialization;
    $html = $this->renderInString('clinic/card_small_list');
    $is_empty_city = 0;
    $city = ModelManagerFactory::getByName('city')->getOneById($params->city_id);
    $city_clinic = ModelManagerFactory::getByName('clinic')->getOneByCityId($params->city_id);
    if ($city->service_flag == 0 || (($city->service_flag == 1) && (!$city_clinic))) $is_empty_city = 1;

    $clinic_word_form = SpecialtyHelper::getClinicWordForm($clinicTotalCount);
    $specialty_name = '';

    if ($params->specialty_id) {
      $specialty_name = SpecialtyHelper::getNameByCount($params->specialty_id, $clinicTotalCount);
    } else if ($params->specialization_id) {

      $specialization = $specialization_manager->getOneById($params->specialization_id);
      $specialty_name = $specialization->name;
    }

    $result = array(
      'html' => $html,
      'nearest' => $params->nearest,
      'additional_clinics' => $additionalClinics,
      'next_page' => (($params->page-1)*$params->by_page+count($clinics))<$clinicTotalCount,
      'full_search' => $clinic_search_algorithm->getGoodSearchFlag(),
      'is_empty_city' => $is_empty_city,
      'city_name' => $city->name,
      'page_title' => $page_title,
      'clinic_total_count' => $clinicTotalCount,
      'specialty_name' => $specialty_name,
      'clinic_word_form' => $clinic_word_form,
    );

    JsonResponse::result($result);
  }

  private function processClinics($clinics, $params, $specialization)
  {
      foreach ($clinics AS $cKey => $cValue) {
          $clinics[$cKey] = $this->processedClinicItem($cValue, $params, $specialization);
      }
      return $clinics;
  }

  private function getClinicSearchErrorMessage(ClinicSearchAlgorithm $clinicSearchAlgorithm, ClinicSearchParams $params, $additionalClinics)
  {
      if ($clinicSearchAlgorithm->getGoodSearchFlag() === false) {
          $is_empty_city = 0;
          $city = ModelManagerFactory::getByName('city')->getOneById($params->city_id);

          if ($city) {
              $city_clinic = ModelManagerFactory::getByName('clinic')->getOneByCityId($params->city_id);
              $cityName = $city->name;
              if ($city->service_flag == 0 || (($city->service_flag == 1) && (!$city_clinic))) {
                  $is_empty_city = 1;
              }
          } else {
              $is_empty_city = 1;
              $cityName = '';
          }

          if ($is_empty_city) {
              return "У нас пока нет клиник в городе {$cityName}. Мы сообщим, как только они появятся!";
          } else {
              return 'По Вашему запросу ничего не найдено. Возможно Вам подойдёт одна из клиник в нашей базе';
          }
      }

      return '';
  }


    /**
     * @param ClinicSearchParams $params
     * @return false|GeoPoint|null
     */
  private function getGeoPointBySearchParams(ClinicSearchParams $params)
  {
      if (!empty($params->street_id)) {
          $geoPoint = GeoHelper::getGeoPointByStreetId($params->street_id);
      } elseif (!empty($params->region_id)) {
          $geoPoint = GeoHelper::getGeoPointByRegionId($params->region_id);
      } elseif (!empty($params->district_id)) {
          $geoPoint = GeoHelper::getGeoPointByDistrictId($params->district_id);
      } elseif (!empty($params->city_id)) {
          $geoPoint = GeoHelper::getGeoPointByCityId($params->city_id);
      } else {
          $geoPoint = null;
      }

      return $geoPoint;
  }

  private function processedClinicItem(ClinicModel $clinic, ClinicSearchParams $params, $specialization = NULL)
  {
    $additional_params = array();

    foreach ($clinic->types AS $type) {
      $id = $type->getId();

      switch ($id) {
        case 5: {
          $additional_params['multidisciplinary'] = 1;
          break;
        }
        case 11: {
          $additional_params['accepts-children'] = 1;
          break;
        }
        case 28: {
          $additional_params['twenty-four-hours'] = 1;
          break;
        }
      }
    }

    foreach ($clinic->features AS $feature) {
      if ($feature->getId() == 14) {
        $additional_params['have-ramp'] = 1;
        break;
      }
    }

    foreach ($clinic->services AS $service) {
      if ($service->getId() == 1) {
        $additional_params['medical-certificates'] = 1;
        break;
      }
    }

    foreach ($clinic->doctors AS $doctor) {
      if ($doctor->is_leave_the_house) {
        $additional_params['leave-the-house'] = 1;
        break;
      }
    }

    if ($clinic->only_adult) {
      $additional_params['accepts-children'] = 0;
    }

    if ($clinic->is_card_pay) {
      $additional_params['payment-cards'] = 1;
    }

    if ($params->specialization_id) {
      $params = clone $params;

      $doctor_search_algorithm = new DoctorSearchAlgorithm();
      $doctor_params = new DoctorSearchParams();

      $specialty_manager = ModelManagerFactory::getByName('specialty');
      $main_specialty = $specialty_manager->getMainOneBySpecializationId($params->specialization_id);

      if (!empty($main_specialty)) {
        $doctor_params->page = NULL;
        $doctor_params->by_page = NULL;
        $doctor_params->specialty_id = $main_specialty->getId();
        $doctor_params->clinic_id = $clinic->getId();

        $search_result = $doctor_search_algorithm->search($doctor_params);

        $additional_params['doctors_main_specialty']['doctors'] = $search_result;
        $additional_params['doctors_main_specialty']['count'] = count($search_result);

      }
    }

    if (empty($specialization)) {
      $additional_params['doctors_main_specialty']['total_doctors'] = $clinic->doctors;
      $additional_params['doctors_main_specialty']['total_specializations'] = $clinic->specializations;
    }

    $clinic->additional_params = $additional_params;

    return $clinic;
  }

  public function ajaxGetDoctorsList()
  {
    $this->layout = 'ajax';

    $clinic_id = $this->request('clinic_id', 0);
    $specialty_id = $this->request('specialty_id', 0);
    $purpose_of_visit_id = $this->request('purpose_of_visit_id', 0);
    $time_of_visit = $this->request('time_of_visit', '');

    $exclude_ids = $this->request('exclude_ids', []);

    $page = $this->request('page');

    $search_params = new SearchParams();

    if ($clinic_id) {
      $search_params->addJoin('doctor_specialty_to_clinic');
      $params = array(
        'doctor_specialty_to_clinic.clinic_id' => $clinic_id,
        'doctor_specialty_to_clinic.is_to_delete' => NULL,
      );
      $search_params->setParamsList($params);
      //$search_params->addParam('doctor_specialty_to_clinic.clinic_id', $clinic_id);

      $clinic_manager = new ClinicManager();
      $clinic = $clinic_manager->getOneById($clinic_id);
      $this->view->clinic = $clinic;
    }

    if ($specialty_id) {
      $specialty_manager = new SpecialtyManager();
      $specialty = $specialty_manager->getOneById($specialty_id);

      $specialty_id_list = array();

      $specialty_id_list[] = $specialty->getId();

      // Убрал, чтобы не выводило дочерние specialty
      /*if ($specialty->childs) {
          $search_params->addSortParam('doctor_specialty_to_clinic.specialty_id', array($specialty->getId()), 'DESC');
          foreach($specialty->childs as $child_specialty)
          {
              $specialty_id_list[] = $child_specialty->getId();
          }
      }*/

      $search_params->addJoin('doctor_specialty_to_clinic');
      $search_params->addParam('doctor_specialty_to_clinic.specialty_id IN', $specialty_id_list);
    }

    if ($purpose_of_visit_id) {
      $search_params->addJoin('purpose_of_visit_to_doctor');
      $search_params->addParam('purpose_of_visit_to_doctor.purpose_of_visit_id', $purpose_of_visit_id);
    }

    if ($time_of_visit == 'morning') {
      $search_params->addParam('is_has_morning_time', 1);
    } else if ($time_of_visit == 'evening') {
      $search_params->addParam('is_has_evening_time', 1);
    } else if ($time_of_visit == 'weekend') {
      $search_params->addParam('is_has_weekend_time', 1);
    } else if ($time_of_visit == 'leave_house') {
      $search_params->addParam('is_leave_the_house', 1);
    }

    if(count($exclude_ids)) {
      $search_params->addParam('id NOT IN', $exclude_ids);
    }
    $search_params->addParam('is_active', 1);

    $search_params->setGetExtraEntry();


    if ($page == 1) {
      $search_params->setOffsetAndLimit(0, 10);
    } else {
      $offset = 10 + ($page - 2) * 10;
      $search_params->setOffsetAndLimit($offset, 10);
    }


    $search_params->addJoin('specialty', '`specialty`.id', '`doctor_specialty_to_clinic`.specialty_id');

    $search_params->addSortParam('rate', 'DESC');
    $search_params->addSortParam('specialty.name', 'ASC');

    $doctor_manager = new DoctorManager();
    $doctors = $doctor_manager->getListBySearchParams_with_shuffle(
        $search_params,
        crc32(session_id())
    );

    /*$specialization_manager = new SpecializationManager();
    $specializations = $specialization_manager->getListByClinicId($clinic_id);
    $doctor_specializations = $specialization_manager->getSpecializationsForClinicWithDoctors($clinic_id);*/

    $count = count($doctors);

    if ($count == 0) JsonResponse::error(2);

    if ($count == 11) {
      unset($doctors[10]);
      $more_button = TRUE;
    } else {
      $more_button = FALSE;
    }

    $doctors = DoctorPriceHelper::getPricesForDoctor($doctors, $clinic_id, $specialty_id);

    $this->view->doctors = $doctors;

    $this->view->specialty_id = $specialty_id;
    $this->view->specialtyIDForDoctorCard = $specialty_id;
    $this->view->purpose_of_visit_id = $purpose_of_visit_id;

    $html = $this->renderInString('doctor/card_big_list');
    JsonResponse::result(array(
      'html' => $html,
      'count' => $count,
      'more_button' => $more_button
    ));
  }

  function ajaxGetReviewsList()
  {
    $this->layout = 'ajax';

    $clinic_id = $this->request('clinic_id', 0);
    $page = $this->request('page', 1);

    $clinic_review_manager = new ClinicReviewManager();
    $clinic_manager = new ClinicManager();

    $offset = 4 + ($page - 2) * 10;
    $reviews = $clinic_review_manager->getConfirmedListByClinicIdWithPagging($clinic_id, $offset, 10);

    $clinic = $clinic_manager->getOneById($clinic_id);

    $this->view->clinic = $clinic;
    $this->view->reviews = $reviews;

    $count = count($reviews);
    $html = $this->renderInString('blocks/reviews-list');

    JsonResponse::result(array(
      'html' => $html,
      'count' => $count
    ));
  }

    private function getClinicPageTitle($specialty = null)
    {
        $specialtyName = "";
        if (!is_integer($specialty)) {
            if (get_class($specialty) == 'ClinicServicesModel') {
                if ($specialty->plural_name) {
                    $specialtyName = $specialty->name;
                }
            } else if (get_class($specialty) == 'ClinicTypeModel') {
                if ($specialty->genitive_name) {
                    $specialtyName = $specialty->name;
                }
            }
        }
        if (is_object($specialty) && get_class($specialty) == 'SpecialtyModel' && $specialty->id) {
            $specialty_id = $specialty->id;

            /**
             * @var SpecializationManager $specialization_manager
             */
            $specialization_manager = ModelManagerFactory::getByName('specialization');
            $adj = $specialization_manager->getAdjectiveNameBySpecialtyId($specialty_id);

            if ($adj) {
                return StringHelper::startProposalWord($adj) . ' – медицинские центры и клиники в Москве. Запись на прием онлайн, фото, цены, отзывы – ' . SITE_NAME . '';
            }
        }

        if (is_object($specialty) && get_class($specialty) == 'SpecializationModel' && $specialty->id) {
            if ($specialty->adjective_name) {
                return StringHelper::startProposalWord($specialty->adjective_name) . ' – медицинские центры и клиники в Москве. Запись на прием онлайн, фото, цены, отзывы – ' . SITE_NAME . '';
            }
        }

        if ($this->view->clinic != null) {
            return SeoTextViewHelper::GetClinicSeoTitle($this->view->clinic);
        } else {
            $seo_specialization = $this->view->specialization ? $this->view->specialization->name : null;
            if (!$seo_specialization && $specialty) {
                $seo_specialization = $specialty->name;
            }

            if ($seo_specialization) {
                $specialtyName = StringHelper::upperCaseFirstSymbol($seo_specialization);
            }
        }

        if (empty($specialtyName)) {
            return 'Медицинские центры и клиники ' . $this->getSeoAddress() . ': цены, отзывы, рейтинги и запись на прием на ' . SITE_NAME;
        } else {
            return $specialtyName . ' – медицинские центры и клиники ' . $this->getSeoAddress() . '. Запись на прием онлайн, фото, цены, отзывы – ' . SITE_NAME . '.';
        }
    }

    public function getSeoAddress()
    {
        if ($this->view->metro_station) {
            return SeoTextViewHelper::getAddressObjectName($this->view->metro_station);
        } elseif ($this->view->street) {
            return SeoTextViewHelper::getAddressObjectName($this->view->street);
        } elseif ($this->view->region) {
            return SeoTextViewHelper::getAddressObjectName($this->view->region);
        } elseif ($this->view->district) {
            return SeoTextViewHelper::getAddressObjectName($this->view->district);
        }

        return SeoTextViewHelper::getAddressObjectName($this->view->city);
    }

  private function getLandingPageItem($landing_page_alias)
  {
    $clinic_services_manager = ModelManagerFactory::getByName('clinic_services');
    $service = $clinic_services_manager->getItemByAlias($landing_page_alias);

    $clinic_type_manager = ModelManagerFactory::getByName('clinic_type');
    $type = $clinic_type_manager->getItemByAlias($landing_page_alias);

    $result_item = $service ? $service : ($type ? $type : array());
    if (empty($result_item) || !$result_item->perceived_as_page) return array();
    else return $result_item;
  }

  private function getMixedArrayConsistingOfServicesAndTypes()
  {
    $clinic_services_manager = ModelManagerFactory::getByName('clinic_services');
    $clinic_type_manager = ModelManagerFactory::getByName('clinic_type');

    $services = $clinic_services_manager->getList();
    $types = $clinic_type_manager->getList();

    $services_index_update = array();
    $types_index_update = array();

    foreach ($services AS $sValue) {
      if ($sValue->alias && $sValue->perceived_as_page) {
        $services_index_update[] = $sValue;
      }
    }

    foreach ($types AS $tValue) {
      if ($tValue->alias && $tValue->perceived_as_page) {
        $types_index_update[] = $tValue;
      }
    }


    $mixed_array = array_merge($services_index_update, $types_index_update);

    return $mixed_array;
  }

  protected function landingPage($landing_page_alias)
  {
    $current_item = $this->getLandingPageItem($landing_page_alias);

    if (!$current_item) {
      return false;
    }

    $services_and_types = $this->getMixedArrayConsistingOfServicesAndTypes();

    $city = $this->city;
    $city_id = $city->getId();

    if ($city) {
      $address_object = $city;
      $this->view->address_object = $address_object;
    }

    $district_manager = new DistrictManager();
    $district = NULL;
    $district_alias = $this->request('district');

    if ($district_alias) {
      $district = $district_manager->getOneByAlias($district_alias);
      if (!$district || $district->city_id != $city->getId()) ErrorPageViewHelper::page404('404');
    }

    $region_manager = new RegionManager();
    $region = NULL;
    $region_alias = $this->request('region');

    if ($region_alias) {
      $region = $region_manager->getOneByAlias($region_alias);

      if (!$region || $region->district_id != $district->getId()) ErrorPageViewHelper::page404();
    }

    $street_manager = new StreetManager();
    $street = NULL;
    $street_alias = $this->request('street');

    if ($street_alias) {
      $street = $street_manager->getOneByAlias($street_alias);

      if (!$street || !$street->isBelongToDistrict($district->getId())) ErrorPageViewHelper::page404();
    }

    $metro_station_manager = new MetroStationManager();
    $metro_station = NULL;
    $metro_alias = $this->request('metro');

    if ($metro_alias) {
      $metro_station = $metro_station_manager->getOneByAlias($metro_alias);

      if (!$metro_station || ($metro_station->region_id != $region->getId())) ErrorPageViewHelper::page404();
    }

    $region_street_alias = $this->request('region_street');

    if ($region_street_alias) {
      $region = $region_manager->getOneByAlias($region_street_alias);

      if (!$region) $street = $street_manager->getOneByAlias($region_street_alias);

      if (!$region && !$street) ErrorPageViewHelper::page404();

      if ($region && ($region->district_id != $district->getId())) ErrorPageViewHelper::page404();

      if ($street && (!$street->isBelongToDistrict($district->getId()))) ErrorPageViewHelper::page404();
    }

    $this->view->district = $district;
    $this->view->region = $region;
    $this->view->street = $street;
    $this->view->metro_station = $metro_station;

    if ($current_item || $city || $street_alias || $region_alias || $region_street_alias || $district_alias) {

      $this->view->is_seo_page = 1;

      $address_object = NULL;

      if ($metro_station) {
        $address_object = $metro_station;
      } elseif ($street) {
        $address_object = $street;
      } elseif ($region) {
        $address_object = $region;
      } elseif ($district) {
        $address_object = $district;
      } elseif ($city) {
        $address_object = $city;
      }
    }

    $search_page_description = '';
    if ((!empty($district) || !empty($region) || !empty($metro_station) || !empty($street)) && isset($address_object) && $address_object
    ) {
      $search_page_description = SeoTextViewHelper::getClinicPageDescription($current_item, $address_object);
    }
    $specialty_manager = ModelManagerFactory::getByName('specialty');
    $specialization_manager = ModelManagerFactory::getByName('specialization');

    $page_title = $this->getClinicPageTitle($current_item);

    $this->view->district = $district;
    $this->view->region = $region;
    $this->view->street = $street;
    $this->view->metro_station = $metro_station;
    $this->view->specialties = $specialty_manager->getHavingDoctorsListByCityId($city_id);
    $this->view->specializations = $specialization_manager->getSpecializationForCityIDInWhichHaveDoctors($city_id);
    $this->view->services_and_types = $services_and_types;
    $this->view->menu_active = 'clinic';
    $this->view->load_map = TRUE;
    $this->view->landing_page = TRUE;
    $this->view->page_title = $page_title;
    $this->view->page_description = $page_title;
    $this->view->canonical_link = '/clinic';
    $this->view->page_type = 'clinic';
    $this->view->is_seo_page = 1;
    $this->view->current_item = $current_item;
    $this->view->search_page_description = $search_page_description;
    $this->view->getSpecializationsGroups = [$specialization_manager, 'getActiveSpecializationsByCity'];
    $this->view->city = $city;
    $this->view->city_id = $city_id;
    $this->view->specialization = $this->specialization;

    $this->render('clinic/search');

    return true;
  }

  protected function clinic($clinicAlias, $originalAlias = false)
  {
      $specializationManager = ModelManagerFactory::getByName('specialization');

      /** @var ClinicManager $clinicManager */
      $clinicManager = ModelManagerFactory::getByName('clinic');
      if ($originalAlias && $clinicManager->getOneByOriginalAlias($originalAlias)) {
          $clinicAlias = "$clinicAlias/$originalAlias";
      }

      /** @var ClinicModel $clinic */
      $clinic = $clinicManager->getOneByIdOrAlias($clinicAlias);
      if ($clinic && !$clinic->is_active) {
          if (time() - $clinic->is_active_updated_at > ClinicModel::INACTIVE_MAX_TIME) {
              RedirectManager::redirect301(LinkHelper::getSiteUrlByCity($this->city) . '/clinic');
          } else {
              ErrorPageViewHelper::page404();
          }
      }

      $clinic = $clinicManager->getOneByIdOrAliasAndIsActive($clinicAlias);

      if (!$clinic) {
          $clinic = $clinicManager->getOneByOldAlias($clinicAlias);
          if ($clinic) {
              RedirectManager::redirect301(ClinicPageLinkViewHelper::getLink($clinic));
          }
      }

      if (strtolower($clinicAlias) !== $clinicAlias) {
          RedirectManager::redirect301(ClinicPageLinkViewHelper::getLink($clinic));
      }

      if ($clinic) {
          $this->view->topPhone = $clinic->top_phone;
          $this->view->topPhoneText = $clinic->top_phone_text;
      }

      if ($clinic && $clinic->isPrimaryClinic()){
//          $this->view->page_title = $clinic->name.' - врачи, отзывы, цены, телефоны и адреса, запись на прием на '.SITE_NAME;
          $this->view->page_title = $this->getClinicPageTitle($this->specialization);
          $this->view->page_description=$this->getClinicPageDescription();
          $this->view->clinic = $clinic;
          //$this->view->clinic_reviews = [];
          $this->render('clinic/primary');
      }

      if (is_numeric($clinicAlias) && $clinic->alias) {
          RedirectManager::redirect301(ClinicPageLinkViewHelper::getLink($clinic));
      }

      if (!$clinic && strpos($clinicAlias,'/') === false && $original_clinic = $clinicManager->getOneByOriginalAlias($clinicAlias)){
          RedirectManager::redirect301(ClinicPageLinkViewHelper::getLink($original_clinic));
      }


      LinkHelper::checkLinkIsCorrectIfThereIsNoAttemptRedirect($clinic, array('city' => $this->city, 'model' => 'clinic'));

      $texts = array(
          'about' => $clinic->about
      );

      if (!empty($texts) && count($texts) > 0) {
          foreach ($texts AS $tKey => $tValue) {

              preg_match_all("|<iframe(.*)/>|U", $tValue, $out, PREG_PATTERN_ORDER);
              if (count($out[0])) {
                  $replace = array();
                  foreach ($out[0] AS $oValue) $replace[] = substr($oValue, 0, strlen($oValue) - 2) . '></iframe>';
                  $text = str_replace($out[0], $replace, $tValue);
                  $clinic->$tKey = $text;
              }
          }
      }

      $this->view->clinic = $clinic;
      $this->view->clinic_id = $clinicAlias;

      $pervoe_predlozhenie = '';
      if (preg_match('$\s*?([A-ZА-ЯЁ].*?\.)$', strip_tags($clinic->about), $a))
          $pervoe_predlozhenie = $a[1];

//      $this->view->page_description = $pervoe_predlozhenie;
      $this->view->page_title = $this->getClinicPageTitle();
      $this->view->page_description = $this->getClinicPageDescription();

      $specialty_manager = new SpecialtyManager();

      $this->view->specialties = $specialty_manager->getSpecialtyListForClinic($clinic->getId());
      $this->view->actions = (new ActionManager())->getListForClinic($clinic->getId());


      $clinic_review_manager = new ClinicReviewManager();
      $clinic_rewies = $clinic_review_manager->getConfirmedListByClinicIdWithPagging($clinic->getId(), 0, 4);
      $this->view->clinic_reviews = $clinic_rewies;

      $all_reviews = $clinic_review_manager->getConfirmedListByClinicId($clinic->getId());
      $this->view->all_reviews = count($all_reviews);

      $purposes_manager = ModelManagerFactory::getByName('purpose_of_visit');
      $purposes = array();
      $purposes[] = $purposes_manager->getOneByName('Первичный прием');
      $purposes[] = $purposes_manager->getOneByName('Повторный прием');
      $this->view->purposes = $purposes;

      $spzn_id = $this->request('spzn_id', 0);

      if ($spzn_id && empty($specialization)) {
          $specialty_manager = ModelManagerFactory::getByName('specialty');

          $specialization = $specializationManager->getOneByIdOrAlias($spzn_id);

          $main_specialty = $specialty_manager->getMainOneBySpecializationId($specialization->getId());
          $this->view->main_specialty = $main_specialty;
      }

      $this->clinicServices($clinic);
      $this->registerStyles();
      return true;
  }

    private function getClinicPageH1($specialization)
    {
        if ($this->view->clinic != null) {
            return ''; // TODO
        } else {
            if (empty($specialization)) {
                return 'Медицинские центры и клиники ' . $this->getSeoAddress();
            } else {
                return StringHelper::upperCaseFirstSymbol($specialization) . '  – клиники и центры';
            }
        }
    }

    private function registerStyles()
    {
        if (debug) {
            $url = '/media/css/'.CSS_DIR.'/clinic/new.css?rnd='.RELEASE__NUMBER;
        } else {
            $url = '/media/css/min/clinic-new.css?rnd='.RELEASE__NUMBER;
        }
        $style = new Style($url);
        $style->setType(STYLE::TYPE_LAZY);
        $this->view->registerStyle($style);
        $this->view->setLayoutParam('isTopLite', true);
    }

    /**
     * @param ClinicModel $clinic
     */
    private function clinicServices($clinic)
    {
        if ($clinic) {
            /** @var ServiceToClinicManager $serviceToClinicManager */
            $serviceToClinicManager = ModelManagerFactory::getByName('service_to_clinic');
            $serviceList = $serviceToClinicManager->getListByClinicId($clinic->id);
            $tree = $this->servicesModel()->getTree();
            $this->proccessServiceTree($tree, $serviceList);
            if (!empty($tree)) {
                $this->view->serviceTree = $tree;
            }
        }
    }

    /**
     * @param array $tree
     * @param ServiceToClinicModel[] $serviceList
     */
    private function proccessServiceTree(array &$tree, array $serviceList)
    {
        foreach ($tree as $id => &$item) {
            if (!empty($item['subslugs'])) {
                $this->proccessServiceTree($item['subslugs'], $serviceList);
            }
            if (isset($serviceList[$id])) {
                $item['min_price'] = $serviceList[$id]->price;
            } else {
                if (empty($item['subslugs'])) {
                    unset($tree[$id]);
                } else {
                    $item['min_price'] = null;
                }
            }
        }
        unset($item);
    }

    /** @return ServiceCategorySimpleModel */
    private function servicesModel() {

        static $model = null;

        if(is_null($model)){
            require_once ABS_ROOT.'/application/models/service.category.simplemodel.php';
            $model = new ServiceCategorySimpleModel();
            $model->setCityID($this->city->id);
        }

        return $model;
    }
}
