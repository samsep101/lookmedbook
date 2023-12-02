<?php
    $distribution = array(
        'table'     => DB_PREFIX . 'distribution',
        'title'     => 'Рассылки',
        'fields'    => array(
            'id'                => 'index',
            'name'              => 'input',
            'text'              => 'text',
            'dt_create'         => array('type' => 'date', 'show_time' => TRUE),

            'dt_start'          => array('type' => 'date', 'show_time' => TRUE),
            'account_id'        => array(
                'type'        => 'category',
                'cross_name'  => 'email',
                'cross_index' => 'id',
                'cross_table' => DB_PREFIX . 'account',
                'first'       => array(
                    '0' => '',
                ),
                'filter'      => 'true',
                'sort_by'     => 'email',
            ),
            'disease_id'        => array(
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
            'all_accounts_flag' => 'checkbox',
            'task_status_id'    => array(
                'type'        => 'category',
                'cross_name'  => 'name',
                'cross_index' => 'id',
                'cross_table' => DB_PREFIX . 'task_status',
                'first'       => array(
                    '0' => '',
                ),
                'filter'      => 'true',
                'sort_by'     => 'name',
            ),
        ),
        'generator' => array(
            'fields' => array(
                'id'                => 'ID',
                'name'              => 'Название',
                'text'              => 'Текст',
                'dt_create'         => 'Дата создания',
                'dt_start'          => 'Время запуска',
                'account_id'        => 'Аккаунт для персональной рассылки',
                'disease_id'        => 'Группа пользователей, привязанная к заболеванию',
                'all_accounts_flag' => 'Рассылка всем пользователям',
                'task_status_id'    => 'Статус задания'
            ),
            'list'   => array(
                'fields'  => array(
                    'name',
                    'dt_create',
                    'dt_start',
                    'task_status_id'
                ),
                'title'   => 'Список',
                'sort_by' => array(
                    array(
                        'field' => 'name',
                        'desc'  => 'ASC'
                    ),
                )

            ),
            'edit'   => array(
                'fields' => array(
                    'Рассылка' => array(
                        'name',
                        'text',
                        'dt_create',
                        'dt_start',
                        'account_id',
                        'disease_id',
                        'all_accounts_flag',
                        'task_status_id'
                    ),
                ),
                'title'  => 'Редактирование',
                'submit' => 'Сохранить',
            ),
            'add'    => array(
                'fields' => array(
                    'Рассылка' => array(
                        'name',
                        'text',
                        'dt_create',
                        'dt_start',
                        'account_id',
                        'disease_id',
                        'all_accounts_flag',
                        'task_status_id'
                    ),
                ),
                'title'  => 'Создать новую',
                'submit' => 'Создать новую',
            ),
        ),
    );

    CmsGeneratorConfigRegister::add('distribution', $distribution);