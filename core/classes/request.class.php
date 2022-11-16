<?php
    /**
     * Http Request
     */
    class Request
    {

        private $get;
        private $post;
        private $request;

        /**
         * Create of HttpRequest object
         *
         * @param array $getParams external params, for example, retrieved from url mapping
         */
        public function __construct($getParams = NULL)
        {
            $this->get = $_GET;
            $this->post = $_POST;
            $this->request = $_REQUEST;

            if (!empty($getParams)) {
                $this->get = array_merge($getParams, $this->get);
                $this->request = array_merge($getParams, $this->request);
            }
        }

        /**
         * Get request param by key
         *
         * @param string $key
         * @param mixed  $defaultValue
         *
         * @return mixed
         */
        public function getParam($key, $defaultValue = NULL)
        {
            if (isset($this->request[$key])) {
                return $this->request[$key];
            }
            return $defaultValue;
        }

        /**
         * Get request param by key sent using GET-method
         *
         * @param string $key
         * @param mixed  $defaultValue
         *
         * @return mixed
         */
        public function get($key, $defaultValue = NULL)
        {
            if (!empty($this->get[$key])) {
                return $this->get[$key];
            }
            return $defaultValue;
        }

        /**
         * Get request param by key sent using POST-method
         *
         * @param string $key
         * @param mixed  $defaultValue
         *
         * @return mixed
         */
        public function post($key, $defaultValue = NULL)
        {
            if (!empty($this->post[$key])) {
                return $this->post[$key];
            }
            return $defaultValue;
        }

        /**
         * Get request param by key sent any method by REQUEST array
         *
         * @param string $key
         * @param mixed  $defaultValue
         *
         * @return mixed
         */
        public function request($key, $defaultValue = NULL)
        {
            if (!empty($this->request[$key])) {
                return $this->request[$key];
            }
            return $defaultValue;
        }

        /**
         * Is POST-Request?
         * @return bool
         */
        public function isPost()
        {
            if (strtolower($_SERVER['REQUEST_METHOD']) == 'post') {
                return TRUE;
            }
            return FALSE;

        }

        /**
         * Is GET-Request?
         * @return bool
         */
        public function isGet()
        {
            if (strtolower($_SERVER['REQUEST_METHOD']) == 'get') {
                return TRUE;
            }
            return FALSE;
        }

        /**
         * Is AJAX-Request?
         * Attention! Not all js-frameworks set this header. (positive checked on jQuery)
         * @return bool
         */
        public function isAJAX()
        {
            if (isset($_SERVER['HTTP_X_REQUESTED_WITH'])) {
                return TRUE;
            }
            return FALSE;
        }
    }