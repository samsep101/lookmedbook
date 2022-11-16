<?php
$yandex_content_log = array(
    'table'         =>  DB_PREFIX.'yandex_content_log',
    'title'         =>  'Загрузка текстов в яндекс',
    'fields'        =>  array(
        'id' => 'index',
        'content_send' => 'input',
        'content_updated' => 'input',
        'content_error' => 'input',
        'date_send' => 'input',
    ),
    'generator' => array(
        'fields' => array(
            'id' => 'ID',
            'content_send' => 'Загружено текстов',
            'content_updated' => 'Обновлено текстов',
            'content_error' => 'Ошибок',
            'date_send' => 'Дата отправки',
        ),
        'list' => array(
            'fields' => array(
                'content_send',
                'content_updated',
                'content_error',
                'date_send',
            ),
            'title'	 => 'Список',
            'sort_by' => array(
                array(
                    'field' => 'date_send',
                    'desc'  => 'DESC'
                ),
            ),
            'information_blocks' => array(
                array(
                    'title' => '',
                    'url' => '/admin/ajax/getYandexContentStatistic',
                ),
            )
        ),
    ),
);
CmsGeneratorConfigRegister::add('yandex_content_log', $yandex_content_log);
