<?php

$specialtyToDiseaseDraft = [
    'table'     => DB_PREFIX . 'specialty_to_disease_draft',
    'title'     => 'Врачи',
    'fields' => [
        'id' => 'index',
        'disease_draft_id' => [
            'type'        => 'category',
            'cross_name'  => 'name',
            'cross_index' => 'id',
            'cross_table' => DB_PREFIX . 'disease_draft',
            'first'       => array(
                '0' => '',
            ),
            'filter'      => 'true',
            'sort_by'     => 'name',
        ],
        'specialty_id' => [
            'type'        => 'category',
            'cross_name'  => 'name',
            'cross_index' => 'id',
            'cross_table' => DB_PREFIX . 'specialty',
            'first'       => array(
                '0' => '',
            ),
            'filter'      => 'true',
            'sort_by'     => 'name',
        ],
        'is_adult' => 'checkbox',
        'is_male' => 'checkbox',
        'is_female' => 'checkbox',
        'is_children' => 'checkbox',
        'is_newborn' => 'checkbox',
        'is_pregnant' => 'checkbox',
        'main_flag' => 'checkbox',
    ],
    'generator' => [
        'fields' => [
            'id' => 'ID',
            'disease_draft_id' => 'Черновик статьи о заболевании',
            'specialty_id' => 'Специльность',
            'is_active' => 'Активность',
            'is_adult' => 'Для взрослых',
            'is_male' => 'Для мужчин',
            'is_female' => 'Для женщин',
            'is_children' => 'Для детей',
            'is_newborn' => 'Для младенцев',
            'is_pregnant' => 'Для беременных',
            'main_flag' => 'Блок является главным?',
        ],
        'list' => [
            'fields' => [
                'specialty_id',
                'is_active',
                'is_adult',
                'is_male',
                'is_female',
                'is_children',
                'is_newborn',
                'is_pregnant',
                'main_flag',
            ],
            'title' => 'Подходящие специальности'
        ],
        'edit' => [
            'fields' => [
                'Данные' => [
                    'disease_draft_id',
                    'specialty_id',
                    'is_active',
                    'is_adult',
                    'is_male',
                    'is_female',
                    'is_children',
                    'is_newborn',
                    'is_pregnant',
                    'main_flag',
                ],
            ],
            'title' => 'Редактирование',
            'submit' => 'Сохранить',
        ],
        'add' => [
            'fields' => [
                'Данные' => [
                    'disease_draft_id',
                    'specialty_id',
                    'is_active',
                    'is_adult',
                    'is_male',
                    'is_female',
                    'is_children',
                    'is_newborn',
                    'is_pregnant',
                    'main_flag',
                ],
            ],
            'title' => 'Добавить подходящую специальность',
            'submit' => 'Добавить',
        ]
    ],
];

CmsGeneratorConfigRegister::add('specialty_to_disease_draft', $specialtyToDiseaseDraft);