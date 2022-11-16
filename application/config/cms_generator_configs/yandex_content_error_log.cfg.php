<?php
$yandex_content_error_log = array(
    'table'         =>  DB_PREFIX.'yandex_content_error_log',
    'title'         =>  'Загрузка текстов в яндекс: ошибки',
    'fields'        =>  array(
        'id' => 'index',
        'disease_id' => array(
            'type'        => 'category',
            'cross_name'  => 'title',
            'cross_index' => 'id',
            'cross_table' => DB_PREFIX . 'disease',
            'first'       => array(
                '0' => '',
            ),
            'filter'      => 'true',
            'sort_by'     => 'title',
        ),
        'error' => 'input',
        'date' => 'input',
    ),
    'generator' => array(
        'fields' => array(
            'id' => 'ID',
            'disease_id' => 'Заболевание',
            'error' => 'Ошибка',
            'date' => 'Дата отправки',
        ),
        'list' => array(
            'fields' => array(
                'disease_id',
                'error',
                'date',
            ),
            'title'	 => 'Список',
            'sort_by' => array(
                array(
                    'field' => 'date',
                    'desc'  => 'DESC'
                ),
            )
        ),
    ),
);
CmsGeneratorConfigRegister::add('yandex_content_error_log', $yandex_content_error_log);
