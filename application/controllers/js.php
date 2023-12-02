<?php
    /**
     * Контролер для автоматической генерации js файлов
     * Заменить затем статическими файлами.
     */
    class JsController extends BaseController
    {
        public function validation()
        {
            $validation_rules = Register::get('validation_rules');

            $rules = $validation_rules->getValidationRules();

            header('Content-type: application/json');
            echo 'var validation_rules = '.json_encode($rules).';';
            exit();
        }
    }