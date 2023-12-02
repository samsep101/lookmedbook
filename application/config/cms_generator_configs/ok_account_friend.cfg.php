<?php
    $cms_ok_account_friend = array(
        'table'     => DB_PREFIX . 'ok_account_friend',
        'title'     => 'Oдноклассники аккаунт - друзья',
        'fields'    => array(
            'id'            => 'index',
            'ok_account_id' => array(
                'type'        => 'category',
                'cross_name'  => 'last_name',
                'cross_index' => 'id',
                'cross_table' => DB_PREFIX . 'ok_account',
                'first'       => array(
                    '0' => '',
                ),
                'filter'      => 'true',
                'sort_by'     => 'last_name',
            ),
            'uid'           => 'input',
            'profile_url'   => 'input',
        ),

        'generator' => array(
            'fields' => array(
                'id'            => 'ID',
                'ok_account_id' => 'Аккаунт из Oдноклассники',
                'uid'           => 'Идентификатор в Oдноклассники',
                'profile_url'   => 'Ссылка на профиль',
            ),
            'list'   => array(
                'fields'  => array(
                    'uid',
                    'profile_url',
                ),
                'title'   => 'Список друзей аккаунтов из Oдноклассники',
                'sort_by' => array(
                    array(
                        'field' => 'uid',
                        'desc'  => 'ASC'
                    ),
                )

            ),
            'edit'   => array(
                'fields'  => array(
                    'Данные' => array(
                        'ok_account_id',
                        'uid',
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
                        'ok_account_id',
                        'uid',
                        'profile_url'
                    ),
                ),
                'tooltip' => '<p>Для того, чтобы <b>изменения вступили в силу</b>, необходимо выйти и войти в систему.</p>',
                'title'   => 'Добавить',
                'submit'  => 'Добавить',
            ),
        ),
    );

    CmsGeneratorConfigRegister::add('ok_account_friend', $cms_ok_account_friend);