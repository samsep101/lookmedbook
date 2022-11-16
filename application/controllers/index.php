<?php

class IndexController extends BaseController
{
  public function index()
  {
    if (Acc::isAuthed()) {
      $this->redirectUrl('/account');
    } else {
      $this->view->home_page = 1;
      $this->view->canonical_link = '';
    }

    /**
     * @var DistrictManager $district_manager
     * @var CityManager $city_manager
     * @var SpecialtyManager $specialty_manager
     */

    $this->view->page_title = PAGE_TITLE . $this->city->prepositional_name . ' – '.SITE_NAME.'';
    $this->view->page_description = ''.SITE_DOMAIN.' — это сервис для поиска врача и записи на прием. Также на сайте есть медицинский справочник: пользователь может найти достоверную информацию обо всех известных заболеваниях, изложенную простым и понятным языком.';

    $district_manager = ModelManagerFactory::getByName('district');
    $city_manager = ModelManagerFactory::getByName('city');
    $specialty_manager = ModelManagerFactory::getByName('specialty');
    $specialization_manager = ModelManagerFactory::getByName('specialization');
    $doctor_manager = new DoctorManager();

    $specialty = $specialty_manager->getOneByName('Терапевт');
    $city = $this->city;

    if ($city) {
      $address_object = $city;
      $this->view->address_object = $address_object;
    }

    $specialty_id = $specialty?$specialty->getId():0;
    if($specialty_id and $city) {
      $districts = $district_manager->getHavingDoctorsListBySpecialtyIdAndCityId($specialty_id, $city->getId());
    }else{
      $districts = [];
    }

    $this->view->districts = $districts;

    $this->view->specialty = $specialty;

    $moscow_city = $city_manager->getOneByName('Москва');
    $moscow_city_id = $moscow_city->getId();
    $city_id = $city->getId();
//            $specialties = $specialty_manager->getRootListToSearchDoctorsByCityId($city_id);
    $specializations = $specialization_manager->getSpecializationForCityIDInWhichHaveDoctors($moscow_city_id);
    $this->view->specializations = $specializations;

    $specialties = $specialty_manager->getHavingDoctorsListByCityId($moscow_city_id);
    $this->view->specialties = $specialties;

    $specialties_groups = SpecialtyHelper::getSpecialtiesLetterGroups($specialties, array(), 1);
    $this->view->specialties_groups = $specialties_groups;

    $doctor_search_params = new DoctorSearchParams();
    $doctor_search_params->city_id = $city_id;
    $doctor_search_params->specialty_id = $specialty_id;
    $doctor_search_params->page = 1;
    $doctor_search_params->by_page = 11;
    $doctor_search_params->sort_by = 'rand';
//            $doctor_search_params->has_avatar = 1;

    $doctors = $doctor_manager->getListByDoctorSearchParams($doctor_search_params);
    shuffle($doctors);

    $this->view->doctors = $doctors;
    $this->view->is_virtual = false;
    $this->view->specialty_id = $specialty_id;
    $this->view->search_page = 1;
    $this->view->districts = $district_manager->getListByCityId($this->city->getId());

    if (date('H:i') > date('H:i', strtotime('9:00')) && date('H:i') < date('H:i', strtotime('21:00')))
      $daytime = true;
    else
      $daytime = false;

    $this->view->daytime = $daytime;
  }

  public function about()
  {
    $this->view->big_image = SettingsManager::get('about_big_image');
    $this->view->about_us_text = SettingsManager::get('about_us_text');
    $this->view->about_our_service = SettingsManager::get('about_our_service');
    $this->view->small_img = SettingsManager::get('about_history_img');
    $this->view->about_history_img_signature = SettingsManager::get('about_history_img_signature');
    $this->view->about_history_text = SettingsManager::get('about_history_text');
    $this->view->label_for_counters = 'about';
  }
}