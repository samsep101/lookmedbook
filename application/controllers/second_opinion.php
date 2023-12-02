<?php

use app\library\resources\Style;

class Second_opinionController extends BaseController
    {
        public $layout = 'home';

        public function index()
        {
            $this->view->page_title = 'Расшифровка снимков МРТ, КТ и других исследований, мнения экспертов';
            $this->view->is_second_opinion = true;
            $this->view->counter_number = (int)AnalyticCounterHelper::getCounterIdByCityIdAndCounterTypeId($this->city->getId(), AnalyticCounterTypeModel::YANDEX_COUNTER);
            $this->registerStyles();
        }

        private function registerStyles()
        {
            if (debug) {
                $url = '/media/css/'.CSS_DIR.'/second_opinion/styles.css?rnd='.RELEASE__NUMBER;
            } else {
                $url = '/media/css/min/second_opinion.css?rnd='.RELEASE__NUMBER;
            }
            $style = new Style($url);
            $style->setType(STYLE::TYPE_SIMPLE);
            $this->view->registerStyle($style);
        }
    }

