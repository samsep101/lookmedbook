<?php

    class ModelExtension
    {

        protected $model = NULL;

        public function __construct($model)
        {
            $this->setModel($model);
        }

        public function setModel($model)
        {
            $this->model = $model;
        }

        public function getModel()
        {
            return $this->model;
        }
    }