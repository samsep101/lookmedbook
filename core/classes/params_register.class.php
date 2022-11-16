<?php
    class ParamsRegister
    {
        private $params = array();

        public function addParam($name)
        {
            $this->params[$name] = TRUE;
        }

        public function removeParam($name)
        {
            unset($this->params[$name]);
        }

        public function checkParam($name)
        {
            return isset($this->params[$name]);
        }

        public function notEmpty()
        {
            return (bool)$this->params;
        }
    }