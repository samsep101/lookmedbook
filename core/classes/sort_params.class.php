<?php
    class SortParams extends Params
    {
        /**
         * @var array array(array('field_name' => <field_name>, 'order' => <asc|desc>, ...)
         */
        protected $params = array();

        public function getParams()
        {
            return $this->params;
        }

        public function addParam($field_name, $order = 'ASC')
        {
            $this->params[] = array(
                'field_name' => $field_name,
                'order'      => $order
            );
        }
    }