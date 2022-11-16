<?php
    $secondary_education_specialty = array(
        'table' => DB_PREFIX . 'secondary_education_specialty',
        'title' => 'Специальности СУЗов',
        'fields' => array(
            'id' => 'index',
            'name' => 'input',
        ),
        'generator' => array(
            'fields' => array(
                'id' => 'ID',
                'name' => 'Название',
            ),
            'list' => array(
                'fields' => array('name'),
                'title' => 'Специальности СУЗов',
                'sort_by' => array(
                    array(
                        'field' => 'name',
                        'desc'  => 'ASC'
                    ),
                ),
            ),
            'edit' => array(
                'fields' => array(
                    'Данные' => array(
                        'name',
                    ),
                ),
                'title'  => 'Редактирование',
                'submit' => 'Сохранить',
            ),
            'add' => array(
                'fields' => array(
                    'Данные' => array(
                        'name',
                    ),
                ),
                'title'  => 'Создание',
                'submit' => 'Создать',
            ),
        ),
    );

CmsGeneratorConfigRegister::add('secondary_education_specialty', $secondary_education_specialty);