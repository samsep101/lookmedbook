<?php
    $cms_fb_account_class = array(
        'table'     => DB_PREFIX . 'fb_account_class',
        'title'     => 'Facebook.com аккаунт - курсы',
        'fields'    => array(
            'id'                      => 'index',
            'fb_account_education_id' => array(
                'type'        => 'category',
                'cross_name'  => 'school_name',
                'cross_index' => 'id',
                'cross_table' => DB_PREFIX . 'fb_account_education',
                'first'       => array(
                    '0' => '',
                ),
                'filter'      => 'true',
                'sort_by'     => 'school_name',
            ),
            'fb_system_id'            => 'input',
            'name'                    => 'input',
            'description'             => 'text',
        ),

        'generator' => array(
            'fields' => array(
                'id'                      => 'ID',
                'fb_account_education_id' => 'Учреждение образования из Facebook.com',
                'fb_system_id'            => 'Идентификатор в Facebook.com',
                'name'                    => 'Название',
                'description'             => 'Описание'
            ),
            'list'   => array(
                'fields'  => array(
                    'name',
                    'description'
                ),
                'title'   => 'Список курсов аккаунтов из Facebook.com',
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
                        'fb_account_education_id',
                        'fb_system_id',
                        'name',
                        'description'
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
                'submit'  => 'Добавить новый курс',
            ),
        ),
    );

    CmsGeneratorConfigRegister::add('fb_account_class', $cms_fb_account_class);