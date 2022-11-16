<?php
    class LettersRule extends Rule
    {
        private static $instance;

        private function __construct()
        {

        }

        public static function getInstance()
        {
            if (self::$instance == NULL) self::$instance = new LettersRule();

            return self::$instance;
        }

        public function execute($rule_value, $validate_value, DynamicModel $model = NULL)
        {
	        if ($validate_value == '') return TRUE;
            if ($rule_value == TRUE) {
                if (!preg_match('/^[A-Za-zА-Яа-я \-]+$/ui', $validate_value)) {
                    return FALSE;
                } else {
                    return TRUE;
                }
            } else {
                return TRUE;
            }
        }
    }