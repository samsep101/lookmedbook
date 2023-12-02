<?php
    $metro_transplantation = array(
        'table'     => DB_PREFIX . 'metro_transplantation', /*имя таблицы*/
        'title'     => 'Список станций пересадки', /*меняется "ролей"*/
        'fields'    => array(
            'id'                         => 'index', /*всегда*/
            'metro_station_id'           => array(
                'type'        => 'category',
                'cross_name'  => 'name_with_city_name',
                'cross_index' => 'id',
                'cross_table' => DB_PREFIX . 'metro_station',
                'first'       => array(
                    '0' => '',
                ),
                'filter'      => 'true',
                'sort_by'     => 'name_with_city_name',
            ),
            'transplantation_station_id' => array(
                'type'        => 'category',
                'cross_name'  => 'name_with_city_name',
                'cross_index' => 'id',
                'cross_table' => DB_PREFIX . 'metro_station',
                'first'       => array(
                    '0' => '',
                ),
                'filter'      => 'true',
                'sort_by'     => 'name_with_city_name',
            ),
        ),
        'generator' => array(
            'fields' => array(
                'id'                         => 'ID',
                'metro_station_id'           => 'Станция метро',
                'transplantation_station_id' => 'Станция пересадки',
            ),
            'list'   => array(
                'fields'  => array('metro_station_id', 'transplantation_station_id'), /*поля кот. отображаются в списке "суперадминистратор"*/
                'title'   => 'Список станций пересадки',
                'sort_by' => array(
                    array(
                        'field' => 'metro_station_id',
                        'desc'  => 'ASC'
                    ),
                )
            ),
            'edit'   => array(
                'fields' => array(
                    'Пересадка' => array(
                        'metro_station_id', 'transplantation_station_id'
                    ),
                ),
                'title'  => 'Редактирование',
                'submit' => 'Сохранить',
            ),
            'add'    => array(
                'fields' => array(
                    'Пересадка' => array(
                        'metro_station_id', 'transplantation_station_id'
                    ),
                ),
                'title'  => 'Создать',
                'submit' => 'Создать',
            ),
        ),
    );

    CmsGeneratorConfigRegister::add('metro_transplantation', $metro_transplantation);