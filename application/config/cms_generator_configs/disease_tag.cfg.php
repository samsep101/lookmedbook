<?php
    $disease_tag = array(
        'table'     => DB_PREFIX . 'disease_tag',
        'title'     => 'Теги заболеваний',
        'fields'    => array(
            'id'  => 'index',
            'tag' => 'input',
        ),
        'generator' => array(
            'fields' => array(
                'id'  => 'ID',
                'tag' => 'Тег',
            ),
            'list'   => array(
                'fields'  => array('tag'),
                'title'   => 'Теги заболеваний',
                'sort_by' => array(
                    array(
                        'field' => 'tag',
                        'desc'  => 'ASC'
                    ),
                )
            ),
            'edit'   => array(
                'fields' => array(
                    'Данные' => array(
                        'tag'
                    ),
                ),
                'title'  => 'Редактирование',
                'submit' => 'Сохранить',
            ),
            'add'    => array(
                'fields' => array(
                    'Данные' => array(
                        'tag'
                    ),
                ),
                'title'  => 'Создание',
                'submit' => 'Создать',
            ),
        ),
    );

    CmsGeneratorConfigRegister::add('disease_tag', $disease_tag);