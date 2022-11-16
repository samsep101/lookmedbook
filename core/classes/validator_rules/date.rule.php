<?php
    class DateRule extends Rule
    {
        private static $instance;

        private function __construct()
        {

        }

        public static function getInstance()
        {
            if (self::$instance == NULL) self::$instance = new DateRule();

            return self::$instance;
        }

        public function execute($rule_value, $validate_value, DynamicModel $model = NULL)
        {
			if (!$validate_value)
				return true;

            if (!preg_match('/^[0-9]{4}\-[0-9]{2}-[0-9]{2}/', $validate_value)) {
                return FALSE;
            } else {
                return TRUE;
            }
        }
    }