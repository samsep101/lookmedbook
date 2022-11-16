<?php
    class MaxRule extends Rule
    {
        private static $instance;

        private function __construct()
        {

        }

        public static function getInstance()
        {
            if (self::$instance == NULL) self::$instance = new MaxRule();

            return self::$instance;
        }

        public function execute($rule_value, $validate_value, DynamicModel $model = NULL)
        {
            Test::dump($model);
            if ($validate_value == '') return TRUE;
            if ($validate_value > $rule_value) {
                return FALSE;
            } else {
                return TRUE;
            }
        }
    }