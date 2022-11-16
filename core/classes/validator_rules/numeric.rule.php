<?php
    class NumericRule extends Rule
    {
        private static $instance;

        private function __construct()
        {

        }

        public static function getInstance()
        {
            if (self::$instance == NULL) self::$instance = new NumericRule();

            return self::$instance;
        }

        public function execute($rule_value, $validate_value)
        {
            if ($validate_value == '') return TRUE;
            if (!is_numeric($validate_value)) {
                return FALSE;
            } else {
                return TRUE;
            }
        }
    }