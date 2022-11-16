<?php
    $cms_fb_account_education = array(
        'table'     => DB_PREFIX . 'fb_account_education',
        'title'     => 'Facebook.com аккаунт - учреждения образования',
        'fields'    => array(
            'id'              => 'index',
            'fb_account_id'   => array(
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
            'school_name'     => 'input',
            'type'            => 'input',
            'year_end'        => 'input',
            'concentration_1' => 'input',
            'concentration_2' => 'input',
            'concentration_3' => 'input',
            'degree_name'     => 'input',
        ),

        'extra'     => array(
            'classes' => array(
                'table' => 'fb_account_class',
                'title' => 'Курсы',
                'field' => 'fb_account_education_id'
            ),
        ),

        'generator' => array(
            'fields' => array(
                'id'              => 'ID',
                'fb_account_id'   => 'Аккаунт из Facebook.com',
                'school_name'     => 'Идентификатор в Facebook.com',
                'type'            => 'Тип',
                'year_end'        => 'Год окончания',
                'concentration_1' => 'Специализация',
                'concentration_2' => 'Специализация',
                'concentration_3' => 'Специализация',
                'degree_name'     => 'Степень'
            ),
            'list'   => array(
                'fields'  => array(
                    'school_name',
                    'type',
                    'year_end',
                    'concentration_1',
                    'concentration_2',
                    'concentration_3',
                    'degree_name',
                ),
                'title'   => 'Список учреждений образования аккаунтов из Facebook.com',
                'sort_by' => array(
                    array(
                        'field' => 'school_name',
                        'desc'  => 'ASC'
                    ),
                )

            ),
            'edit'   => array(
                'fields'  => array(
                    'Данные' => array(
                        'fb_account_id',
                        'school_name',
                        'type',
                        'year_end',
                        'concentration_1',
                        'concentration_2',
                        'concentration_3',
                        'degree_name',
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
                        'school_name',
                        'type',
                        'year_end',
                        'concentration_1',
                        'concentration_2',
                        'concentration_3',
                        'degree_name',
                    ),
                ),
                'tooltip' => '<p>Для того, чтобы <b>изменения вступили в силу</b>, необходимо выйти и войти в систему.</p>',
                'title'   => 'Добавить',
                'submit'  => 'Добавить',
            ),
        ),
    );

    CmsGeneratorConfigRegister::add('fb_account_education', $cms_fb_account_education);