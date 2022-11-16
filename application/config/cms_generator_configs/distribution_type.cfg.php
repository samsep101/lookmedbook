<?php

    $distribution_type = array(
        'table'     => DB_PREFIX . 'distribution_type',
        'title'     => 'Типы рассылок',
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
                'fields' => array('name'),
                'title'  => 'Список типов рассылок',
            ),
            'edit'   => array(
                'fields'  => array(
                    'Основные данные' => array(
                        'name'
                    ),
                ),
                'title'   => 'Редактирование типов рассылок',
                'submit'  => 'Сохранить',
                'sort_by' => array(
                    array(
                        'field' => 'name',
                        'desc'  => 'ASC'
                    ),
                )
            ),
            'add'    => array(
                'fields' => array(
                    'Основные данные' => array(
                        'name'
                    ),
                ),
                'title'  => 'Добавление типа рассылки',
                'submit' => 'Добавить',
            ),
        )
    );

    CmsGeneratorConfigRegister::add('distribution_type', $distribution_type);