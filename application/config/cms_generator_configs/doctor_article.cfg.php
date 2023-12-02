<?php
    $doctor_article = array(
        'table'     => DB_PREFIX . 'doctor_article',
        'title'     => 'Научные работы',
        'fields'    => array(
            'id'                     => 'index',
            'name'                   => 'input',
            'year'                   => 'input',
            'doctor_id'              => array(
                'type'        => 'category',
                'cross_name'  => 'full_lower_name',
                'cross_index' => 'id',
                'cross_table' => DB_PREFIX . 'doctor',
                'first'       => array(
                    '0' => '',
                ),
                'sort_by'     => 'full_lower_name',
            ),
            'doctor_article_type_id' => array(
                'type'        => 'category',
                'cross_name'  => 'name',
                'cross_index' => 'id',
                'cross_table' => DB_PREFIX . 'doctor_article_type',
                'first'       => array(
                    '0' => '',
                ),
                'sort_by'     => 'name',
            ),
        ),
        'generator' => array(
            'fields' => array(
                'id'                     => 'ID',
                'name'                   => 'Название',
                'year'                   => 'Год',
                'doctor_id'              => 'Доктор',
                'doctor_article_type_id' => 'Тип научной работы',
            ),
            'list'   => array(
                'fields'  => array('name', 'year', 'doctor_id', 'doctor_article_type_id'),
                'title'   => 'Список научных работ',
                'sort_by' => array(
                    array(
                        'field' => 'name',
                        'desc'  => 'ASC'
                    ),
                )
            ),
            'edit'   => array(
                'fields' => array(
                    'Данные' => array(
                        'name', 'year', 'doctor_id', 'doctor_article_type_id'
                    ),
                ),
                'title'  => 'Редактирование',
                'submit' => 'Сохранить',
            ),
            'add'    => array(
                'fields' => array(
                    'Данные' => array(
                        'name', 'year', 'doctor_id', 'doctor_article_type_id'
                    ),
                ),
                'title'  => 'Создать',
                'submit' => 'Создать',
            ),
        ),
    );

    CmsGeneratorConfigRegister::add('doctor_article', $doctor_article);