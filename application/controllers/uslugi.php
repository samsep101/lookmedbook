<?php

require_once ABS_ROOT.'/core/funcs/string.helpers.php';
require_once ABS_ROOT.'/core/classes/SnippetPagination.php';

class Uslugi_SeoController extends BaseController {
    /** @author Playmore 2017 (playmoredevelop@gmail.com) */

    use \app\library\traits\DoctorSearchTrait;

    protected $slug = false;
    protected $category_slug = false;
    protected $metro = false;
    protected $district = false;
    protected $area = false;
    protected $street = false;


    protected $current = [
        'name' => '',
        'genitive_name' => ''
    ];

    protected $category = [
        'name' => '',
        'genitive_name' => ''
    ];

    protected $seo_method = 'seo_index';

    /** @var View */
    public $view;

    protected function replace_seo($str) {

        $replace = [
            '%city%' => $this->city->prepositional_name,
            '%usluga-spec%' => $this->category['genitive_name'],
            '%usluga-name%' => $this->category['name'],
            '%service-name%' => $this->current['name'],
            '%service-name-genitive%' => $this->current['genitive_name'],
        ];

        return str_replace(array_keys($replace), array_values($replace), $str);

    }

    protected function seo_index() {

        # Медицинские услуги в %city%
        $this->view->h1 = $this->replace_seo('Медицинские услуги в %city%');
        # Медицинские услуги в %city% - цены клиник с отзывами, рейтингами и записью на прием на Lookmedbook
        $this->view->page_title = $this->replace_seo('Медицинские услуги в %city% - цены клиник с отзывами, рейтингами и записью на прием на Lookmedbook.');
        # Интересуют медицинские услуги в Москве? Loomedbook поможет выбрать среди лучших клиник и медицинских центров по отзывам, рейтингу и стоимости. Заходите и записывайтесь!
        $this->view->page_description = $this->replace_seo('Интересуют медицинские услуги в %city%? Loomedbook поможет выбрать среди лучших клиник и медицинских центров по отзывам, рейтингу и стоимости. Заходите и записывайтесь!');

        $this->view->breadcrumbs = [
            ['Главная', '/', 'home'],
            ['Все услуги', '/uslugi', '']
        ];
    }

    protected function seo_slug() {
        if ($this->category['name'] != 'Услуги по ОМС') {
            $this->view->h1 = $this->replace_seo('Медицинские услуги в области %usluga-spec%');
            $this->view->h2 = $this->replace_seo('Услуги в области %usluga-spec%');
            $this->view->page_description = $this->replace_seo('Интересуют медицинские услуги в области %usluga-spec% в %city%? Loomedbook поможет выбрать среди лучших клиник и медицинских центров по отзывам, рейтингу и стоимости.');
        } else {
            $this->view->h1 = $this->replace_seo('%usluga-name%');
            $this->view->h2 = $this->replace_seo('%usluga-name%');
            $this->view->page_description = $this->replace_seo('Интересуют %usluga-name% в %city%? Loomedbook поможет выбрать среди лучших клиник и медицинских центров по отзывам, рейтингу и стоимости.');
        }
        $this->view->page_title = $this->replace_seo('%usluga-name% в %city% - цены клиник с отзывами, рейтингами и записью на прием на Lookmedbook.');

        $this->view->breadcrumbs = [
            ['Главная', '/', 'home'],
            ['Все услуги', '/uslugi', ''],
            [$this->category['name'], false, '']
        ];
    }

    protected function seo_service() {
        if ($this->current['service_type'] === 'diagnostic') {
            $this->view->h1 = $this->current['name'];
            $this->view->h2 = $this->replace_seo('Услуги %service-name%');
            $this->view->page_title = $this->replace_seo('%service-name% в %city%, низкие цены, отзывы о клиниках');
            $this->view->page_description = $this->replace_seo('%service-name% в %city% - недорого, запись на прием онлайн!');
        } else {
            $this->view->h1 = $this->current['name'];
            $this->view->h2 = $this->replace_seo('Услуги %service-name%');
            $this->view->page_title = $this->replace_seo('%service-name% в %city% - цены клиник с отзывами, рейтингами и записью на прием на Lookmedbook.');
            $this->view->page_description = $this->replace_seo('Интересует %service-name% в %city%? Loomedbook поможет выбрать среди лучших клиник и медицинских центров по отзывам, рейтингу и стоимости.');
        }

        $this->view->breadcrumbs = [
            ['Главная', '/', 'home'],
            ['Все услуги', '/uslugi', ''],
            [$this->category['name'], '/uslugi/'.$this->category_slug, ''],
            [$this->current['name'], false, ''],
        ];
    }

    public function afterAction() {

        if(method_exists($this, $this->seo_method)){

            return $this->{$this->seo_method}();
        }

        return $this->seo_index();
    }
}

/* END CLASS: SeoUslugi extends BaseController */

class UslugiController extends Uslugi_SeoController {
    /** @author Playmore 2017 (playmoredevelop@gmail.com) */

    //public $layout = 'blocks';
    public $template = 'index';

    protected $id = false;
    protected $parent_id = 0;
    /** @var ClinicManager */
    protected $clinic_manager = false;

    /** @var ServiceToClinicManager */
    protected $service_clinic_manager = false;

    protected $container = [];

    public function __construct() {

        parent::__construct();

        if(false === Application::config('section.services.available')){
            ErrorPageViewHelper::page404('404');
            exit();
        }
    }

    public function beforeAction()
    {
        parent::beforeAction();

        $this->clinic_manager = ModelManagerFactory::getByName('clinic');
        $this->clinic_manager->setCityID($this->city->id);

        $this->service_clinic_manager = ModelManagerFactory::getByName('service_to_clinic');
    }

    /** @return ServiceCategorySimpleModel */
    public function services_model() {

        static $model = null;

        if(is_null($model)){
            require_once ABS_ROOT.'/application/models/service.category.simplemodel.php';
            $model = new ServiceCategorySimpleModel();
            $model->setCityID($this->city->id);
        }

        return $model;
    }

    /** @return RelationsSimpleModel */
    public function relations_model() {

        static $model = null;

        if(is_null($model)){
            require_once ABS_ROOT.'/application/models/relations.simplemodel.php';
            $model = new RelationsSimpleModel();
            $model->setCityID($this->city->id);
        }

        return $model;
    }

    public function setSegments() {

        $this->category_slug    = $this->request('cat', false);
        $this->metro            = $this->request('metro', false);
        $this->district         = $this->request('district', false);
        $this->area             = $this->request('area', false);
        $this->street           = $this->request('street', false);
        $this->slug             = $this->request('slug',false);
    }

    # /uslugi
    public function index() {

        $this->setSegments();

        $this->container['tree'] = $this->services_model()->getTree();
        $this->view->districts = $this->services_model()->getDistricts();
        $this->view->tree = $this->container['tree'];
    }
    # /uslugi/akusherstvo
    public function category() {

        $this->index();
        $this->seo_method = 'seo_slug';
        $this->view->page = 'category';

        $this->category = $this->services_model()->getByAlias($this->category_slug);
        $this->id = $this->category['id'];
        $this->parent_id = (int)$this->category['parent_id'];
        $this->view->search_category = $this->id ?: $this->parent_id;
        if(!empty($this->id)){
            if(array_key_exists($this->id, $this->container['tree']) && !$this->slug){
                $this->view->current_tree = [ $this->id => $this->container['tree'][$this->id] ];
            } elseif ($this->category['id'] && array_key_exists($this->category['id'], $this->container['tree'])) {
                $finder = function ($branch) use (&$finder) {
                    if (!empty($branch['subslugs'])) {
                        foreach ($branch['subslugs'] as $id => $element) {
                            if ($element['alias'] == $this->slug) {
                                return $element;
                            }
                            $result = $finder($element);
                            if ($result) {
                                return $result;
                            }
                        }
                    }
                    return false;
                };
                $tree = $finder($this->container['tree'][$this->category['id']]);
                if ($tree) {
                    if ($this->slug) {
                        $this->view->service = $this->services_model()->getById($tree['id']);
                        $this->id = $this->view->service['id'];
                        $this->parent_id = (int)$this->view->service['parent_id'];
                    }
                    $this->view->current_tree = [$this->id => $tree];
                } else {
                    ErrorPageViewHelper::page404('404');
                    exit();
                }
            }

            $clinics = [];

            $pagination = new SnippetPagination();

            $params = ClinicSearchHelper::initClinicSearchParams($this->request, $this->city->id);
            $params->service_categories = $this->id;
            $clinic_search_algorithm = new ClinicSearchAlgorithm();

            $clinics_count = $clinic_search_algorithm->count($params);

            $serviceId = null;
            if ($clinics_count > 0) {
                $pagination = $pagination->make($clinics_count, 12);
                $params->page = $pagination->offset / $pagination->perpage + 1;
                $params->by_page = $pagination->perpage;
                $serviceId = $this->id;
                $clinics = $clinic_search_algorithm->search($params);
            } elseif ($this->parent_id > 0) {
                $params->service_categories = $this->view->search_category = $this->parent_id;
                $clinics_count = $clinic_search_algorithm->count($params);
                $pagination = $pagination->make($clinics_count, 12);
                $params->page = $pagination->offset / $pagination->perpage + 1;
                $params->by_page = $pagination->perpage;
                $serviceId = $this->parent_id;
                $clinics = $clinic_search_algorithm->search($params);
            }

            if ($clinics_count === 0) {
                $tree = $this->view->current_tree;
                $subServiceIds = $this->getSubserviceIds(reset($tree));
                if ($subServiceIds) {
                    $params->service_categories = $subServiceIds;
                    $clinics_count = $clinic_search_algorithm->count($params);
                }
                $pagination = $pagination->make($clinics_count, 12);
                $params->page = $pagination->offset / $pagination->perpage + 1;
                $params->by_page = $pagination->perpage;
                $clinics = $clinic_search_algorithm->search($params);
            }

            $clinics = $this->processedClinics($serviceId, $clinics);

            $doctors = null;
            if (!$clinics && !empty($this->view->service['specialty_id'])) {
                $doctorSearchAlgorithm = new DoctorSearchAlgorithm();

                $doctorSearchParams = new DoctorSearchParams();
                $doctorSearchParams->not_virtual = 1;
                $doctorSearchParams->specialty_id = $this->view->service['specialty_id'];
                $doctorSearchParams->city_id = $this->city->getId();

                $doctorsCount = $doctorSearchAlgorithm->count($doctorSearchParams);
                $pagination = $pagination->make($doctorsCount, 12);
                $doctorSearchParams->page = $pagination->offset / $pagination->perpage + 1;
                $doctorSearchParams->by_page = $pagination->perpage;
                $doctors = $doctorSearchAlgorithm->search($doctorSearchParams);
            }

            $this->view->additionalBlocks = [
                'clinicInfo' => 'uslugi/blocks/clinic_info'
            ];
            $this->view->clinics = $clinics;
            $this->view->doctors = $doctors;
            $pagination->getmethod = true;
            $pagination->replaces['{text.prev}'] = '<i class="glyphicon glyphicon-chevron-left"></i>';
            $pagination->replaces['{text.next}'] = '<i class="glyphicon glyphicon-chevron-right"></i>';
            $this->view->pagination = $pagination;
            $this->view->base_url = implode('/', ['/uslugi', $this->category_slug]);

            $this->view->current_slug = $this->slug;
            $this->view->service_category = $this->category;
            if ($this->view->service_category) {
                $this->view->topPhone = $this->view->service_category['top_phone'];
                $this->view->topPhoneText = $this->view->service_category['top_phone_text'];
            }
        }

        $this->setRoots();

    }

    # /uslugi/andrologija/mar-test
    public function slug() {

        $this->category();
        $this->seo_method = 'seo_service';
        $this->view->page = 'slug';
        $this->view->category = $this->category;

        $article_slug = $this->request('slug', false);

        if(!empty($article_slug)){

            $this->view->btnback = $this->replace_seo('Услуги в области %usluga-spec%');
            $this->view->btnslug = '/uslugi/'.$this->category_slug;
            $this->view->current_slug = $this->category_slug.'/'.$article_slug;
            $this->current = $this->services_model()->getByAlias($article_slug);
            if ($this->current) {
                $this->view->topPhone = $this->current['top_phone'];
                $this->view->topPhoneText = $this->current['top_phone_text'];
                if (!empty($this->current['linked_service_id'])) {
                    $this->view->isHiddenFromRobots = true;
                }
            }
            $this->view->base_url = implode('/', ['/uslugi', $this->category_slug, $this->slug]);
            $this->blockBefore($this->category['alias'] . '-' . $this->current['alias']);
        }
    }

    # /uslugi/district-vao
    public function district() {}
    # /uslugi/area-sokolinaya-gora
    public function area() {}
    # /uslugi/metro-baumanskaya
    public function metro() {}
    # /uslugi/street-scherbakovskaya
    public function street() {}
    # /uslugi/akusherstvo/district-vao
    public function slug_district() {

        if(!empty($this->district)){

            // подготавливаем фильтры сразу в модели
            $this->services_model()->districID = 1;
        }

        // и далее вызываем метод формирования дочерней страницы
        // при этом в запросах клиник уже будут данные для фильтров
        $this->slug();
    }
    # /uslugi/akusherstvo/area-sokolinaya-gora
    public function slug_area() {}
    # /uslugi/akusherstvo/metro-baumanskaya
    public function slug_metro() {}
    # /uslugi/akusherstvo/street-scherbakovskaya
    public function slug_street() {}

    public function render() {

        $this->beforeRender();
        $this->afterAction();

        $this->view->clearscripts = true;
        $this->view->page_type = $this->action;
        $this->view->controller = $this->controller;

        $this->view->setLayout($this->layout);
        $templatePath = $this->getTemplatePath($this->template);

        return $this->view->render($templatePath);
    }

    /**
     * Блок, который отображается до карты
     */
    public function blockBefore($service)
    {
        $service = str_replace('/', '', $service);
        $this->view->block_before = "uslugi/blocks-before/$service";
    }

    private function processedClinicItem(ClinicModel $clinic) {

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

        $count_doctors = 0;
        foreach ($clinic->doctors AS $doctor) {
            $count_doctors++;
            if ($doctor->is_leave_the_house) {
                $additional_params['leave-the-house'] = 1;
                break;
            }
        }

        $clinic->total_doctors = $count_doctors;

        $clinic->total_specializations = count($clinic->specializations);

        if ($clinic->only_adult) {
            $additional_params['accepts-children'] = 0;
        }

        if ($clinic->is_card_pay) {
            $additional_params['payment-cards'] = 1;
        }

        $clinic->additional_params = $additional_params;

        return $clinic;
    }

    private function setRoots() {

        $this->container['roots'] = [];

        foreach($this->container['tree'] as $id => $one){

            if($one['count'] > 0){
                $this->container['roots'][$id] = [
                    'slug' => $one['alias'],
                    'name' => $one['name'],
                    'count' => !empty($one['count']) ? $one['count'] : 0
                ];
            }
        }

        $this->view->roots = $this->container['roots'];
    }

    public function ajaxSearchDoctors()
    {
        $this->layout = 'ajax';
        $this->view->page_type = 'doctor';

        $doctor_search_params = DoctorSearchHelper::initDoctorSearchParams($this->request, $this->city->getId());

        $serviceCategoryId = $doctor_search_params->service_categories;

        if ($serviceCategoryId) {
            $specialtyManager = new ServiceCategoryManager();
            /** @var ServiceCategoryModel $serviceCategory */
            $serviceCategory = $specialtyManager->getOneById($serviceCategoryId);
            if ($serviceCategory) {
                $doctor_search_params->specialty_id = $serviceCategory->specialty_id;
            }
        }

        $address_object = $this->ajaxSearch__address_object();
        $this->view->address_object = $address_object;

        //for primary doctors
        $doctor_search_algorithm = new DoctorSearchAlgorithm();
        if ($doctor_search_params->specialty_id) {
            $doctors = $doctor_search_algorithm->search($doctor_search_params);
        } else {
            $doctors = [];
        }
        $filter_active = 0;

        $this->view->specialty_id = $doctor_search_params->specialty_id;
        $this->view->specialtyIDForDoctorCard = isset($doctor_search_params->specialty_id) ? $doctor_search_params->specialty_id : null;
        $this->view->doctors = $doctors;
        $this->view->purpose_of_visit_id = $doctor_search_params->purpose_of_visit_id;
        $this->view->search_page = true;
        $this->view->counter_number = (int)AnalyticCounterHelper::getCounterIdByCityIdAndCounterTypeId($this->city->getId(), AnalyticCounterTypeModel::YANDEX_COUNTER);
        $this->view->is_seo_page = true;
        $this->view->noWrap = true;

        $html = $this->renderInString('doctor/card_big_list');

        $is_empty_city = 0;
        $city_doctor = NULL;
        if ($doctor_search_params->city_id and !$this->city->service_flag) {
            $is_empty_city = 1;
        }

        $get_good_search_flag = count($doctors) == 0 ? false : $doctor_search_algorithm->getGoodSearchFlag();

        $result = array(
            'html' => $html,
            'next_page' => $doctor_search_algorithm->getNextPageFlag(),
            'full_search' => $get_good_search_flag,
            'is_empty_city' => $is_empty_city,
            'city_name' => $this->city ? $this->city->name : 'Москва',
            'latitude' => $this->city ? $this->city->lat : '56,7558',
            'longitude' => $this->city ? $this->city->lng : '37,6176',
            'filter_active' => $filter_active,
            'isset_region' => ($doctor_search_params->region_id && !$doctor_search_params->street_id && !$doctor_search_params->metro_station_id) ? 1 : 0,
        );

        JsonResponse::result($result);
    }

    public function ajaxSearchClinics()
    {
        $this->layout = 'ajax';

        $params = ClinicSearchHelper::initClinicSearchParams($this->request, $this->city->id);
        $clinic_search_algorithm = new ClinicSearchAlgorithm();
        $clinics = $clinic_search_algorithm->search($params);
        $specialization_manager = ModelManagerFactory::getByName('specialization');
        $specialization = $specialization_manager->getOneById($params->specialization_id);

        $clinics = $this->processedClinics($params->service_categories, $clinics);

        $this->view->clinics = $clinics;
        $this->view->specialization = $specialization;
        $this->view->additionalBlocks = [
            'clinicInfo' => 'uslugi/blocks/clinic_info'
        ];
        $html = $this->renderInString('uslugi/blocks/card_small_list');

        $is_empty_city = 0;
        $city = ModelManagerFactory::getByName('city')->getOneById($params->city_id);
        $city_clinic = ModelManagerFactory::getByName('clinic')->getOneByCityId($params->city_id);
        if ($city->service_flag == 0 || (($city->service_flag == 1) && (!$city_clinic))) $is_empty_city = 1;

        $doctor_manager = ModelManagerFactory::getByName('clinic');
        $clinic_total_count = $doctor_manager->getCountByModelSearchCriteria($params);
        $clinic_word_form = SpecialtyHelper::getClinicWordForm($clinic_total_count);

        $result = array(
            'html' => $html,
            'next_page' => (($params->page-1)*$params->by_page+count($clinics))<$clinic_total_count?true:false,
            'full_search' => $clinic_search_algorithm->getGoodSearchFlag(),
            'is_empty_city' => $is_empty_city,
            'city_name' => $city->name,
            'clinic_total_count' => $clinic_total_count,
            'clinic_word_form' => $clinic_word_form,
        );

        JsonResponse::result($result);
    }

    protected function processedClinics($serviceId, $clinics)
    {
        $clinicIds = [];
        foreach ($clinics as $clinic) {
            $clinicIds[] = $clinic->id;
        }

        if ($clinicIds) {
            $prices = $this->service_clinic_manager->getGroupedPriceByClinicId($serviceId, $clinicIds);
            foreach ($clinics as &$clinic) {
                if (isset($prices[$clinic->id])) {
                    $clinic = $this->processedClinicItem($clinic);
                    $clinic->service_price = $prices[$clinic->id];
                }
            }
            unset($clinic);
        }

        return $clinics;
    }

    private function getSubserviceIds(array $tree)
    {
        $result = [];
        if (!empty($tree['subslugs'])) {
            foreach ($tree['subslugs'] as $key => $subslug) {
                $result[] = $key;
                $result += $this->getSubserviceIds($subslug);
            }
        }

        return $result;
    }
}

/* END CLASS: UslugiController extends BaseController */
