<?php
    class SystemFileGenerator extends Controller
    {
        protected  function getCamelCaseString($table_name){
            $class_name = str_replace('_', ' ', $table_name);
            $class_name = ucwords($class_name);
            return str_replace(' ', '', $class_name);
        }
    }