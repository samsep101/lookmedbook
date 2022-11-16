<?php
    $metro_branch = array(
        'table'     => DB_PREFIX . 'metro_branch',
        'title'     => 'Список веток метро',
        'fields'    => array(
            'id'       => 'index',
            'name'     => 'input',
            'metro_id' => array(
                'type'        => 'category',
                'cross_name'  => 'name',
                'cross_index' => 'id',
                'cross_table' => DB_PREFIX . 'metro',
                'first'       => array(
                    '0' => '',
                ),
                'filter'      => 'true',
                'sort_by'     => 'name',
            ),
            'image_id' => array(
                'type'          => 'image',
                'base_dir'      => 'metro/',
                'upload_folder' => 'metro/'
            ),
        ),
        'extra'     => array(
            'stations' => array(
                'table' => 'metro_station',
                'title' => 'Станции метро',
                'field' => 'metro_branch_id',
                'sort_by' => array(
                    array(
                        'field' => 'number',
                        'desc'  => 'ASC'
                    ),
                ),
            ),
        ),
        'generator' => array(
            'fields' => array(
                'id'       => 'ID',
                'name'     => 'Название ветки',
                'metro_id' => 'Метро',
	            'image_id' => 'Иконка'
            ),
            'list'   => array(
                'fields'  => array('name', 'metro_id'), /*поля кот. отображаются в списке "суперадминистратор"*/
                'title'   => 'Ветки метро',
                'sort_by' => array(
                    array(
                        'field' => 'name',
                        'desc'  => 'ASC'
                    ),
                )
            ),
            'edit'   => array(
                'fields' => array(
                    'Ветка метро' => array(
                        'name', 'metro_id', 'image_id'
                    ),
                ),
                'title'  => 'Редактирование',
                'submit' => 'Сохранить',
            ),
            'add'    => array(
                'fields' => array(
                    'Ветка метро' => array(
                        'name', 'metro_id', 'image_id'
                    ),
                ),
                'title'  => 'Добавить',
                'submit' => 'Добавить',
            ),
        ),
    );

    CmsGeneratorConfigRegister::add('metro_branch', $metro_branch);