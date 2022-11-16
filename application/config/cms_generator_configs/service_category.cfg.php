<?php

CmsGeneratorConfigRegister::add('service_category', [

    'table'     => 'service_category', /*имя таблицы*/
    'title'     => 'Управление услугами', /*меняется "ролей"*/
    'fields'    => [
        'id'                => 'index', /*всегда*/
        'alias'             => 'input',
        'name'              => 'input',
        'genitive_name'     => 'input',
        'service_type'      => [
            'type'   => 'listvalue',
            'values' => [
                'service'    => 'Услуга',
                'diagnostic' => 'Диагностика',
            ],
        ],
        'parent_id'         => [
            'type'        => 'category',
            'cross_name'  => 'name',
            'cross_index' => 'id',
            'cross_table' => 'service_category',
            'first'       => [
                '0' => '',
            ],
            'filter'      => 'false',
            'sort_by'     => 'name',
        ],
        'is_active'         => 'checkbox',
        'description'       => 'htmlarea',
        'full_description'  => 'htmlarea',
        'top_phone'			=> 'input',
        'top_phone_text'	=> 'htmlarea',
        'priority'          => [
            'type'      => 'input',
            'inputType' => 'number',
        ],
        'linked_service_id' => [
            'type'        => 'category',
            'cross_name'  => 'name',
            'cross_index' => 'id',
            'cross_table' => 'service_category',
            'first'       => [
                '0' => '',
            ],
            'filter'      => 'false',
            'sort_by'     => 'name',
        ],
    ],
    'generator' => [
        'fields' => [
            'id'                => 'ID',
            'alias'             => 'ЧПУ',
            'name'              => 'Название',
            'genitive_name'     => 'Название в родительном падеже',
            'service_type'      => 'Тип услуги',
            'parent_id'         => 'Родительская услуга',
            'is_active'         => 'Активна?',
            'description'       => 'Статья-описание об услуге',
            'full_description'  => 'Расширенное описание услуги (отображается под текстом "Читать далее")',
            'priority'          => 'Приоритет',
            'linked_service_id' => 'Ссылка на услугу',
            'top_phone'			=> 'Телефон отображаемый в верхней части страницы (Звони, мы поможем)',
            'top_phone_text'    => 'Текст над номером телефона',
        ],
        'list'   => [
            'fields'  => ['id', 'name', 'alias', 'service_type', 'parent_id', 'is_active', 'linked_service_id'],
            /*поля кот. отображаются в списке "суперадминистратор"*/
            'title'   => 'Список услуг',
            'sort_by' => [
                ['field' => 'parent_id', 'desc' => 'ASC'],
                ['field' => 'alias', 'desc' => 'ASC'],
            ],
            'filters' => [
                'use_class_params' => 'ServiceCategorySearchParams',
                'filters'          => [
                    'Название услуги' => [
                        'name LIKE' => [
                            'type'  => 'input',
                            'title' => '',
                            'bonus_params' => [
                                'w_mask' => 'both',
                            ],
                        ],
                    ],
                ],
            ],
        ],
        'edit'   => [
            'fields' => [
                'Услуга' => [
                    'alias',
                    'name',
                    'genitive_name',
                    'service_type',
                    'parent_id',
                    'is_active',
                    'description',
                    'full_description',
                    'priority',
                    'linked_service_id',
                ],
                'Номер телефона' => [
                    'top_phone',
                    'top_phone_text',
                ],
            ],
            'title'  => 'Редактирование',
            'submit' => 'Сохранить',
        ],
        'add'    => [
            'fields' => [
                'Услуга' => [
                    'alias',
                    'name',
                    'genitive_name',
                    'service_type',
                    'parent_id',
                    'is_active',
                    'description',
                    'full_description',
                    'priority',
                    'linked_service_id',
                ],
                'Номер телефона' => [
                    'top_phone',
                    'top_phone_text',
                ],
            ],
            'title'  => 'Создать',
            'submit' => 'Создать',
        ],
    ],

    'extra' => [
        'relations_to_clinic' => [
            'type'  => 'view',
            'title' => 'Связи с клиниками',
            'view'  => function ($view) {
                return $view->renderInString('admin/edit_sections/related_clinic', false);
            },
        ],
    ],

    'buttons' => [
        'Удалить все связи' => 'services_categories_clear',
    ],

]);
