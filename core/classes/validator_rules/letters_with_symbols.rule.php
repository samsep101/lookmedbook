<?php
	class LettersWithSymbolsRule extends Rule{
		private static $instance;

		private function __construct()
		{

		}

		public static function getInstance()
		{
			if (self::$instance == NULL) self::$instance = new LettersWithSymbolsRule();

			return self::$instance;
		}


		public function execute($rule_value, $validate_value, DynamicModel $model = NULL)
		{
			if ($rule_value == TRUE) {
				if (!preg_match('/^[A-Za-zА-Яа-я\«\» \(\)\."\-,0-9]+$/ui', $validate_value)) {
					return FALSE;
				} else {
					return TRUE;
				}
			} else {
				return TRUE;
			}
		}
	}