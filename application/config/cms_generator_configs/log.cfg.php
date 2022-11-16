<?php
    $log = array(
        'table'     => DB_PREFIX . 'log', /*имя таблицы*/
        'title'     => 'Список логов', /*меняется "ролей"*/
        'fields'    => array(
            'id'         => 'index', /*всегда*/
            'code'       => 'input',
            'log'        => 'input',
            'account_id' => array(
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
            'dt'         => array('type' => 'date', 'show_time' => TRUE),
        ),
        'generator' => array(
            'fields' => array(
                'id'         => 'ID',
                'code'       => 'Код',
                'log'        => 'Лог',
                'account_id' => 'Аккаунт',
                'dt'         => 'Дата',

            ),
            'list'   => array(
                'fields'  => array('code', 'account_id', 'log'), /*поля кот. отображаются в списке "суперадминистратор"*/
                'title'   => 'Список логов',
                'sort_by' => array(
                    array(
                        'field' => 'code',
                        'desc'  => 'ASC'
                    ),
                )
            ),
            'edit'   => array(
                'fields' => array(
                    'Лог' => array(
                        'code', 'log', 'account_id', 'dt'
                    ),
                ),
                'title'  => 'Редактирование',
                'submit' => 'Сохранить',
            ),
            'add'    => array(
                'fields' => array(
                    'Лог' => array(
                        'code', 'log', 'account_id', 'dt'
                    ),
                ),
                'title'  => 'Создать',
                'submit' => 'Создать',
            ),
        ),
    );

    CmsGeneratorConfigRegister::add('log', $log);