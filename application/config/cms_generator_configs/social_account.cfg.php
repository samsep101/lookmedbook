<?php
    $social_account = array(
        'table'     => DB_PREFIX . 'social_account', /*имя таблицы*/
        'title'     => 'Список аккаунтов соц. сетей', /*меняется "ролей"*/
        'fields'    => array(
            'id'                => 'index', /*всегда*/
            'uid'               => 'input',
            'email'             => 'input',
            'first_name'        => 'input',
            'last_name'         => 'input',
            'account_id'        => array(
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
            'profile_url'       => 'input',
            'social_network_id' => array(
                'type'        => 'category',
                'cross_name'  => 'name',
                'cross_index' => 'id',
                'cross_table' => DB_PREFIX . 'social_network',
                'first'       => array(
                    '0' => '',
                ),
                'filter'      => 'true',
                'sort_by'     => 'name',
            ),
        ),
        'generator' => array(
            'fields' => array(
                'id'                => 'ID', /*всегда*/
                'uid'               => 'Идентификатор',
                'email'             => 'Эл. почта в соц. сети',
                'first_name'        => 'Имя',
                'last_name'         => 'Фамилия',
                'account_id'        => 'Аккаунт',
                'profile_url'       => 'Адрес профиля',
                'social_network_id' => 'Соц. сеть',
            ),
            'list'   => array(
                'fields'  => array('id', 'account_id', 'social_network_id'), /*поля кот. отображаются в списке "суперадминистратор"*/
                'title'   => 'Список аккаунтов соц. сетей',
                'sort_by' => array(
                    array(
                        'field' => 'account_id',
                        'desc'  => 'ASC'
                    ),
                )
            ),
            'edit'   => array(
                'fields' => array(
                    'Аккаунт' => array(
                        'id',
                        'uid',
                        'email',
                        'first_name',
                        'last_name',
                        'account_id',
                        'profile_url',
                        'social_network_id',
                    ),
                ),
                'title'  => 'Редактирование',
                'submit' => 'Сохранить',
            ),
            'add'    => array(
                'fields' => array(
                    'Аккаунт' => array(
                        'id',
                        'uid',
                        'email',
                        'first_name',
                        'last_name',
                        'account_id',
                        'profile_url',
                        'social_network_id',
                    ),
                ),
                'title'  => 'Создать',
                'submit' => 'Создать',
            ),
        ),
    );

    CmsGeneratorConfigRegister::add('social_account', $social_account);