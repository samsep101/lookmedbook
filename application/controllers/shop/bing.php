<?php
    class BingShopController extends BaseController
    {
        public function index()
        {
            $api = ImageSearchApiFactory::getInstance();

            $search_params = array(
                'title' => 'таблетки',
                'size' => 'Large',
                'count' => 10
            );

            $api->searchImage($search_params);
        }
    }