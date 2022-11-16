<?php
    /**
     * Created by JetBrains PhpStorm.
     * User: Денис
     * Date: 28.11.12
     * Time: 15:54
     * To change this template use File | Settings | File Templates.
     */
    abstract class Rule
    {

        protected $errorMessage;

        abstract public function execute($rule_value, $validate_value);

        public function getErrorMessage()
        {
            return $this->errorMessage;
        }
    }