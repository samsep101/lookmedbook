<?php
    class PageViewHelper
    {
        public static function getDescription($code)
        {
            /**
             * @var PageManager $page_manager
             * @var PageModel $page
             */

            $page_manager = ModelManagerFactory::getByName('page');
            $page = $page_manager->getOneByCode($code);

            return ($page) ? $page->descr : null;
        }

        public static function getContent($code)
        {
            /**
             * @var PageManager $page_manager
             * @var PageModel $page
             */

            $page_manager = ModelManagerFactory::getByName('page');
            $page = $page_manager->getOneByCode($code);

            return ($page) ? $page->content : null;
        }
    }