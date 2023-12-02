<?php
$cms_mapping = [
    'table'  => DB_PREFIX . 'docdoc_service_category_mapping_tree',
    'title'  => 'Мэппинг услуг DocDoc',
    'fields' => [
        'id'                => 'index',
        'service_id'        => [
            'type'        => 'smartcategory',
            'cross_name'  => 'full_name',
            'cross_index' => 'docdoc_id',
            'cross_table' => DB_PREFIX . 'docdoc_service_category',
            'first'       => [
                '0' => '',
            ],
            'filter'      => 'true',
            'sort_by'     => 'full_name',
            'style'       => 'max-width: 600px;',
        ],
        'mapped_service_id' => [
            'type'        => 'smartcategory',
            'cross_name'  => 'full_name',
            'cross_index' => 'docdoc_id',
            'cross_table' => DB_PREFIX . 'docdoc_service_category',
            'first'       => [
                '0' => '',
            ],
            'filter'      => 'true',
            'sort_by'     => 'full_name',
            'style'       => 'max-width: 600px;'
        ],
    ],

    'generator' => [
        'fields' => [
            'id'                => 'id',
            'service_id'        => 'Услуга-двойник',
            'mapped_service_id' => 'Услуга, отображаемая на сайте',
        ],
        'list'   => [
            'fields'  => [
                'service_id',
                'mapped_service_id',
            ],
            'title'   => 'Мэппинг услуг',
            'sort_by' => [
                [
                    'field' => 'id',
                    'desc'  => 'ASC'
                ],
            ],
        ],
        'edit'   => [
            'fields' => [
                'Данные' => [
                    'service_id',
                    'mapped_service_id'
                ],
            ],
            'title'  => 'Редактирование',
            'submit' => 'Сохранить',
        ],
        'add'    => [
            'fields' => [
                'Данные' => [
                    'service_id',
                    'mapped_service_id',
                ],
            ],
            'title'  => 'Добавить',
            'submit' => 'Добавить',
        ],
    ],
];

CmsGeneratorConfigRegister::add('docdoc_service_category_mapping_tree', $cms_mapping);
