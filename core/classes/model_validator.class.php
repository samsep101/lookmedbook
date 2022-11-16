<?php
    abstract class ModelValidator
    {
        protected $error_codes = array();
        protected $error_messages = array();

        public function __construct()
        {

        }

        public function validate(DynamicModel $model)
        {
            return TRUE;
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