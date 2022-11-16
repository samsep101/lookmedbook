<?php
    class TypeViewFactory
    {
        private static $register = array();

        /**
         * @param $type_name
         *
         * @return Type
         */
        public static function getTypeViewByTypeName($type_name)
        {
			$info = array();
			if (is_array($type_name))
			{
				$info = $type_name;
				$type_name = $type_name['type'];
			}

			$class_name = ucfirst($type_name) . 'Type';

            if ($class_name != 'Type') {
			    return new $class_name(null, $info);
            }
            else
                return '';
        }
    }