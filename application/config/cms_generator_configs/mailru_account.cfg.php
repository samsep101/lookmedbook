<?php
    $cms_mailru_account = array(
        'table'     => DB_PREFIX . 'mailru_account',
        'title'     => 'Mail.ru аккаунты',
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
                'sort_by'     => 'name',
            ),
            'uid'                  => 'input',
            'first_name'           => 'input',
            'last_name'            => 'input',
            'nick_name'            => 'input',
            'profile_url'          => 'input',
            'status_text'          => 'text',
            'is_account_connected' => array('type' => 'checkbox', 'label' => 'Да/Нет'),
        ),

        'extra'     => array(

            'friends' => array(
                'table' => 'mailru_account_friend',
                'title' => 'Друзья',
                'field' => 'mailru_account_id'
            ),

        ),

        'generator' => array(
            'fields' => array(
                'id'                   => 'ID',
                'account_id'           => 'Аккаунт',
                'uid'                  => 'Идентификатор',
                'first_name'           => 'Имя',
                'last_name'            => 'Фамилия',
                'nick_name'            => 'Имя на сайте Mail.ru',
                'profile_url'          => 'Ссылка на профиль',
                'status_text'          => 'Статус',
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
                'title'   => 'Список аккаунтов из Mail.ru',
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
                        'nick_name',
                        'profile_url',
                        'status_text',
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
                        'nick_name',
                        'profile_url',
                        'status_text',
                        'is_account_connected'
                    ),
                ),
                'tooltip' => '<p>Для того, чтобы <b>изменения вступили в силу</b>, необходимо выйти и войти в систему.</p>',
                'title'   => 'Добавить',
                'submit'  => 'Добавить',
            ),
        ),
    );

    CmsGeneratorConfigRegister::add('mailru_account', $cms_mailru_account);