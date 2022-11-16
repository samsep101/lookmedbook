<?php
    class DbField
    {

        protected $name;
        protected $type;
        protected $foreign_key;

        public function __construct($name, $foreign_key, $type)
        {
            $this->name = $name;
            $this->foreign_key = $foreign_key;
            $this->type = $type;
        }

        public function getForeignKey()
        {
            return $this->foreign_key;
        }

        public function getName()
        {
            return $this->name;
        }

        public function getType()
        {
            return $this->type;
        }


    }