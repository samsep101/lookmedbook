<?php
class AnalyticCounterTypeManager extends StaticDataModelManager
{
    protected $model_data = array(
        1 => array(
            'id' => AnalyticCounterTypeModel::YANDEX_COUNTER,
            'name' => 'yandex_counter'
        ),
        2 => array(
            'id' => AnalyticCounterTypeModel::GOOGLE_COUNTER,
            'name' => 'google_counter'
        )
    );

    protected $model_name = 'AnalyticCounterTypeModel';
}