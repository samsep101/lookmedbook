<?php
class PasswordRule extends Rule{
    private static $instance;

    private function __construct()
    {

    }

    public static function getInstance()
    {
        if (self::$instance == NULL) self::$instance = new PasswordRule();

        return self::$instance;
    }


    public function execute($rule_value, $validate_value, DynamicModel $model = NULL)
    {
        if ($rule_value == TRUE) {
            if (!preg_match('/^[A-Za-z0-9]+$/', $validate_value)) {
                return FALSE;
            } else {
                return TRUE;
            }
        } else {
            return TRUE;
        }
    }
}