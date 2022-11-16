<?php
    class RegexRule extends Rule
    {
        private static $instance;

        private function __construct()
        {

        }

        public static function getInstance()
        {
            if (self::$instance == NULL) self::$instance = new RegexRule();

            return self::$instance;
        }

        public function execute($rule_value, $validate_value)
        {
            if ($validate_value == '') return TRUE;
            if (preg_match('/' . $rule_value . '/ims', $validate_value)) return TRUE;
            else {
                return FALSE;
            }
        }
    }