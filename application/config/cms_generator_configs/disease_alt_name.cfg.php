<?php
    $disease_alt_name = array(
        'table'     => DB_PREFIX . 'disease_alt_name',
        'title'     => 'Альтернативные названия заболеваний',
        'fields'    => array(
            'id'         => 'index',
            'alt_name'   => 'input',
            'disease_id' => array(
                'type'        => 'category',
                'cross_name'  => 'title',
                'cross_index' => 'id',
                'cross_table' => DB_PREFIX . 'disease',
                'first'       => array(
                    '0' => '',
                ),
                'filter'      => 'true',
                'sort_by'     => 'title',
            ),
        ),
        'generator' => array(
            'fields' => array(
                'id'         => 'ID',
                'alt_name'   => 'Альтернативное название',
                'disease_id' => 'Заболевание',
            ),
            'list'   => array(
                'fields'  => array('alt_name', 'disease_id'),
                'title'   => 'Альтернативные названия заболеваний',
                'sort_by' => array(
                    array(
                        'field' => 'alt_name',
                        'desc'  => 'ASC'
                    ),
                )
            ),
            'edit'   => array(
                'fields' => array(
                    'Данные' => array(
                        'alt_name', 'disease_id'
                    ),
                ),
                'title'  => 'Редактирование',
                'submit' => 'Сохранить',
            ),
            'add'    => array(
                'fields' => array(
                    'Данные' => array(
                        'alt_name', 'disease_id'
                    ),
                ),
                'title'  => 'Создание',
                'submit' => 'Создать',
            ),
        ),
    );

    CmsGeneratorConfigRegister::add('disease_alt_name', $disease_alt_name);