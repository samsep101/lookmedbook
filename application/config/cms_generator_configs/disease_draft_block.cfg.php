<?php

$diseaseDraftBlock = [
    'table' => DB_PREFIX . 'disease_draft_block',
    'title' => 'Информационные блоки заболеваний',
    'fields' => [
        'id' => 'index',
        'disease_draft_id' => [
            'type' => 'category',
            'cross_name' => 'name',
            'cross_index' => 'id',
            'cross_table' => DB_PREFIX . 'disease_draft',
            'first' => array(
                '0' => '',
            ),
            'filter' => 'true',
            'sort_by' => 'name',
        ],
        'disease_block_type_id' => [
            'type' => 'listvalue',
            'values' => DiseaseDraftBlockModel::getDiseaseBlockTypes(),
        ],

        'title' => 'input',
        'is_active' => 'checkbox',
        'is_adult' => 'checkbox',
        'is_male' => 'checkbox',
        'is_female' => 'checkbox',
        'is_children' => 'checkbox',
        'is_newborn' => 'checkbox',
        'is_pregnant' => 'checkbox',
        'main_flag' => 'checkbox',
        'content' => 'htmlarea',
    ],
    'generator' => [
        'fields' => [
            'id' => 'ID',

            'title' => 'название блока',
            'disease_draft_id' => 'Черновик статьи о заболевании',
            'disease_block_type_id' => 'Тип блока дополнительной информации',
            'is_active' => 'Активность',
            'is_adult' => 'Для взрослых',
            'is_male' => 'Для мужчин',
            'is_female' => 'Для женщин',
            'is_children' => 'Для детей',
            'is_newborn' => 'Для младенцев',
            'is_pregnant' => 'Для беременных',
            'main_flag' => 'Блок является главным?',
            'content' => 'Содержание',
        ],
        'list' => [
            'fields' => [
                'disease_block_type_id',
                'title',
                'is_adult',
                'is_male',
                'is_female',
                'is_children',
                'is_newborn',
                'is_pregnant',
                'is_active',
            ],
            'title' => 'Информационные блоки заболеваний'
        ],
        'edit' => [
            'fields' => [
                'Данные' => [
                    'disease_block_type_id',
                    'title',
                    'is_active',
                    'is_adult',
                    'is_male',
                    'is_female',
                    'is_children',
                    'is_newborn',
                    'is_pregnant',
                    'main_flag',
                    'content',
                ]
            ],
            'title' => 'Редактирование',
            'submit' => 'Сохранить',
        ],
        'add' => [
            'fields' => [
                'Данные' => [
                    'disease_draft_id',
                    'disease_block_type_id',
                    'title',
                    'is_active',
                    'is_adult',
                    'is_male',
                    'is_female',
                    'is_children',
                    'is_newborn',
                    'is_pregnant',
                    'main_flag',
                    'content',
                ]
            ],
            'title' => 'Создание',
            'submit' => 'Создать',
        ]
    ]
];

CmsGeneratorConfigRegister::add('disease_draft_block', $diseaseDraftBlock);
