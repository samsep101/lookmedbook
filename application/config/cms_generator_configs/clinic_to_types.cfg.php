<?php
$clinic_to_types = array(
    'table'     => DB_PREFIX . 'clinic_to_types',
    'title'     => 'Типы клиник',
    'fields'    => array(
        'id'             => 'index',
        'clinic_id'      => array(
            'type'        => 'category',
            'cross_name'  => 'name',
            'cross_index' => 'id',
            'cross_table' => DB_PREFIX . 'clinic',
            'filter'      => 'true',
            'sort_by'     => 'name',
            'first'       => array(
                0 => '',
            )
        ),
        'clinic_type_id' => array(
            'type'        => 'category',
            'cross_name'  => 'name',
            'cross_index' => 'id',
            'cross_table' => DB_PREFIX . 'clinic_type',
            'filter'      => 'true',
            'sort_by'     => 'name',
            'first'       => array(
                0 => '',
            )
        ),
    ),

    'generator' => array(
        'fields' => array(
            'id'             => 'ID',
            'clinic_id'      => 'Клиника',
            'clinic_type_id' => 'Тип клиники'
        ),
        'list'   => array(
            'fields'  => array(
                'id',
                'clinic_id',
                'clinic_type_id'
            ),
            'title'   => 'Типы клиник',
            'sort_by' => array(
                array(
                    'field' => 'id',
                    'desc'  => 'DESC'
                ),
            ),
        ),
        'edit'   => array(
            'fields'  => array(
                'Данные' => array(
                    'id',
                    'clinic_id',
                    'clinic_type_id'
                ),
            ),
            'tooltip' => '<p>Для того, чтобы <b>изменения вступили в силу</b>, необходимо выйти и войти в систему.</p>',
            'title'   => 'Редактирование',
            'submit'  => 'Сохранить',
        ),
        'add'    => array(
            'fields'  => array(
                'Данные' => array(
                    'clinic_id',
                    'clinic_type_id'
                ),
            ),
            'tooltip' => '<p>Для того, чтобы <b>изменения вступили в силу</b>, необходимо выйти и войти в систему.</p>',
            'title'   => 'Добавить',
            'submit'  => 'Добавить',
        ),
    ),
);

CmsGeneratorConfigRegister::add('clinic_to_types', $clinic_to_types);