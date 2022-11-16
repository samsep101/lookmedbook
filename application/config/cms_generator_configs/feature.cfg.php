<?php
    $feature = array(
        'table'     => DB_PREFIX . 'feature',
        'title'     => 'Сервис',
        'fields'    => array(
            'id'   => 'index',
            'name' => 'input',
            'sort' => 'input',
        ),
        'generator' => array(
            //'disabled' => array('add','delete'),
            //'required' => array('name'=>'text'),
            'fields' => array(
                'id'   => 'ID',
                'name' => 'Название',
                'sort' => 'Сортировка',
            ),
            'list'   => array(
                'fields'  => array('name', 'sort'),
                'title'   => 'Список сервисов',
                'sort_by' => array(
                    array(
                        'field' => 'sort',
                        'desc'  => 'ASC'
                    ),
                )
            ),
            'edit'   => array(
                'fields' => array(
                    'Данные' => array(
                        'name', 'sort'
                    ),
                ),
                'title'  => 'Редактирование',
                'submit' => 'Сохранить',
            ),
            'add'    => array(
                'fields' => array(
                    'Данные' => array(
                        'name', 'sort'
                    ),
                ),
                'title'  => 'Добавление',
                'submit' => 'Добавить',
            ),
        )
    );

    CmsGeneratorConfigRegister::add('feature', $feature);