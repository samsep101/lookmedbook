<?php
    class RequestProcessor
    {
        private $fields = array();

        private $request;

        public function __construct($config_name)
        {
            $config = CmsGeneratorConfigRegister::get($config_name);
            $this->fields = $config['fields'];

            $this->request = new Request();
        }

        public function processPostData(DynamicModel $model)
        {
            $fields = $model->getFields();



            $form = $this->request->getParam('form');

            if ($fields) {
                foreach ($fields as $field_name) {
                    if (isset($form[$field_name])){
                        $model->{$field_name} = $form[$field_name] ? $form[$field_name] : NULL;
                    }
                }
            }
        }


    }