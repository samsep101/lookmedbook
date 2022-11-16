<?php
    class ValidCaptchaRule extends Rule
    {
        private static $instance;

        private function __construct()
        {

        }

        public static function getInstance()
        {
            if (self::$instance == NULL) self::$instance = new ValidCaptchaRule();

            return self::$instance;
        }

        public function execute($rule_value, $validate_value)
        {
            return TRUE;
        }
    }