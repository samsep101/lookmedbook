<?php
    $user = array(
        'table'     => DB_PREFIX . 'user',
        'title'     => 'Администраторы',
        'fields'    => array(
            'id'       => 'index',
            'login'    => 'input',
            'password' => 'password',
            'email'    => 'input',
            'is_super' => array(
                'type'   => 'listvalue_acl',
                'values' => array(
                    '0' => 'Ограниченный доступ',
                    '1' => 'Супер админ',
                    '2' => 'Менеджер',
                ),
            ),
            'role_id'  => array(
                'type'        => 'category',
                'cross_name'  => 'name',
                'cross_index' => 'id',
                'cross_table' => DB_PREFIX . 'role',
                'first'       => array(
                    '0' => '',
                ),
                'sort_by'     => 'name',
            ),
        ),
        'extra'     => array(
            'clinic_to_user' => array(
                'table' => 'clinic_to_user',
                'title' => 'Клиника',
                'field' => 'user_id'
            ),
        ),
        'generator' => array(
            'required' => array('login' => 'exist', 'password' => 'text', 'email' => 'email'),
            'fields'   => array(
                'id'       => 'ID',
                'login'    => 'Логин',
                'password' => 'Пароль',
                'email'    => 'E-mail',
                'is_super' => 'Статус',
                'role_id'  => 'Роль'
            ),
            'list'     => array(
                'fields'  => array('login', 'email', 'is_super', 'role_id'),
                'title'   => 'Список администраторов',
                'sort_by' => array(
                    array(
                        'field' => 'email',
                        'desc'  => 'ASC'
                    ),
                ),


            ),
            'edit'     => array(
                'fields' => array(
                    'Основные данные' => array(
                        'login', 'password', 'email', 'is_super', 'role_id'
                    ),
                ),
                'title'  => 'Редактирование администратора',
                'submit' => 'Сохранить',
            ),
            'add'      => array(
                'fields' => array(
                    'Основные данные' => array(
                        'login', 'password', 'email', 'is_super', 'role_id'
                    ),
                ),
                'title'  => 'Добавление администратора',
                'submit' => 'Добавить',
            ),
        )
    );

    CmsGeneratorConfigRegister::add('user', $user);