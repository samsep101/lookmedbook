<?php
    /**
     * Created by JetBrains PhpStorm.
     * User: Денис
     * Date: 28.11.12
     * Time: 15:20
     * To change this template use File | Settings | File Templates.
     */

    /**
     * Класс валидации значений
     *
     */
    class Validator
    {
        private $status = TRUE;
        private $error_codes = array();
        private $error_messages = array();

        public function validate($validate_value, $rules, DynamicModel $model)
        {
            foreach ($rules as $rule_name => $rule_info) {
                $rule_validator = ValidatorRulesFactory::getRule($rule_name);

                $rule_value = (is_array($rule_info)) ? $rule_info['value'] : $rule_info;

                if ($rule_name == 'field_value') continue;

                if (!$rule_validator->execute($rule_value, $validate_value, $model)) {
                    $this->status = FALSE;
                    $this->error_codes[] = $rule_info['code'];
                    $this->error_messages[] = $rule_info['message'];
                }
            }
        }

        public function checkStatus()
        {
            return $this->status;
        }

        public function getErrorCodes()
        {
            return $this->error_codes;
        }

        public function getErrorMessages()
        {
            return $this->error_messages;
        }
    }


