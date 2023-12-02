<?php
    $cms_vk_account_university = array(
        'table'     => DB_PREFIX . 'vk_account_university',
        'title'     => 'ВКонтакте аккаунт - ВУЗ',
        'fields'    => array(
            'id'              => 'index',
            'vk_account_id'   => array(
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
            'city_id'         => array(
                'type'        => 'category',
                'cross_name'  => 'name',
                'cross_index' => 'id',
                'cross_table' => DB_PREFIX . 'city',
                'first'       => array(
                    '0' => '',
                ),
                'filter'      => 'true',
            ),
            'name'            => 'input',
            'faculty_name'    => 'input',
            'chair_name'      => 'input',
            'year_graduation' => 'input',
        ),

        'generator' => array(
            'fields' => array(
                'id'              => 'ID',
                'vk_account_id'   => 'Аккаунт из ВКонтакте',
                'city_id'         => 'Город',
                'name'            => 'Название',
                'faculty_name'    => 'Название факультета',
                'chair_name'      => 'Название кафедры',
                'year_graduation' => 'Год выпуска',
            ),
            'list'   => array(
                'fields'  => array(
                    'name',
                    'faculty_name',
                    'chair_name',
                    'year_graduation',
                    'city_id'
                ),
                'title'   => 'Список ВУЗ аккаунтов из ВКонтакте',
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
                        'faculty_name',
                        'chair_name',
                        'year_graduation',
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
                        'faculty_name',
                        'chair_name',
                        'year_graduation',
                        'city_id'
                    ),
                ),
                'tooltip' => '<p>Для того, чтобы <b>изменения вступили в силу</b>, необходимо выйти и войти в систему.</p>',
                'title'   => 'Добавить',
                'submit'  => 'Добавить',
            ),
        ),
    );

    CmsGeneratorConfigRegister::add('vk_account_university', $cms_vk_account_university);