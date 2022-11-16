<?php
    $cms_storage = array(
        'table'     => DB_PREFIX . 'storage',
        'title'     => 'Действия пользователей',
        'fields'    => array(
            'id'                     => 'index',
            'controller'             => 'input',
            'action'                 => 'input',
            'serialized_post_params' => 'text',
            'serialized_get_params'  => 'text',
            'account_id'             => array(
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
            'dt'                     => 'date',
        ),

        'generator' => array(
            'fields' => array(
                'id'                     => 'ID',
                'controller'             => '',
                'action'                 => '',
                'serialized_post_params' => '',
                'serialized_get_params'  => '',
                'account_id'             => 'Пользователь',
                'dt'                     => 'Дата',
            ),
            'list'   => array(
                'fields'  => array(
                    'controller',
                    'action',
                    'account_id',
                    'dt',
                ),
                'title'   => 'Список',
                'sort_by' => array(
                    array(
                        'field' => 'controller',
                        'desc'  => 'ASC'
                    ),
                )
            ),
            'edit'   => array(
                'fields'  => array(
                    'Данные' => array(
                        'controller',
                        'action',
                        'serialized_post_params',
                        'serialized_get_params',
                        'account_id',
                        'dt',
                    ),
                ),
                'tooltip' => '<p>Для того, чтобы <b>изменения вступили в силу</b>, необходимо выйти и войти в систему.</p>',
                'title'   => 'Редактирование',
                'submit'  => 'Сохранить',
            ),
            'add'    => array(
                'fields'  => array(
                    'Данные' => array(
                        'controller',
                        'action',
                        'serialized_post_params',
                        'serialized_get_params',
                        'account_id',
                        'dt',
                    ),
                ),
                'tooltip' => '<p>Для того, чтобы <b>изменения вступили в силу</b>, необходимо выйти и войти в систему.</p>',
                'title'   => 'Добавить',
                'submit'  => 'Добавить',
            ),
        ),
    );

    CmsGeneratorConfigRegister::add('storage', $cms_storage);