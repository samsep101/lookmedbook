<?php
    $cms_mailru_account_friend = array(
        'table'     => DB_PREFIX . 'mailru_account_friend',
        'title'     => 'Mail.ru аккаунт - друзья',
        'fields'    => array(
            'id'                => 'index',
            'mailru_account_id' => array(
                'type'        => 'category',
                'cross_name'  => 'last_name',
                'cross_index' => 'id',
                'cross_table' => DB_PREFIX . 'mailru_account',
                'first'       => array(
                    '0' => '',
                ),
                'filter'      => 'true',
                'sort_by'     => 'last_name',
            ),
            'uid'               => 'input',
            'first_name'        => 'input',
            'last_name'         => 'input',
            'profile_url'       => 'input',
        ),

        'generator' => array(
            'fields' => array(
                'id'                => 'ID',
                'mailru_account_id' => 'Аккаунт из Mail.ru',
                'uid'               => 'Идентификатор в Mail.ru',
                'first_name'        => 'Имя',
                'last_name'         => 'Отчество',
                'profile_url'       => 'Ссылка на профиль',
            ),
            'list'   => array(
                'fields'  => array(
                    'first_name',
                    'last_name',
                    'profile_url',
                ),
                'title'   => 'Список друзей аккаунтов из Mail.ru',
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
                        'mailru_account_id',
                        'uid',
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
                        'mailru_account_id',
                        'uid',
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

    CmsGeneratorConfigRegister::add('mailru_account_friend', $cms_mailru_account_friend);