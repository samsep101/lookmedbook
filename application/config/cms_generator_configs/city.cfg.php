<?php
    $cms_city = array(
        'table'     => DB_PREFIX . 'city',
        'title'     => 'Города',
        'fields'    => array(
            'id'           => 'index',
            'name'         => 'input',
            'country_id'   => array(
                'type'        => 'category',
                'cross_name'  => 'name',
                'cross_index' => 'id',
                'cross_table' => DB_PREFIX . 'country',
                'first'       => array(
                    '0' => '',
                ),
                'filter'      => 'true',
                'sort_by'     => 'name',
            ),
            'service_flag' => array('type' => 'checkbox', 'label' => 'Да/Нет'),
        ),

        'generator' => array(
            'fields' => array(
                'id'           => 'ID',
                'name'         => 'ФИО',
                'country_id'   => 'Название страны',
                'service_flag' => 'Наличие клиник/врачей',
            ),
            'list'   => array(
                'fields'  => array(
                    'name',
                    'country_id',
                    'service_flag'
                ),
                'title'   => 'Список городов',
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
                        'name',
                        'country_id',
                        'service_flag'
                    ),
                ),
                'tooltip' => '<p>Для того, чтобы <b>изменения вступили в силу</b>, необходимо выйти и войти в систему.</p>',
                'title'   => 'Редактирование',
                'submit'  => 'Сохранить',
            ),
            'add'    => array(
                'fields'  => array(
                    'Данные' => array(
                        'name',
                        'country_id',
                        'service_flag'
                    ),
                ),
                'tooltip' => '<p>Для того, чтобы <b>изменения вступили в силу</b>, необходимо выйти и войти в систему.</p>',
                'title'   => 'Добавить',
                'submit'  => 'Добавить',
            ),
        ),
    );

    CmsGeneratorConfigRegister::add('city', $cms_city);