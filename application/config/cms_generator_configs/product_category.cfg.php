<?php
    $cms_product_category = array(
        'table'     => DB_PREFIX . 'product_category',
        'title'     => 'Категории товаров',
        'fields'    => array(
            'id'          => 'index',
            'name'        => 'input',
            'parent_id'   => array(
                'type'       => 'parent_record_one_table',
                'name'       => 'name',
                'table_name' => DB_PREFIX . 'product_category',
                'if_null'    => 'Нет родителя',
                'manager'    => 'product_category',
                'first'      => 'Не выбрано',
            ),

            'is_active'   => array(
                'type'  => 'checkbox',
                'label' => 'Да/Нет'
            ),
            'image_id'    => array(
                'type'          => 'image',
                'base_dir'      => 'product_category/',
                'upload_folder' => 'product_category/'
            ),
            'description' => array(
                'type'  => 'htmlarea',
                'style' => 'width: 400px;'
            ),
        ),
        'generator' => array(
            'fields' => array(
                'id'          => 'ID',
                'name'        => 'Название',
                'parent_id'   => 'Родительская категория',
                'is_active'   => 'Активная',
                'image_id'    => 'Изображение',
                'description' => 'Описание',
            ),
            'list'   => array(
                'fields'  => array('name', 'parent_id', 'is_active'),
                'title'   => 'Список категорий товаров',
                'sort_by' => array(
                    array(
                        'field' => 'name',
                        'desc'  => 'ASC'
                    ),
                ),
                'filters' => array(
                    'use_class_params' => 'ProductCategorySearchCriteria',
                    'filters'          => array(
                        'Только активные' => array(
                            'is_active' => array(
                                'type'  => 'checkbox',
                                'title' => ''
                            ),
                        ),
                        'Название'        => array(
                            'name' => array(
                                'type'  => 'input',
                                'title' => ''
                            ),
                        ),
                    )
                )
            ),
            'edit'   => array(
                'fields' => array(
                    'Данные' => array(
                        'name',
                        'parent_id',
                        'is_active',
                        'image_id',
                        'description'
                    ),

                ),
                'title'  => 'Редактирование категории товаров',
                'submit' => 'Сохранить',
            ),
            'add'    => array(
                'fields' => array(
                    'Данные' => array(
                        'name', 'parent_id', 'is_active', 'image_id'
                    ),
                ),
                'title'  => 'Добавление категории товаров',
                'submit' => 'Добавить',
            ),
        )
    );

    CmsGeneratorConfigRegister::add('product_category', $cms_product_category);