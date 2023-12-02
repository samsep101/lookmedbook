<?php
$clinic_to_services = array(
    'table'     => DB_PREFIX . 'clinic_to_services',
    'title'     => 'Услуги клиник',
    'fields'    => array(
        'id'             => 'index',
        'clinic_id'      => array(
            'type'        => 'category',
            'cross_name'  => 'name',
            'cross_index' => 'id',
            'cross_table' => DB_PREFIX . 'clinic',
            'filter'      => 'true',
            'sort_by'     => 'name'
        ),
        'clinic_service_id' => array(
            'type'        => 'category',
            'cross_name'  => 'name',
            'cross_index' => 'id',
            'cross_table' => DB_PREFIX . 'clinic_services',
            'filter'      => 'true',
            'sort_by'     => 'name'
        ),
    ),

    'generator' => array(
        'fields' => array(
            'id'             => 'ID',
            'clinic_id'      => 'Клиника',
            'clinic_service_id' => 'Услуга клиники'
        ),
        'list'   => array(
            'fields'  => array(
                'id',
                'clinic_id',
                'clinic_type_id'
            ),
            'title'   => 'Услуги клиник',
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
                    'clinic_service_id'
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
                    'clinic_service_id'
                ),
            ),
            'tooltip' => '<p>Для того, чтобы <b>изменения вступили в силу</b>, необходимо выйти и войти в систему.</p>',
            'title'   => 'Добавить',
            'submit'  => 'Добавить',
        ),
    ),
);

CmsGeneratorConfigRegister::add('clinic_to_services', $clinic_to_services);