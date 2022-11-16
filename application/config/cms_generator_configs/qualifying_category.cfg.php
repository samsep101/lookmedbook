<?php
    $qualifying_category = array(
        'table'     => DB_PREFIX . 'qualifying_category',
        'title'     => 'Квалификационные категории врачей',
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
                'title'   => 'Квалификационные категории врачей',
                'sort_by' => array(
                    array(
                        'field' => 'id',
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
                'title'  => 'Создание',
                'submit' => 'Создать',
            ),
        ),
    );

    CmsGeneratorConfigRegister::add('qualifying_category', $qualifying_category);