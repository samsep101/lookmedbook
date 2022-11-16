<?php
    class PagingParams extends Params
    {
        protected $page;
        protected $by_page;

        public function __construct($page, $by_page)
        {
            $this->page = $page;
            $this->by_page = $by_page;
        }

        public function setByPage($by_page)
        {
            $this->by_page = $by_page;
        }

        public function getByPage()
        {
            return $this->by_page;
        }

        public function setPage($page)
        {
            $this->page = $page;
        }

        public function getPage()
        {
            return $this->page;
        }
    }