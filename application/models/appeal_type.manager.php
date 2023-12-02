<?php
    class AppealTypeManager extends StaticDataModelManager
    {
        protected $model_data = array(
            1 => array(
                'id' => 1,
                'name' => 'Целевой'
            ),
            2 => array(
                'id' => 2,
                'name' => 'Не целевой'
            )
        );

        protected $model_name = 'AppealTypeModel';
    }