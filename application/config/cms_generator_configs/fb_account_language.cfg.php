<?php
    $cms_fb_account_language = array(
        'table'     => DB_PREFIX . 'fb_account_language',
        'title'     => 'Facebook.com аккаунт - языки',
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
            'name'          => 'input',
        ),

        'generator' => array(
            'fields' => array(
                'id'            => 'ID',
                'fb_account_id' => 'Аккаунт из Facebook.com',
                'fb_system_id'  => 'Идентификатор в Facebook.com',
                'name'          => 'Название',
            ),
            'list'   => array(
                'fields'  => array(
                    'name',
                ),
                'title'   => 'Список языков аккаунтов из Facebook.com',
                'sort_by' => array(
                    array(
                        'field' => 'name',
                        'desc'  => 'ASC'
                    ),
                )

            ),
            'edit'   => array(
                'fields'  => array(
                    'Данные' => array(
                        'fb_account_id',
                        'fb_system_id',
                        'name',
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
                        'name',
                        'category'
                    ),
                ),
                'tooltip' => '<p>Для того, чтобы <b>изменения вступили в силу</b>, необходимо выйти и войти в систему.</p>',
                'title'   => 'Добавить',
                'submit'  => 'Добавить',
            ),
        ),
    );

    CmsGeneratorConfigRegister::add('fb_account_language', $cms_fb_account_language);