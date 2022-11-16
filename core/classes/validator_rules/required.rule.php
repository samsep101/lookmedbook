<?php
    class RequiredRule extends Rule
    {
        private static $instance;

        private function __construct()
        {

        }

        public static function getInstance()
        {
            if (self::$instance == NULL) self::$instance = new RequiredRule();

            return self::$instance;
        }

        public function execute($rule_value, $validate_value)
        {
            if ($rule_value == TRUE) {
                return (bool)$validate_value;
            } else {
                return TRUE;
            }
        }
    }