<?php
    $search_log = array(
        'table'     => DB_PREFIX . 'search_log',
        'title'     => 'Лог поисковых запросов',
        'fields'    => array(
            'id'        => 'index',
            'query' => 'just_text',
            'is_successful' => array('type' => 'checkbox', 'label' => 'Да/Нет'),
            'time' => array(
                'type' => 'date',
                'show_time' => TRUE
            ),
        ),
        'generator' => array(
            'fields'   => array(
                'id'        => 'ID',
                'query' => 'Текст запроса',
                'is_successful' => 'Запрос успешный',
                'time' => 'Время выполнения запроса',
            ),
            'list'     => array(
                'fields'  => array('id', 'query', 'is_successful', 'time'),
                'title'   => 'Лог поисковых запросов',
                'sort_by' => array(
                    array(
                        'field' => 'time',
                        'desc'  => 'DESC'
                    ),
                )

            ),
            'edit'     => array(
                'fields' => array(
                    'fields'  => array('id', 'query', 'is_successful', 'time'),
                ),
                'title'  => 'Редактирование страницы',
                'submit' => 'Сохранить',
            ),
        )
    );

    CmsGeneratorConfigRegister::add('search_log', $search_log);