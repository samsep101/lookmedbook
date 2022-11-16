<?php
    class OrderTimeRule extends Rule
    {
        private static $instance;

        private function __construct()
        {

        }

        public static function getInstance()
        {
            if (self::$instance == NULL) self::$instance = new OrderTimeRule();

            return self::$instance;
        }

        public function execute($rule_value, $validate_value)
        {

            if ($validate_value < date('Y-m-d H:i:s')) {
                return FALSE;
            } else {
                return TRUE;
            }
        }
    }