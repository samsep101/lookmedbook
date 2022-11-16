<?php
    class SpecialtyRule extends Rule
    {
        private static $instance;

        private function __construct()
        {

        }

        public static function getInstance()
        {
            if (self::$instance == NULL) self::$instance = new SpecialtyRule();

            return self::$instance;
        }

        public function execute($rule_value, $validate_value)
        {
            $validate_value = intval($validate_value);

            if($rule_value && $validate_value) {
                $specialtiesManager = ModelManagerFactory::getByName('specialty');
                $specialties = $specialtiesManager->getList();

                if(!count($specialties)) return FALSE;

                foreach($specialties AS $sValue) {
                    if($sValue->id == $validate_value) return TRUE;
                }
            } else {
                return FALSE;
            }
        }
    }