<?php
    $metro = array(
        'table'     => DB_PREFIX . 'metro', /*имя таблицы*/
        'title'     => 'Список веток метро', /*меняется "ролей"*/
        'fields'    => array(
            'id'      => 'index', /*всегда*/
            'city_id' => array(
                'type'        => 'category',
                'cross_name'  => 'name',
                'cross_index' => 'id',
                'cross_table' => DB_PREFIX . 'city',
                'first'       => array(
                    '0' => '',
                ),
                'filter'      => 'true',
                'sort_by'     => 'name'
            ),
            'name'    => 'input',

        ),
        'generator' => array(
            'fields' => array(
                'id'      => 'ID',
                'city_id' => 'Город',
                'name'    => 'Название метро',
            ),
            'list'   => array(
                'fields'  => array('city_id', 'name'), /*поля кот. отображаются в списке "суперадминистратор"*/
                'title'   => 'Список станций пересадки',
                'sort_by' => array(
                    array(
                        'field' => 'city_id',
                        'desc'  => 'ASC'
                    ),
                )
            ),
            'edit'   => array(
                'fields' => array(
                    'Метро' => array(
                        'city_id', 'name'
                    ),
                ),
                'title'  => 'Редактирование',
                'submit' => 'Сохранить',
            ),
            'add'    => array(
                'fields' => array(
                    'Метро' => array(
                        'city_id', 'name'
                    ),
                ),
                'title'  => 'Создать',
                'submit' => 'Создать',
            ),
        ),
    );

    CmsGeneratorConfigRegister::add('metro', $metro);