<?php
    class EmailRule extends Rule
    {
        private static $instance;

        private function __construct()
        {

        }

        public static function getInstance()
        {
            if (self::$instance == NULL) self::$instance = new EmailRule();

            return self::$instance;
        }


        public function execute($rule_value, $validate_value, DynamicModel $model = NULL)
        {
            if($validate_value === '')
                return true;
            if ($rule_value == TRUE) {
                if (!preg_match('/[A-Za-z0-9_\-\.]+@[A-Za-z0-9_\-\.]/', $validate_value)) {
                    return FALSE;
                } else {
					if ((strpos($validate_value, '..') !== FALSE) || ($validate_value == 'mail@example.com'))
						return FALSE;
                    return TRUE;
                }
            } else {
                return TRUE;
            }
        }
    }