<?php

    $clinic_phone = array(
        'table'     => DB_PREFIX . 'clinic_phone',
        'title'     => 'Телефонные номера',
        'fields'    => array(
            'id'           => 'index',
            'clinic_id'    => array(
                'type'        => 'category',
                'cross_name'  => 'name',
                'cross_index' => 'id',
                'cross_table' => DB_PREFIX . 'clinic',
                'first'       => array(
                    '0' => '',
                ),
                'filter'      => 'true',
                'sort_by'     => 'name',
            ),
            'phone_number' => 'input',
        ),
        'generator' => array(
            'fields' => array(
                'id'           => 'ID',
                'clinic_id'    => 'Клиника',
                'phone_number' => 'Описание',
            ),
            'list'   => array(
                'fields'  => array(
                    'clinic_id',
                    'phone_number',
                ),
                'title'   => 'Телефонные номера',
                'sort_by' => array(
                    array(
                        'field' => 'clinic_id',
                        'desc'  => 'ASC'
                    ),
                )
            ),
            'edit'   => array(
                'fields'  => array(
                    'Информация' => array(
                        'clinic_id',
                        'phone_number',
                    ),
                ),
                'tooltip' => '<p>Для того, чтобы <b>изменения вступили в силу</b>, необходимо выйти и войти в систему.</p>',
                'title'   => 'Редактирование',
                'submit'  => 'Сохранить',
            ),
            'add'    => array(
                'fields'  => array(
                    'Информация' => array(
                        'clinic_id',
                        'phone_number'
                    ),
                ),
                'tooltip' => '<p>Для того, чтобы <b>изменения вступили в силу</b>, необходимо выйти и войти в систему.</p>',
                'title'   => 'Добавить',
                'submit'  => 'Добавить',
            ),
        ),
    );

    CmsGeneratorConfigRegister::add('clinic_phone', $clinic_phone);