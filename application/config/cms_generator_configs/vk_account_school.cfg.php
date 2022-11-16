<?php
    $cms_vk_account_school = array(
        'table'     => DB_PREFIX . 'vk_account_school',
        'title'     => 'ВКонтакте аккаунт - школы/ССУЗ',
        'fields'    => array(
            'id'             => 'index',
            'vk_account_id'  => array(
                'type'        => 'category',
                'cross_name'  => 'last_name',
                'cross_index' => 'id',
                'cross_table' => DB_PREFIX . 'vk_account',
                'first'       => array(
                    '0' => '',
                ),
                'filter'      => 'true',
                'sort_by'     => 'last_name',
            ),
            'city_id'        => array(
                'type'        => 'category',
                'cross_name'  => 'name',
                'cross_index' => 'id',
                'cross_table' => DB_PREFIX . 'city',
                'first'       => array(
                    '0' => '',
                ),
                'filter'      => 'true',
            ),
            'name'           => 'input',
            'year_start'     => 'input',
            'year_end'       => 'input',
            'year_graduated' => 'input',
            'speciality'     => 'input',
            'class'          => 'input',
        ),

        'generator' => array(
            'fields' => array(
                'id'             => 'ID',
                'vk_account_id'  => 'Аккаунт из ВКонтакте',
                'city_id'        => 'Город',
                'name'           => 'Название',
                'year_start'     => 'Год начала обучения',
                'year_end'       => 'Год окончания обучения',
                'year_graduated' => 'Год выпуска',
                'speciality'     => 'Специальность',
                'class'          => 'Класс',
            ),
            'list'   => array(
                'fields'  => array(
                    'name',
                    'year_start',
                    'year_end',
                    'year_graduated',
                    'speciality',
                    'class',
                    'city_id'
                ),
                'title'   => 'Список школ/ССУЗ аккаунтов из ВКонтакте',
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
                        'vk_account_id',
                        'name',
                        'year_start',
                        'year_end',
                        'year_graduated',
                        'speciality',
                        'class',
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
                        'vk_account_id',
                        'name',
                        'year_start',
                        'year_end',
                        'year_graduated',
                        'speciality',
                        'class',
                        'city_id'
                    ),
                ),
                'tooltip' => '<p>Для того, чтобы <b>изменения вступили в силу</b>, необходимо выйти и войти в систему.</p>',
                'title'   => 'Добавить',
                'submit'  => 'Добавить',
            ),
        ),
    );

    CmsGeneratorConfigRegister::add('vk_account_school', $cms_vk_account_school);