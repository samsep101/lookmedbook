<?php
    class LandingController extends BaseController
    {
        public $layout = 'landing';

        public function index()
        {
            ErrorPageViewHelper::page404('404');
            exit();

            $specialty_alias = $this->request('id');
            $opened_specialty = array(SpecialtyModel::OPHTHALMOLOGIST);

            if (!$specialty_alias) {
                ErrorPageViewHelper::page404('404');
                exit();
            }

            /**
             * @var SpecialtyManager $specialty_manager
             */

            $specialty_manager = ModelManagerFactory::getByName('specialty');
            $specialty = $specialty_manager->getOneByAlias($specialty_alias);

            if (!$specialty || !in_array($specialty->getId(), $opened_specialty)) {
                ErrorPageViewHelper::page404('404');
                exit();
            }

            $this->view->specialty = $specialty;
            $this->view->counter_number = (int)AnalyticCounterHelper::getCounterIdByCityIdAndCounterTypeId($this->city->getId(), AnalyticCounterTypeModel::YANDEX_COUNTER);
        }
    }