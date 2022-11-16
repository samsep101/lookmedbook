<?php
    class TripleFieldRule extends Rule
    {
        private static $instance;

        private function __construct()
        {

        }

        public static function getInstance()
        {
            if (self::$instance == NULL) self::$instance = new TripleFieldRule();

            return self::$instance;
        }

        public function execute($rule_value, $validate_value, DynamicModel $model = NULL)
        {
            if (!$validate_value || !$rule_value) return TRUE;

            $tmp = explode('.', $rule_value);
            $table = $tmp[0];
            $field_1 = $tmp[1];
            $field_2 = $tmp[2];
            $field_3 = $tmp[3];

            $tmp_2 = explode('|', $validate_value);
            $validate_1 = $tmp_2[0];
            $validate_2 = $tmp_2[1];
            $validate_3 = $tmp_2[2];

            $db = Register::get('db');

            $sql = 'SELECT COUNT(*) as result
                    FROM '.$table.'
                    WHERE '.$field_1.' LIKE "'.$db->escape($validate_1).'"
                    AND '.$field_2.' LIKE "'.$db->escape($validate_2).'"
                    AND '.$field_3.' LIKE "'.$db->escape($validate_3).'"
                    AND id != '.(int)$model->getId();

            $data = $db->query($sql);

            return !(bool)$data[0]['result'];
        }
    }