<?php

$metro_station_to_clinic = [
    'table' => DB_PREFIX . 'metro_station_to_clinic',
    'title' => 'Список связей Станция метро-Клиника',
    'fields' => [
        'id' => 'index',
        'clinic_id' => [
            'type' => 'category',
            'cross_name' => 'name',
            'cross_index' => 'id',
            'cross_table' => DB_PREFIX . 'clinic',
            'first' => [
                '0' => '',
            ],
            'filter' => 'true',
            'sort_by' => 'name',
        ],
        'metro_station_id' => [
            'type' => 'category',
            'cross_name' => 'name',
            'cross_index' => 'id',
            'cross_table' => DB_PREFIX . 'metro_station',
            'first' => [
                '0' => '',
            ],
            'filter' => 'true',
            'sort_by' => 'name',
        ],
    ],
    'generator' => [
        'fields' => [
            'id' => 'ID',
            'clinic_id' => 'Клиника',
            'metro_station_id' => 'Станция метро',
        ],
        'list' => [
            'fields' => ['clinic_id', 'metro_station_id'],
            'title' => 'Список связей Станция метро-Клиника',
            'sort_by' => [
                [
                    'field' => 'clinic_id',
                    'desc' => 'ASC',
                ],
            ],
        ],
        'edit' => [
            'fields' => [
                'Связь' => [
                    'clinic_id',
                    'metro_station_id',
                ],
            ],
            'title' => 'Редактирование',
            'submit' => 'Сохранить',
        ],
        'add' => [
            'fields' => [
                'Связь' => [
                    'clinic_id',
                    'metro_station_id',
                ],
            ],
            'title' => 'Создание',
            'submit' => 'Создать',
        ],
    ],
];

CmsGeneratorConfigRegister::add('metro_station_to_clinic', $metro_station_to_clinic);
