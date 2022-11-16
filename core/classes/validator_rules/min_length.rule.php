<?php
    class MinLengthRule extends Rule
    {
        private static $instance;

        private function __construct()
        {

        }

        public static function getInstance()
        {
            if (self::$instance == NULL) self::$instance = new MinLengthRule();

            return self::$instance;
        }

        public function execute($rule_value, $validate_value)
        {
            if ($validate_value == '') return TRUE;
            if (mb_strlen($validate_value, 'utf-8') < $rule_value) {
                return FALSE;
            } else {
                return TRUE;
            }
        }
    }