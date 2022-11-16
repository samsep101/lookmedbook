<?php
    class ValidationRules
    {
        private $validation_rules;

        public function __construct()
        {

        }

        public function add($name, $rules)
        {
            $this->validation_rules[$name] = $rules;
        }

        public function get($name)
        {
            if (!isset($this->validation_rules[$name]) && debug)
                throw new Exception('Правил валидации с именем ' . $name . ' не существует!');
            return $this->validation_rules[$name];
        }

        public function getValidationRules()
        {
            return $this->validation_rules;
        }
    }