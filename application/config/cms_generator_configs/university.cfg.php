<?php
    $university = array(
        'table'     => DB_PREFIX . 'university',
        'title'     => 'Высшие учебные заведения',
        'fields'    => array(
            'id'   => 'index',
            'name' => 'input',
        ),
        'generator' => array(
            'fields' => array(
                'id'   => 'ID',
                'name' => 'Название',
            ),
            'list'   => array(
                'fields'  => array('name'),
                'title'   => 'Высшие учебные заведения',
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
                        'name'
                    ),
                ),
                'title'  => 'Редактирование',
                'submit' => 'Сохранить',
            ),
            'add'    => array(
                'fields' => array(
                    'Данные' => array(
                        'name'
                    ),
                ),
                'title'  => 'Создание',
                'submit' => 'Создать',
            ),
        ),
    );

    CmsGeneratorConfigRegister::add('university', $university);