<?php
    $cms_ok_account = array(
        'table'     => DB_PREFIX . 'ok_account',
        'title'     => 'Oдноклассники аккаунты',
        'fields'    => array(
            'id'                   => 'index',
            'account_id'           => array(
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
            'uid'                  => 'input',
            'first_name'           => 'input',
            'last_name'            => 'input',
            'profile_url'          => 'input',
            'age'                  => 'input',
            'is_account_connected' => array('type' => 'checkbox', 'label' => 'Да/Нет'),
        ),

        'extra'     => array(

            'friends' => array(
                'table' => 'ok_account_friend',
                'title' => 'Друзья',
                'field' => 'ok_account_id'
            ),

        ),

        'generator' => array(
            'fields' => array(
                'id'                   => 'ID',
                'account_id'           => 'Аккаунт',
                'uid'                  => 'Идентификатор',
                'first_name'           => 'Имя',
                'last_name'            => 'Фамилия',
                'profile_url'          => 'Ссылка на профиль',
                'age'                  => 'Возраст',
                'is_account_connected' => 'Присоединен к аккаунту в системе',
            ),
            'list'   => array(
                'fields'  => array(
                    'account_id',
                    'uid',
                    'last_name',
                    'first_name',
                    'profile_url',
                    'is_account_connected'
                ),
                'title'   => 'Список аккаунтов из Одноклассники',
                'sort_by' => array(
                    array(
                        'field' => 'last_name',
                        'desc'  => 'ASC'
                    ),
                )

            ),
            'edit'   => array(
                'fields'  => array(
                    'Данные' => array(
                        'account_id',
                        'uid',
                        'first_name',
                        'last_name',
                        'profile_url',
                        'age',
                        'is_account_connected'
                    ),
                ),
                'tooltip' => '<p>Для того, чтобы <b>изменения вступили в силу</b>, необходимо выйти и войти в систему.</p>',
                'title'   => 'Редактирование',
                'submit'  => 'Сохранить',
            ),
            'add'    => array(
                'fields'  => array(
                    'Данные' => array(
                        'account_id',
                        'uid',
                        'first_name',
                        'last_name',
                        'profile_url',
                        'age',
                        'is_account_connected'
                    ),
                ),
                'tooltip' => '<p>Для того, чтобы <b>изменения вступили в силу</b>, необходимо выйти и войти в систему.</p>',
                'title'   => 'Добавить',
                'submit'  => 'Добавить',
            ),
        ),
    );

    CmsGeneratorConfigRegister::add('ok_account', $cms_ok_account);