<?php
$yandex_log = array(
    'table'     => DB_PREFIX . 'yandex_log', /*имя таблицы*/
    'title'     => 'Переписка с Яндексом', /*меняется "ролей"*/
    'fields'    => array(
        'id'               => 'index', /*всегда*/
        'method' => 'just_text',
        'direction' => 'just_text',
        'request' => 'just_text',
        'response' => 'just_text',
        'dt' => 'just_text',
        'visit_id' => 'just_text',
        'yandex_id' => 'just_text',
    ),
    'generator' => array(
        'fields' => array(
            'id'               => 'ID',
            'method' => 'Название метода',
            'direction' => 'Направление запроса',
            'request' => 'Содержание запроса',
            'response' => 'Содержание ответа',
            'dt' => 'Время запроса',
            'visit_id' => 'Визит',
            'yandex_id' => 'ID яндекса',
        ),
        'list'   => array(
            'fields'  => array('visit_id', 'method', 'dt'), /*поля кот. отображаются в списке "суперадминистратор"*/
            'title'   => 'Переписка с Яндексом',
            'sort_by' => array(
                array(
                    'field' => 'dt',
                    'desc' => 'ASC'
                ),
            ),
        ),
        'edit'   => array(
            'fields' => array(
                'Данные' => array(
                    'visit_id',
                    'yandex_id',
                    'method',
                    'direction',
                    'request',
                    'response',
                    'dt',
                ),
            ),
            'title'  => 'Редактирование',
            'submit' => 'Сохранить',
        ),
        'add'    => array(
            'fields' => array(
                'Данные' => array(
                    'visit_id',
                    'yandex_id',
                    'method',
                    'direction',
                    'request',
                    'response',
                    'dt',
                ),
            ),
            'title'  => 'Добавить',
            'submit' => 'Добавить',
        ),
    ),
);

CmsGeneratorConfigRegister::add('yandex_log', $yandex_log);