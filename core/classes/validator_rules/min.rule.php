<?php
    /**
     * Created by JetBrains PhpStorm.
     * User: Денис
     * Date: 28.11.12
     * Time: 16:09
     * To change this template use File | Settings | File Templates.
     */

    class MinRule extends Rule
    {
        private static $instance;

        private function __construct()
        {

        }

        public static function getInstance()
        {
            if (self::$instance == NULL) self::$instance = new MinRule();

            return self::$instance;
        }

        public function execute($rule_value, $validate_value)
        {
            if ($validate_value == '') return TRUE;
            if ($validate_value < $rule_value) {
                return FALSE;
            } else {
                return TRUE;
            }
        }
    }