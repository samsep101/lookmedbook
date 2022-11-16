<?php
class WordsCountRule extends Rule
{
	private static $instance;

	private function __construct()
	{

	}

	public static function getInstance()
	{
		if (self::$instance == NULL) self::$instance = new WordsCountRule();

		return self::$instance;
	}


	public function execute($rule_value, $validate_value, DynamicModel $model = NULL)
	{
		if (preg_match_all('/([a-zA-Zа-яА-Я-]+)/ui', $validate_value,$matches))
		{
			return $rule_value == count($matches[1]);
		} else {
			return false;
		}
	}
}