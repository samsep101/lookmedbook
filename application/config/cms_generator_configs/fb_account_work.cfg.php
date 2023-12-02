<?php
    $cms_fb_account_work = array(
        'table'     => DB_PREFIX . 'fb_account_work',
        'title'     => 'Facebook.com аккаунт - работа',
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
            'employer_name' => 'input',
            'description'   => 'text',
            'position_name' => 'input',
            'dt_start'      => 'date',
            'dt_end'        => 'date',
            'city_id'       => array(
                'type'        => 'category',
                'cross_name'  => 'name',
                'cross_index' => 'id',
                'cross_table' => DB_PREFIX . 'city',
                'first'       => array(
                    '0' => '',
                ),
                'filter'      => 'true',
            )
        ),

        'generator' => array(
            'fields' => array(
                'id'            => 'ID',
                'fb_account_id' => 'Аккаунт из Facebook.com',
                'employer_name' => 'Название компании',
                'description'   => 'Описание',
                'position_name' => 'Должность',
                'dt_start'      => 'Период: с',
                'dt_end'        => 'по ',
                'city_id'       => 'Город'
            ),
            'list'   => array(
                'fields'  => array(
                    'employer_name',
                    'position_name',
                    'dt_start',
                    'dt_end',
                    'city_id'
                ),
                'title'   => 'Список мест работ аккаунтов из Facebook.com',
                'sort_by' => array(
                    array(
                        'field' => 'employer_name',
                        'desc'  => 'ASC'
                    ),
                )

            ),
            'edit'   => array(
                'fields'  => array(
                    'Данные' => array(
                        'fb_account_id',
                        'employer_name',
                        'description',
                        'position_name',
                        'dt_start',
                        'dt_end',
                        'city_id'
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
                        'employer_name',
                        'description',
                        'position_name',
                        'dt_start',
                        'dt_end',
                        'city_id'
                    ),
                ),
                'tooltip' => '<p>Для того, чтобы <b>изменения вступили в силу</b>, необходимо выйти и войти в систему.</p>',
                'title'   => 'Добавить новое место работы',
                'submit'  => 'Добавить новое место работы',
            ),
        ),
    );

    CmsGeneratorConfigRegister::add('fb_account_work', $cms_fb_account_work);