<?php
    class FilterTypeFactory
    {
        /**
         * @param $filter_name
         * @return FilterType
         * @throws Exception
         */
        public static function getByName($filter_name, $field_name, $settings)
        {
            $filter_name = StringHelper::toCamelCase($filter_name);

            $class_name = $filter_name.'FilterType';
            if(Application::tryToLoadClass($class_name))
            {
                return new $class_name($field_name, $settings);
            } else {
                throw new Exception('Unknown class '.$class_name);
            }
        }
    }