<?php
    $grant = array(
        'table'     => DB_PREFIX . 'grant', /*имя таблицы*/
        'title'     => 'Права на действия контроллеров', /*меняется "ролей"*/
        'fields'    => array(
            'id'            => 'index', /*всегда*/
            'role_id'       => array(
                'type'        => 'category',
                'cross_name'  => 'name',
                'cross_index' => 'id',
                'cross_table' => DB_PREFIX . 'role',
                'first'       => array(
                    '0' => '',
                ),
                'filter'      => 'true',
                'sort_by'     => 'name',
            ),
            'controller_id' => array(
                'type'        => 'category',
                'cross_name'  => 'name',
                'cross_index' => 'id',
                'cross_table' => DB_PREFIX . 'controller',
                'cross_order' => 'name',
                'first'       => array(
                    '0' => '',
                ),
                'filter'      => 'true',
                'sort_by'     => 'name',
            ),
            'list'          => array('type' => 'checkbox', 'label' => ''),
            'add'           => array('type' => 'checkbox', 'label' => ''),
            'edit'          => array('type' => 'checkbox', 'label' => ''),
            'delete'        => array('type' => 'checkbox', 'label' => ''),
            'delete_list'   => array('type' => 'checkbox', 'label' => ''),
            'save'          => array('type' => 'checkbox', 'label' => ''),

        ),
        'generator' => array(
            'fields' => array(
                'id'            => 'ID',
                'role_id'       => 'Роль',
                'controller_id' => 'Контроллер',
                'list'          => 'Список',
                'add'           => 'Добавление',
                'edit'          => 'Редактирование',
                'delete'        => 'Удаление',
                'delete_list'   => 'Множ. удаление',
                'save'          => 'Сохранение'
            ),
            'list'   => array(
                'fields'  => array('role_id', 'controller_id', 'list', 'add', 'edit', 'delete', 'delete_list', 'save'),
                'title'   => 'Права на действия контроллеров',
                'sort_by' => array(
                    array(
                        'field' => 'role_id',
                        'desc'  => 'ASC'
                    ),
                )
            ),
            'edit'   => array(
                'fields' => array(
                    'Права' => array(
                        'role_id', 'controller_id', 'list', 'add', 'edit', 'delete', 'delete_list', 'save'
                    ),
                ),
                'title'  => 'Редактирование',
                'submit' => 'Сохранить',
            ),
            'add'    => array(
                'fields' => array(
                    'Права' => array(
                        'role_id', 'controller_id', 'list', 'add', 'edit', 'delete', 'delete_list', 'save'
                    ),
                ),
                'title'  => 'Создать',
                'submit' => 'Создать',
            ),
        ),
    );

    CmsGeneratorConfigRegister::add('grant', $grant);