<?php
    define('FORM_VALIDATOR_RULES_PATH', dirname(__FILE__) . '/validator_rules/');
    class ValidatorRulesFactory
    {
        public static function getRule($ruleName)
        {
            $fileName = $ruleName . '.rule.php';
            $className = str_replace('_', '', $ruleName) . 'Rule';


            if (!class_exists($className, FALSE)) {
                if (!file_exists(FORM_VALIDATOR_RULES_PATH . $fileName)) {
                    throw new FormValidatorNotFoundRuleException('Файл для правила ' . $ruleName . ' не найден. ' . FORM_VALIDATOR_RULES_PATH . $fileName);
                } else {
                    require_once (FORM_VALIDATOR_RULES_PATH . $fileName);
                }
            }

            return call_user_func_array(array($className, 'getInstance'), array());
        }
    }

    class FormValidatorNotFoundRuleException extends Exception
    {
    }