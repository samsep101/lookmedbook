<?php
    class CmsGeneratorConfigRegister
    {
        private static $container;

        public static function add($name, $value)
        {
            if (isset(self::$container[$name])) {
                throw new Exception('Элемент с индексом ' . $name . ' уже содержится в реестре');
            }

            self::$container[$name] = $value;
        }

        public static function get($name)
        {
            return (isset(self::$container[$name])) ? self::$container[$name] : FALSE;
        }
    }