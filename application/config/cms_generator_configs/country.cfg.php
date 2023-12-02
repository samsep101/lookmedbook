<?php
    $country = array(
        'table'     => DB_PREFIX . 'country',
        'title'     => 'Страны',
        'fields'    => array(
            'id'   => 'index',
            'name' => 'input',
        ),
        'extra'     => array(
            'city' => array(
                'table' => 'city',
                'title' => 'Города',
                'field' => 'city_id'
            ),
        ),
        'generator' => array(
            'fields' => array(
                'id'   => 'ID',
                'name' => 'Название страны',
            ),
            'list'   => array(
                'fields'  => array(
                    'name',
                ),
                'title'   => 'Список стран',
                'sort_by' => array(
                    array(
                        'field' => 'name',
                        'desc'  => 'ASC'
                    ),
                )
            ),
            'edit'   => array(
                'fields'  => array(
                    'Данные' => array(
                        'name',
                    ),
                ),
                'tooltip' => '<p>Для того, чтобы <b>изменения вступили в силу</b>, необходимо выйти и войти в систему.</p>',
                'title'   => 'Редактирование',
                'submit'  => 'Сохранить',
            ),
            'add'    => array(
                'fields'  => array(
                    'Данные' => array(
                        'name',
                    ),
                ),
                'tooltip' => '<p>Для того, чтобы <b>изменения вступили в силу</b>, необходимо выйти и войти в систему.</p>',
                'title'   => 'Создать новую',
                'submit'  => 'Создать новую',
            ),
        ),
    );

    CmsGeneratorConfigRegister::add('country', $country);