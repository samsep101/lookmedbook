<?php
    class ManageRegistryController extends BaseController
    {
        public function __construct()
        {
            parent::__construct();

            $this->layout = 'registry';

            if (!Acl::isAuthed(RoleModel::ACCOUNT_SUPER_MANAGER)
				&& !Acl::isAuthed(RoleModel::FREELANCE_MANAGER)
				&& !Acl::isAuthed(RoleModel::ACCOUNT_MANAGER)
				)
                RedirectManager::redirect('/admin');
        }

		/**
		 * Метод для главной страницы менедежар
		 */
		public function index()
        {
			$moderate_page_manager = new ModeratePageManager();
	        $updates = $moderate_page_manager->getTheUpdatedPagesByUserIdWithLimit(Acl::userId(), 0, 10);
	        $this->view->updates = $updates;

            $doctor_manager = new DoctorManager();
            $doctors = $doctor_manager->getUnboundedListWithPagingByRegistryUserId(Acl::userId(), $this->registry_city);
            $this->view->doctors = $doctors;

            $clinic_manager = new ClinicManager();

            $region_params = new ClinicSearchParams();
            $region_params->is_region = 1;
            $region_params->page = 1;
            $region_params->by_page = 5;
            $region_params->registry_user_id =  Acl::userId();

            if (Acl::userRole() == RoleModel::FREELANCE_MANAGER)
            {
                $region_params->sort_by = 'raw_clinics_in_start';
            }

            $region_clinics = $clinic_manager->getListByClinicSearchParams($region_params);

            $clinic_search_params = new ClinicSearchParams();
            $clinic_search_params->registry_user_id = Acl::userId();
            $clinic_search_params->city_id = $this->registry_city;
            $clinic_search_params->page = 1;
            $clinic_search_params->by_page = 5;
            $clinics = $clinic_manager->getListByClinicSearchParams($clinic_search_params);

            $this->view->clinics = $clinics;
            $this->view->region_clinics = $region_clinics;

            /**
             * @var CityManager $city_manager
             * @var CityModel[] $cities
             */
            $city_manager = ModelManagerFactory::getByName('city');

            if (Acl::userRole() == RoleModel::ACCOUNT_SUPER_MANAGER) {
                $cities = $city_manager->getListWithClinics();
            } else {
                $cities = $city_manager->getListWithClinicsByRegistryUserId(Acl::userId());
            }
            $this->view->cities = $cities;
            $this->view->registry_city = $this->registry_city;
            $this->view->instance = 'doctor';
            $this->view->page_name = 'main_page';
        }

		/**
		 * Метод для страницы обновлений
		 */
		public function updates()
	    {
		    $by_page = 10;
			$page = $this->request('page', 1);

		    $moderate_page_manager = new ModeratePageManager();
		    $updates = $moderate_page_manager->getTheUpdatedPagesByUserIdWithLimit(($page - 1) * $by_page, $by_page);

		    $total_count = Db::getCountWithoutLimit();

		    $this->view->updates = $updates;
		    $this->view->pages = ($total_count % $by_page) ? (int)($total_count / $by_page) + 1 : $total_count / $by_page;
	    }

		/**
		 * Метод для страницы статусов
		 */
		public function statuses()
	    {
			$moderate_page_manager = new ModeratePageManager();

		    $update_params = new ModeratePageSearchParams();
		    $update_params->limit = 6;
		    $update_params->offset = 0;
		    $update_params->moderate_status_id = ModerateStatusModel::MODERATE;
		    $updates = $moderate_page_manager->getTheUpdatedPagesByModeratePageSearchParams($update_params);

		    $update_params = new ModeratePageSearchParams();
		    $update_params->limit = 6;
		    $update_params->offset = 0;
		    $update_params->moderate_status_id = ModerateStatusModel::EDIT;
		    $edit_pages = $moderate_page_manager->getTheUpdatedPagesByModeratePageSearchParams($update_params);

		    $update_params = new ModeratePageSearchParams();
		    $update_params->limit = 6;
		    $update_params->offset = 0;
		    $update_params->moderate_status_id = ModerateStatusModel::SENT_BACK;
		    $sent_back_pages = $moderate_page_manager->getTheUpdatedPagesByModeratePageSearchParams($update_params);

			$this->view->updates = $updates;
		    $this->view->edit_pages = $edit_pages;
		    $this->view->sent_back_pages = $sent_back_pages;
	    }

		/**
		 * Метод для управления страницами, которые требуют проверки
		 */
		public function moderate_pages()
	    {
		    $page = $this->request('page', 1);
		    $clinic_name = $this->request('clinic_name', '');
		    $moderate_status_id = $this->request('moderate_status_id', '');

		    $by_page = 40;

		    $moderate_page_manager = new ModeratePageManager();

		    $params = new ModeratePageSearchParams();
		    $params->limit = $by_page;
		    $params->offset = ($page - 1) * $by_page;
		    $params->clinic_name = $clinic_name;
		    $params->moderate_status_id = $moderate_status_id;

		    $this->view->moderate_status_id = $moderate_status_id;

		    $this->view->current_page = $page;

		    $this->view->page_url = '/registry/manage/moderate_pages';
		    $this->view->clinic_name = $clinic_name;

		    $sent_back_pages = $moderate_page_manager->getTheUpdatedPagesByModeratePageSearchParams($params);
		    $total_count = Db::getCountWithoutLimit();

		    $this->view->pages_total = ($total_count % $by_page) ? (int)($total_count / $by_page) + 1 : $total_count / $by_page;
		    $this->view->pages = $sent_back_pages;
		    $this->render('registry/manage/pages_list');
	    }

		/**
		 * Метод для страницы со списком врачей
		 */
		public function doctors()
        {
            $doctor_manager = new DoctorManager();

            $query = $this->request('query');
            $by_page = 10;
            $page = $this->request('page', 1);


            /**
             * @var CityManager $city_manager
             * @var CityModel[] $cities
             */
            $city_manager = ModelManagerFactory::getByName('city');
            if (Acl::userRole() == RoleModel::ACCOUNT_SUPER_MANAGER) {
                $cities = $city_manager->getListWithDoctors();
            } else {
                $cities = $city_manager->getListWithDoctorsByRegistryUserId(Acl::userId());
            }
            $in_list_city = 0;

            foreach ($cities as $city) {
                if ($city->getId() == $this->registry_city || $this->registry_city == 100000) {
                    $in_list_city = 1;
                    break;
                }
            }

            if (!$in_list_city) {
                $this->registry_city = 0;
                setcookie('city_id', 0, time() + 60 * 60 * 24 * 365, '/');
            }

            $this->view->query = $query;
            $this->view->page = $page;

            $this->view->current_page = $page;
            $this->view->page_url = '/registry/manage/doctors';

            Environment::set('get_total_count', true);

			$doctor_search_params = new DoctorSearchParams();
			$doctor_search_params->registry_user_id = Acl::userId();
			$doctor_search_params->doctor_name = $query;
			$doctor_search_params->page = $page;
			$doctor_search_params->by_page = $by_page;
            $doctor_search_params->not_virtual = 1;
            $doctor_search_params->is_active = null;
            $doctor_search_params->is_has_active_clinic = false;

            if ($this->registry_city && $this->registry_city != 100000) {
                $doctor_search_params->city_id = $this->registry_city;
            } else if ($this->registry_city && $this->registry_city == 100000) {
                $doctor_search_params->is_has_clinic = false;
            }

            $doctors = $doctor_manager->getListByDoctorSearchParams($doctor_search_params);
            $total_count = $doctor_manager->getTotalHits();

			$this->view->doctors = $doctors;
            $this->view->pages_total = ($total_count % $by_page) ? (int)($total_count / $by_page) + 1 : $total_count / $by_page;

            $this->view->cities = $cities;
            $this->view->registry_city = $this->registry_city;
            $this->view->instance = 'doctor';
            $this->view->page_name = 'doctors';
        }

		/**
		 * Метод для управления списком клиник
		 */
		public function clinics()
        {
            if ($this->registry_city == 100000) {
                $this->registry_city = 0;
                setcookie('city_id', 0, time() + 60 * 60 * 24 * 365, '/');
            }

            $clinic_manager = new ClinicManager();

            $query = $this->request('query');
            $by_page = 10;
            $page = $this->request('page', 1);

            $this->view->query = $query;
            $this->view->page = $page;

            $this->view->current_page = $page;
            $this->view->page_url = '/registry/manage/clinics';

            Environment::set('get_total_count', true);

			$clinic_search_params = new ClinicSearchParams();
			$clinic_search_params->page = $page;
			$clinic_search_params->by_page = $by_page;
			$clinic_search_params->clinic_name = $query;
			$clinic_search_params->registry_user_id = Acl::userId();
            $clinic_search_params->calc_found_rows = true;
            $clinic_search_params->city_id = $this->registry_city;

            $clinics = $clinic_manager->getListByClinicSearchParams($clinic_search_params);
            $this->view->clinics = $clinics;
            $total_count = $clinic_manager->getTotalHits();
            $this->view->pages_total = ($total_count % $by_page) ? (int)($total_count / $by_page) + 1 : $total_count / $by_page;

            /**
             * @var CityManager $city_manager
             * @var CityModel[] $cities
             */
            $city_manager = ModelManagerFactory::getByName('city');

            if (Acl::userRole() == RoleModel::ACCOUNT_SUPER_MANAGER) {
                $cities = $city_manager->getListWithClinics();
            } else {
                $cities = $city_manager->getListWithClinicsByRegistryUserId(Acl::userId());
            }
            $this->view->cities = $cities;
            $this->view->registry_city = $this->registry_city;
            $this->view->instance_name = 'clinic';
            $this->view->page_name = 'clinics';
        }

        public function regions()
        {
            $access_roles = array(RoleModel::ACCOUNT_MANAGER, RoleModel::ACCOUNT_SUPER_MANAGER, RoleModel::FREELANCE_MANAGER);
            if (!in_array(Acl::userRole(), $access_roles))
                ErrorPageViewHelper::page404();

            $clinic_manager = new ClinicManager();

            $freelancer_id = $this->request('registry_user_id');

            $default_status_id = 0;

            if (Acl::userRole() == RoleModel::FREELANCE_MANAGER)
                $default_status_id = ClinicStatusModel::RAW;

            $status_id = $this->request('status_id', $default_status_id);

            $registry_user_id = Acl::userId();

            $this->view->registry_user_id = $freelancer_id;

            $query = $this->request('query');
            $by_page = 20;
            $page = $this->request('page', 1);

            $this->view->query = $query;
            $this->view->page = $page;

            $this->view->current_page = $page;
            $this->view->page_url = '/registry/manage/regions';

            $city_id = $this->request->get('city_id', null);

            $this->view->city_id = $city_id;

            $clinic_search_params = new ClinicSearchParams();

            $clinic_search_params->clinic_name = $query;
            $clinic_search_params->registry_user_id = $registry_user_id;
            $clinic_search_params->freelancer_id = $freelancer_id;
            $clinic_search_params->is_region = 1;
			$clinic_search_params->get_extra_item = 1;
			$clinic_search_params->city_id = $city_id;

            if ($status_id)
            {
                $clinic_search_params->status = $status_id;
            }

            $this->view->counters = RegionCountersCache::getRegionsCountersCache(Acl::userId(), $freelancer_id, $city_id, $query);

            $this->view->status = $status_id;

            $clinic_search_params->page = $page;
            $clinic_search_params->by_page = $by_page;
            $clinic_search_params->calc_found_rows = true;

            $clinics = $clinic_manager->getListByClinicSearchParams($clinic_search_params, true);
            $total_count = $clinic_manager->getTotalHits();

            $this->view->clinics = $clinics;

            $this->view->pages_total = ($total_count % $by_page) ? (int)($total_count / $by_page) + 1 : $total_count / $by_page;

            $user_manager = new UserManager();
            $freelancers = $user_manager->getFreelancersListByAccountManagerId(Acl::userId());
            $this->view->freelancers = $freelancers;

            $this->view->menu_active = 'regions';

            /**
             * @var CityManager $city_manager
             * @var CityModel[] $cities
             */
            $city_manager = ModelManagerFactory::getByName('city');

            $cities = $city_manager->getListWithRegionsByUserId(Acl::userId());
            $this->view->cities = $cities;
        }

        public function region_check()
        {
			ini_set('memory_limit', '128M');
            if (!in_array(Acl::userRole(), array(RoleModel::ACCOUNT_MANAGER, RoleModel::ACCOUNT_SUPER_MANAGER)))
                ErrorPageViewHelper::page404();

            $clinic_manager = new ClinicManager();

            $registry_user_id = $this->request('registry_user_id');
            $this->view->registry_user_id = $registry_user_id;


            $query = $this->request('query');
            $by_page = 10;
            $page = $this->request('page', 1);

            $this->view->query = $query;
            $this->view->page = $page;

            $city_id = $this->request->get('city_id', null);
            $this->view->city_id = $city_id;

            $clinic_search_params = new ClinicSearchParams();
            $clinic_search_params->clinic_name = $query;
            $clinic_search_params->freelancer_id = $registry_user_id;
            $clinic_search_params->regions = 1;
            $clinic_search_params->city_id = $city_id;

            $this->view->counters = RegionCountersCache::getRegionsCountersCache(Acl::userId(), $registry_user_id, $city_id);

            $clinic_search_params->page = $page;
            $clinic_search_params->by_page = $by_page;
            $clinic_search_params->sort_by = 'dt_publish';

            $this->view->clinics = array();


            if ($registry_user_id)
            {
                $edit_dates = $clinic_manager->getDistinctPublishDatesByUserIdAndCityId($registry_user_id, $city_id);
            } else {
                $edit_dates = $clinic_manager->getDistinctPublishDatesByCityId($city_id);
            }

			$this->show_search_input = false;

            $this->view->edit_dates = $edit_dates;
            $this->view->menu_active = 'region_check';

            /**
             * @var CityManager $city_manager
             * @var CityModel[] $cities
             */
            $city_manager = ModelManagerFactory::getByName('city');

            $cities = $city_manager->getListWithRegionsCheckByUserId(Acl::userId());
            $this->view->cities = $cities;
        }

        public function ajaxGetUpdateClinicsList()
        {
            if (!in_array(Acl::userRole(), array(RoleModel::ACCOUNT_MANAGER, RoleModel::ACCOUNT_SUPER_MANAGER)))
                JsonResponse::error(ValidationErrorCodes::ACCESS_DENIED);

            set_time_limit(0);

            $this->layout = 'ajax';

            $date_from = $this->request('dt_start');
            $date_to = $this->request('dt_end');
            $city_id = $this->request('city_id');

            $clinic_search_params = new ClinicSearchParams();
            $clinic_search_params->publish_date_from = $date_from;
            $clinic_search_params->publish_date_to = $date_to;
            $clinic_search_params->is_region = 1;
            $clinic_search_params->registry_user_id = Acl::userId();
            $clinic_search_params->sort_by = 'dt_publish';
            $clinic_search_params->city_id = $city_id;

			/**
			 * @var ClinicManager $clinic_manager
			 */
			$clinic_manager = new ClinicManager();

            $clinic_check_date_manager = new ClinicCheckDateManager();

            $check_dates = $clinic_check_date_manager->getCheckedDaysByUserIdAndDatePeriod(Acl::userId(), $date_from, $date_to);

            /**
             * @var ClinicModel[] $clinics
             */
            $clinics = $clinic_manager->getListByClinicSearchParams($clinic_search_params);

            $result = array();

            foreach($clinics as $clinic)
            {
                $this->view->clinic = $clinic;
                $result[] = array(
                    'date_publish' => $clinic->date_publish,
                    'checked' => in_array($clinic->date_publish, $check_dates),
                    'html' => $this->renderInString('registry/manage/blocks/clinic_check_card'),
                );
            }

            JsonResponse::result($result);
        }

        public function ajaxSetCheckedDate()
        {
            $date = $this->request->post('date');

            $check_date_manager = new ClinicCheckDateManager();

            if ($check_date_manager->getOneByDateAndUserId($date, Acl::userId()))
            {
                $check_date_manager->deleteByDateAndUserId($date, Acl::userId());
                JsonResponse::result(0);
            } else {
                $check_date = new ClinicCheckDateModel();
                $check_date->date = $date;
                $check_date->user_id = Acl::userId();


                if ($check_date->save())
                {
                    JsonResponse::result(1);
                } else {
                    JsonResponse::error(ValidationErrorCodes::WRONG_DATA);
                }
            }
        }

        public function ajaxDeleteClinicById()
        {
            $clinic_id = $this->request->post('clinic_id');

            $clinic_manager = new ClinicManager();
            $clinic_manager->deleteById($clinic_id);

            JsonResponse::result();
        }
    }