<?php
    $doctor_article_type = array(
        'table'     => DB_PREFIX . 'doctor_article_type',
        'title'     => 'Типы научных работ',
        'fields'    => array(
            'id'          => 'index',
            'name'        => 'input',
            'description' => 'text',
        ),
        'generator' => array(
            'fields' => array(
                'id'          => 'ID',
                'name'        => 'Название',
                'description' => 'Описание',
            ),
            'list'   => array(
                'fields'  => array('name'),
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
                        'name', 'description'
                    ),
                ),
                'title'  => 'Редактирование',
                'submit' => 'Сохранить',
            ),
            'add'    => array(
                'fields' => array(
                    'Данные' => array(
                        'name', 'description'
                    ),
                ),
                'title'  => 'Создать',
                'submit' => 'Создать',
            ),
        ),
    );

    CmsGeneratorConfigRegister::add('doctor_article_type', $doctor_article_type);