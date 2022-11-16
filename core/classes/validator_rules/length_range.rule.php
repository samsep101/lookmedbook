<?php
    class LengthRangeRule extends Rule
    {
        private static $instance;

        private function __construct()
        {

        }

        public static function getInstance()
        {
            if (self::$instance == NULL) self::$instance = new LengthRangeRule();

            return self::$instance;
        }

        public function execute($rule_value, $validate_value, DynamicModel $model = NULL)
        {
            if ($validate_value == '')
                return TRUE;

            $values = explode('-', $rule_value);

            if ((mb_strlen($validate_value, 'utf-8') < $values[0])
                || (mb_strlen($validate_value, 'utf-8') > $values[1])
            ) {
                return FALSE;
            } else {
                return TRUE;
            }
        }
    }