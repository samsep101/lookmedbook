<?php
    class FieldInfo
    {
        protected $name;
        protected $foreign_key = FALSE;
        protected $foreign_name = '';
        protected $type;

        protected $is_index = FALSE;
        protected $is_unique = FALSE;

        public function setForeignKey($foreign_key)
        {
            $this->foreign_key = $foreign_key;
        }

        public function setForeignName($foreign_name)
        {
            $this->foreign_name = $foreign_name;
        }

        public function getForeignName()
        {
            return $this->foreign_name;
        }

        public function getForeignKey()
        {
            return $this->foreign_key;
        }

        public function setIsIndex($is_index)
        {
            $this->is_index = $is_index;
        }

        public function getIsIndex()
        {
            return $this->is_index;
        }

        public function setIsUnique($is_unique)
        {
            $this->is_unique = $is_unique;
        }

        public function getIsUnique()
        {
            return $this->is_unique;
        }

        public function setName($name)
        {
            $this->name = $name;
        }

        public function getName()
        {
            return $this->name;
        }

        public function setType($type)
        {
            $this->type = $type;
        }

        public function getType()
        {
            return $this->type;
        }
    }