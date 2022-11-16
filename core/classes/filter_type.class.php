<?php
    abstract class FilterType
    {
        protected $settings;
        protected $field_name;

        abstract function getView($val);

        protected function getElementName()
        {
            return 'filter['.$this->field_name.']';
        }

        /**
         * @return mixed
         */
        public function getFieldName()
        {
            return $this->field_name;
        }

        /**
         * @return mixed
         */
        public function getSettings()
        {
            return $this->settings;
        }


    }