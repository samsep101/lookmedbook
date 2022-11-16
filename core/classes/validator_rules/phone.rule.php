<?php
    class PhoneRule extends Rule
    {
        private static $instance;

        private function __construct()
        {

        }

        public static function getInstance()
        {
            if (self::$instance == NULL) self::$instance = new PhoneRule();

            return self::$instance;
        }

        public function execute($rule_value, $validate_value)
        {
            if (!preg_match('/^\+?7\-?[0-9]{3}\-?[0-9]{3}\-?[0-9]{2}\-?[0-9]{2}$/', $validate_value)) {
                return FALSE;
            } else {
                return TRUE;
            }
        }
    }