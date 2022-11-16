<?php
    class CustomModelManager extends ModelManager
    {
        protected $model_register_enable = 0;

        public function getList()
        {
            throw new Exception('Это объект класса CustomManager');
        }

        public function getOneById()
        {
            throw new Exception('Это объект класса CustomManager');
        }

        public function getListBySearchParams()
        {
            throw new Exception('Это объект класса CustomManager');
        }

        public function getListWithLimit()
        {
            throw new Exception('Это объект класса CustomManager');
        }
    }