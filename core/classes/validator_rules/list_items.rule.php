<?php

    class ListItemsRule extends Rule
    {
        private static $instance;

        private function __construct()
        {

        }

        public static function getInstance()
        {
            if (self::$instance == NULL) self::$instance = new ListItemsRule();

            return self::$instance;
        }

        public function execute($rule_value, $validate_value)
        {
			if(!empty($validate_value) && is_array($validate_value) && count($validate_value) > 0) {
				$noneSelected = true;
				foreach($validate_value AS $vvValue) {
					if($vvValue > 0) $noneSelected = false;
				}

				if($noneSelected) {
					return FALSE;
				} else {
					return TRUE;
				}
			} else {
				return FALSE;
			}
        }
    }