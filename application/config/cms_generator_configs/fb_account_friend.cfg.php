<?php
    $cms_fb_account_friend = array(
        'table'     => DB_PREFIX . 'fb_account_friend',
        'title'     => 'Facebook.com аккаунт - друзья',
        'fields'    => array(
            'id'            => 'index',
            'fb_account_id' => array(
                'type'        => 'category',
                'cross_name'  => 'last_name',
                'cross_index' => 'id',
                'cross_table' => DB_PREFIX . 'fb_account',
                'first'       => array(
                    '0' => '',
                ),
                'filter'      => 'true',
                'sort_by'     => 'last_name',
            ),
            'fb_system_id'  => 'input',
            'first_name'    => 'input',
            'last_name'     => 'input',
            'profile_url'   => 'input',
        ),

        'generator' => array(
            'fields' => array(
                'id'            => 'ID',
                'fb_account_id' => 'Аккаунт из Facebook.com',
                'fb_system_id'  => 'Идентификатор в Facebook.com',
                'first_name'    => 'Имя',
                'last_name'     => 'Отчество',
                'profile_url'   => 'Ссылка на профиль',
            ),
            'list'   => array(
                'fields'  => array(
                    'first_name',
                    'last_name',
                    'profile_url',
                ),
                'title'   => 'Список друзей аккаунтов из Facebook.com',
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
                        'fb_account_id',
                        'fb_system_id',
                        'first_name',
                        'last_name',
                        'profile_url'
                    ),
                ),
                'tooltip' => '<p>Для того, чтобы <b>изменения вступили в силу</b>, необходимо выйти и войти в систему.</p>',
                'title'   => 'Редактирование',
                'submit'  => 'Сохранить',
            ),
            'add'    => array(
                'fields'  => array(
                    'Данные' => array(
                        'fb_account_id',
                        'fb_system_id',
                        'first_name',
                        'last_name',
                        'profile_url'
                    ),
                ),
                'tooltip' => '<p>Для того, чтобы <b>изменения вступили в силу</b>, необходимо выйти и войти в систему.</p>',
                'title'   => 'Добавить',
                'submit'  => 'Добавить',
            ),
        ),
    );

    CmsGeneratorConfigRegister::add('fb_account_friend', $cms_fb_account_friend);