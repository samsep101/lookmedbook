<?php

$diseaseDraft = [
    'table' => DB_PREFIX . 'disease_draft',
    'title' => 'Черновики статей заболеваний',
    'fields' => [
        'id' => 'index',
        'disease_id' => array(
            'type'			=> 'category',
            'cross_name'	=> 'title',
            'cross_index'	=> 'id',
            'cross_table'	=> DB_PREFIX.'disease',
            'first'			=> array( '0'	=>	'',),
            'filter' => 'true',
            'sort_by'     => 'title'
        ),
        'name' => 'input',
        'genitive_name' => 'input',
        'prepositional_name' => 'input',
        'alternative_names' => 'input',
        'tags' => 'input',
        'content' => 'htmlarea',
        'extended_content' => 'htmlarea',
        'sources' => 'htmlarea',
        'status' => [
            'type' => 'listvalue',
            'values' => DiseaseDraftModel::getStatuses()
        ],
    ],
    'extra' => [
        'specialties' => [
            'table' => 'specialty_to_disease_draft',
            'title' => 'Подходящие специальности врачей',
            'field' => 'disease_draft_id'
        ],
        'contentBlocks' => [
            'table' => 'disease_draft_block',
            'title' => 'Дополнительный блок описания болезни',
            'field' => 'disease_draft_id'
        ]
    ],
    'generator' => [
        'fields' => [
            'id' => 'ID',
            'disease_id' => 'Опубликованная статья',
            'name' => 'Название',
            'genitive_name' => 'Название в родительном падеже',
            'prepositional_name' => 'Название в предложном падеже',
            'alternative_names' => 'Альтернативные названия, разделенные через запятые',
            'tags' => 'Тэги, разделенные через запятые',
            'content' => 'Контент',
            'extended_content' => 'Расширенный контент',
            'sources' => 'Источники',
            'status' => 'Статус'
        ],
        'list' => [
            'fields' => [
                'id',
                'name',
                'disease_id',
                'status',
            ],
            'title' => 'Черновики статей о заболеваниях',
            'sort_by' => [
                [
                    'field' => 'id',
                    'desc' => 'ASC',
                ]
            ],
            'filters' => [
                'use_class_params' => 'DiseaseSearchCriteria',
                'filters' => [
                    'Название' => [
                        'name' => [
                            'type' => 'input',
                            'title' => ''
                        ],
                    ]
                ]
            ],
            'links' => [
                [
                    'img' => '/media/images/eye.png',
                    'class' => '',
                    'title' => 'Посмотреть статью на сайте',
                ]
            ],
            'link_generator' => function ($link) {
                return isset($link->disease->alias) ? "/disease/{$link->disease->alias}" : "#";
            }
        ],
        'edit' => [
            'fields' => [
                'Данные' => [
                    'disease_id',
                    'status',
                    'name',
                    'genitive_name',
                    'prepositional_name',
                    'alternative_names',
                    'tags',
                    'content',
                    'extended_content',
                    'sources',
                ]
            ],
            'tooltip' => '<p>Для того, чтобы статья была опубликована на сайте, необходимо сменить статус на "Готов".</p>',
            'title' => 'Редактирование',
            'submit' => 'Сохранить',
        ],
        'add' => [
            'fields' => [
                'Данные' => [
                    'name',
                    'genitive_name',
                    'prepositional_name',
                    'alternative_names',
                    'tags',
                    'content',
                    'extended_content',
                    'sources',
                ]
            ],
            'title'   => 'Создать черновик',
            'submit'  => 'Создать черновик',
        ]
    ]
];

CmsGeneratorConfigRegister::add('disease_draft', $diseaseDraft);
