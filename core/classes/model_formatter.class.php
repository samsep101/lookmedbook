<?php
    class ModelFormatter
    {
        protected $fields;

        public function format($model)
        {
            if (is_array($model))
            {
                return $this->formatList($model);
            } else {
                return $this->formatOne($model);
            }
        }

        /**
         * @param DynamicModel $model
         * @return array
         */
        public function formatOne(DynamicModel $model)
        {
            $result = array();

            if (!$this->fields)
            {
                $table_name = $model->getManager()->getTableName();
                $this->fields = DbHelper::getTableFields($table_name);
            }

        }

        public function formatList($model)
        {

        }
    }