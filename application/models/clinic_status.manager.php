<?php
    class ClinicStatusManager extends StaticDataModelManager
    {
        protected $model_data = array(
            ClinicStatusModel::PUBLISHED => array(
                'id' => ClinicStatusModel::PUBLISHED,
                'name' => 'Опубликована',
            ),
            ClinicStatusModel::RAW => array(
                'id' => ClinicStatusModel::RAW,
                'name' => 'Необработана',
            ),
            ClinicStatusModel::PROBLEM => array(
                'id' => ClinicStatusModel::PROBLEM,
                'name' => 'Проблемная'
            ),
        );

        protected $model_name = 'ClinicStatusModel';
    }