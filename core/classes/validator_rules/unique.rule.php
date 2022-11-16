<?php
    class UniqueRule extends Rule
    {
        private static $instance;

        public static function getInstance()
        {
            if (self::$instance == NULL) self::$instance = new UniqueRule();

            return self::$instance;
        }

        public function execute($rule_value, $validate_value, DynamicModel $model = NULL)
        {
            $tmp = explode('.', $rule_value);
            $table = $tmp[0];
            $field = $tmp[1];

            $db = Register::get('db');

            $sql = 'SELECT COUNT(*) as result
                        FROM `' . $table . '`
                    WHERE `' . $field . '` = "' . $db->escape($validate_value) . '"
                        AND id != ' . (int)$model->getId();

            $data = $db->query($sql);

            return !(bool)$data[0]['result'];
        }
    }