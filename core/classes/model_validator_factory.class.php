<?php
    class ModelValidatorFactory
    {
        public static function getValidatorByObject(DynamicModel $object)
        {
            $model_class_name = get_class($object);
            $validator_class_name = '';
            if (preg_match('/^(.+)Model$/', $model_class_name, $matches)) {
                $validator_class_name = $matches[1] . 'Validator';
            }

            if (class_exists($validator_class_name, FALSE) || Application::tryToLoadClass($validator_class_name)) {
                return new $validator_class_name();
            } else {
                return new DefaultModelValidator();
            }
        }
    }