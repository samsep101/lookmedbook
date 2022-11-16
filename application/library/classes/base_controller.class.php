<?php
	class BaseController extends Controller
	{
		public $breadcrumbs = array('Главная' => '/');
		public $layout = 'home';
		/**
		 * @var AccountModel
		 */
		protected $current_account;
		protected $close_aboute_note_attribute;
		protected $close_settings_note_attribute;
		protected $close_review_note_attribute;
		protected $close_visits_note_attribute;

		protected $start_action_time;

		protected $start_render_time;

		/**
		 * @var CityModel
		 */
		protected $city;
        /**
         * @var CityModel
         */
        protected $registry_city;
		/**
		 * @var IHtmlCache
		 */
		protected $file_cache = null;
		/**
		 * @var bool
		 */
		protected $cache_view = false;
		/**
		 * @var string
		 */
		protected $cache_name = '';

		/**
		 * @var ProductBasket
		 */
		protected $product_basket;

		protected $csrf;

		public function getCsrf()
		{
			if(!$this->csrf)
			{
				$this->csrf = new Csrf();
			}

			return $this->csrf;
		}

		public function beforeAction()
		{
            //if($this->request->isPost() && ($_SERVER['REQUEST_URI'] != '/registry/ajax/uploadImage')
			//	&& ($_SERVER['REQUEST_URI'] != '/registry/ajax/uploadServicesFiles'))
			//{
			//	$token = $this->request->post('csrf');
			//	if(!$this->getCsrf()->checkToken($token) || !$this->getCsrf()->checkReferer())
			//	{
			//		if($this->request->isAJAX())
			//		{
			//			JsonResponse::error(ValidationErrorCodes::WRONG_CSRF_TOKEN);
			//		}
			//		else
			//		{
			//			ErrorPageViewHelper::pageCsrfError();
			//		}
			//	}
			//}

			$this->view->csrf = $this->getCsrf()->getUserToken();

			$this->city = SeoLinksHelper::getCityByPageLink();

            $this->registry_city = isset($_COOKIE["city_id"]) ? $_COOKIE["city_id"] : 0;

			// авторизация по ссылке
			if(isset($_GET['fastlogin']))
			{
				if(is_string($_GET['fastlogin']))
				{
					$fastlogin = new FastLogin();
					$fastlogin->tryToLogin($_GET['fastlogin']);
				}
			}

			if(!$this->city || !$this->city->isUsed())
			{
				ErrorPageViewHelper::page404();
			}

			$this->product_basket = ProductBasketFactory::getInstance();


			if(isset($_SERVER['HTTP_HOST']) && isset($_SERVER['REQUEST_URI']))
			{
				if(debug)
				{
					if(($_SERVER['HTTP_HOST'] != str_replace(SITE_SCHEME . '://','',SITE_URL)) && (!strpos($_SERVER['REQUEST_URI'], 'system')) && (!strpos($_SERVER['REQUEST_URI'], 'ajax') && (!strpos($_SERVER['REQUEST_URI'], 'popup')) && (!strpos($_SERVER['REQUEST_URI'], 'test')) && (!strpos($_SERVER['REQUEST_URI'], 'js/'))))
					{
						PhpHeaderHelper::status404();
					}
				}
			}


			$this->file_cache = HtmlCacheFactory::getInstance();

			$this->view->error_messages = array();

			$this->current_account = false;
			if(Acc::accountId())
			{
				$this->current_account = ModelManagerFactory::getByName('account')->getOneById(Acc::accountId());
			}

			$this->close_about_note_attribute = false;
			if(isset($_SESSION['close_about_note_attribute']) && $_SESSION['close_about_note_attribute'])
			{
				$this->close_aboute_note_attribute = $_SESSION['close_about_note_attribute'];
			}

			$this->close_settings_note_attribute = false;
			if(isset($_SESSION['close_settings_note_attribute']) && $_SESSION['close_settings_note_attribute'])
			{
				$this->close_settings_note_attribute = $_SESSION['close_settings_note_attribute'];
			}

			$this->close_review_note_attribute = false;
			if(isset($_SESSION['close_review_note_attribute']) && $_SESSION['close_review_note_attribute'])
			{
				$this->close_review_note_attribute = $_SESSION['close_review_note_attribute'];
			}

			$this->close_visits_note_attribute = false;
			if(isset($_SESSION['close_visits_note_attribute']) && $_SESSION['close_visits_note_attribute'])
			{
				$this->close_visits_note_attribute = $_SESSION['close_visits_note_attribute'];
			}

			$this->start_action_time = microtime(true);

            /* Отображение баннера в нижней части страницы */
            $showBannerOnInnerPages = array(
                'clinic',
                'doctor',
                'catalog'
            );

            $category_alias        = $this->request('category_alias', '');
            $parent_category_alias = $this->request('parent_category_alias', '');

            if(
                in_array($this->controller, $showBannerOnInnerPages) && $this->action != 'index' ||
                $this->controller == 'product' ||
                $this->controller == 'catalog' && (!empty($category_alias) || !empty($parent_category_alias))
            ) {
                $this->view->show_horizontal_banner = true;
            }
		}

		public function beforeRender()
		{
            $b_param = $this->request('b');
            if($b_param && $b_param == 'red') {
                $this->view->is_red = 1;
            }
			else if ($b_param && $b_param == 'simple') {
            	$this->view->is_simple = 1;
            }
            
			$this->view->current_account = $this->current_account;
			$this->view->cache = $this->file_cache;
			$this->view->close_aboute_note_attribute = $this->close_aboute_note_attribute;
			$this->view->close_settings_note_attribute = $this->close_settings_note_attribute;
			$this->view->close_review_note_attribute = $this->close_review_note_attribute;
			$this->view->close_visits_note_attribute = $this->close_visits_note_attribute;

			$this->view->action_time = microtime(true) - $this->start_action_time;

			$this->view->start_render_time = microtime(true);

			$prev = $this->request('prev');

			$this->view->referer = (isset($_SERVER['HTTP_REFERER'])) ? $_SERVER['HTTP_REFERER'] : '';
			$this->view->city = $this->city;

			$this->view->product_basket = $this->product_basket;
			if($prev)
			{
				$this->view->referer = '/' . urldecode($prev);


				if($this->cache_view)
				{
					$this->file_cache->start($this->cache_name);
				}
			}
		}

		public function afterAction()
		{
			if($this->cache_view)
			{

				$this->file_cache->end();
			}
		}

	}