<?php
    $message = array(
        'table'     => DB_PREFIX . 'message', /*имя таблицы*/
        'title'     => 'Список сообщений', /*меняется "ролей"*/
        'fields'    => array(
            'id'            => 'index', /*всегда*/
            'name'          => 'input',
            'text'          => 'text',
            'to_account_id' => array(
                'type'        => 'category',
                'cross_name'  => 'full_name',
                'cross_index' => 'id',
                'cross_table' => DB_PREFIX . 'account',
                'first'       => array(
                    '0' => '',
                ),
                'filter'      => 'true',
                'sort_by'     => 'full_name',
            ),
            'dt'            => array('type' => 'date', 'show_time' => TRUE),
            'is_readed'     => 'checkbox',
        ),
        'generator' => array(
            'fields' => array(
                'id'            => 'ID',
                'name'          => 'Заголовок',
                'text'          => 'Текст',
                'to_account_id' => 'Аккаунт',
                'dt'            => 'Дата',
                'is_readed'     => 'Прочитано?',
            ),
            'list'   => array(
                'fields'  => array('name', 'text'), /*поля кот. отображаются в списке "суперадминистратор"*/
                'title'   => 'Список сообщений',
                'sort_by' => array(
                    array(
                        'field' => 'to_account_id',
                        'desc'  => 'ASC'
                    ),
                )
            ),
            'edit'   => array(
                'fields' => array(
                    'Сообщение' => array(
                        'name', 'text', 'to_account_id', 'dt', 'is_readed',
                    ),
                ),
                'title'  => 'Редактирование',
                'submit' => 'Сохранить',
            ),
            'add'    => array(
                'fields' => array(
                    'Сообщение' => array(
                        'name', 'text', 'to_account_id', 'dt', 'is_readed',
                    ),
                ),
                'title'  => 'Создать',
                'submit' => 'Создать',
            ),
        ),
    );

    CmsGeneratorConfigRegister::add('message', $message);