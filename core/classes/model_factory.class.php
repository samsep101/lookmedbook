<?php
    class ModelFactory
    {
        /**
         * @param $model_name
         *
         * @return
         */
        public static function getByName($model_name)
        {
            $model_name = str_replace('_', ' ', $model_name);
            $model_name = ucwords($model_name);
            $model_name = str_replace(' ', '', $model_name);

            $model_class_name = $model_name . 'Model';
            if (class_exists($model_class_name, FALSE) || Application::tryToLoadClass($model_class_name)) {
                return new $model_class_name();
            }

            return FALSE;
        }

        public static function getByTableName($table_name)
        {
            $table_name = str_replace('_', ' ', $table_name);
            $table_name = ucwords($table_name);
            $table_name = str_replace(' ', '', $table_name);

            $model_class_name = $table_name . 'Model';
            if (class_exists($model_class_name, FALSE) || Application::tryToLoadClass($model_class_name)) {
                return new $model_class_name();
            }

            return FALSE;
        }
    }