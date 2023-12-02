<?php
    $task = array(
        'table'     => DB_PREFIX . 'distribution_task',
        'title'     => 'Задания рассылки',
        'fields'    => array(
            'id'              => 'index',
            'distribution_id' => array(
                'type'        => 'category',
                'cross_name'  => 'name',
                'cross_index' => 'id',
                'cross_table' => DB_PREFIX . 'distribution',
                'first'       => array(
                    '0' => '',
                ),
                'filter'      => 'true',
                'sort_by'     => 'name',
            ),
            'account_id'      => array(
                'type'        => 'category',
                'cross_name'  => 'email',
                'cross_index' => 'id',
                'cross_table' => DB_PREFIX . 'account',
                'first'       => array(
                    '0' => '',
                ),
                'filter'      => 'true',
                'sort_by'     => 'last_name',
            ),
            'task_status_id'  => array(
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
                'id'              => 'ID',
                'distribution_id' => 'Рассылка',
                'account_id'      => 'Пользователь',
                'task_status_id'  => 'Статус'
            ),
            'list'   => array(
                'fields'  => array(
                    'distribution_id',
                    'account_id',
                    'task_status_id'
                ),
                //'is_super'
                'title'   => 'Список',
                'sort_by' => array(
                    array(
                        'field' => 'distribution_id',
                        'desc'  => 'ASC'
                    ),
                )

            ),
            'edit'   => array(
                'fields' => array(
                    'Рассылка' => array(
                        'distribution_id',
                        'account_id',
                        'task_status_id'
                    )
                ),
                'title'  => 'Редактирование',
                'submit' => 'Сохранить',
            ),
            'add'    => array(
                'fields' => array(
                    'Рассылка' => array(
                        'distribution_id',
                        'account_id',
                        'task_status_id'
                    )
                ),
                'title'  => 'Создать',
                'submit' => 'Создать',
            ),
        ),
    );

    CmsGeneratorConfigRegister::add('distribution_task', $task);