<?php
    class TableInfo
    {
        private $table_name;

        private $fields = array();

        public function __construct($table_name)
        {
            $this->table_name = $table_name;
        }

        public function addField($field)
        {
            $this->fields[] = $field;
        }

        public function getTableName()
        {
            return $this->table_name;
        }

        /**
         * @return FieldInfo[]
         */
        public function getFields()
        {
            return $this->fields;
        }
    }