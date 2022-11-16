<?php
    class AnalysisController extends BaseController
    {
        public function index()
        {

            $this->view->city_id = $this->city->getId();
            $this->view->latitude = $this->city->lat;
            $this->view->longitude = $this->city->lng;

            $this->view->menu_active = 'analysis';

            $this->view->load_map = TRUE;

            $this->view->page_title = 'Найти лабораторию - «'.SITE_NAME.'»';
            $this->view->page_description = 'Найти лабораторию - вся информация обо всех известных заболеваниях на сервисе '.SITE_NAME.'';

            $this->view->canonical_link = '/analysis';
        }

		public function ajaxGetExistsOfStatusesByCityId()
		{
			$city_id = $this->request('city_id');

			/**
			 * @var LaboratoryManager $laboratory_manager
			 */
			$laboratory_manager = ModelManagerFactory::getByName('laboratory');

			$availability = $laboratory_manager->checkExistsOfStatusesByCityId($city_id);

			JsonResponse::result($availability);
		}
    }
