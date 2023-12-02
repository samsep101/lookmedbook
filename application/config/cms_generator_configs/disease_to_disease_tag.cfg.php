<?php
    $disease_to_disease_tag = array(
        'table'     => DB_PREFIX . 'disease_to_disease_tag',
        'title'     => 'Список связей заболевание-тег',
        'fields'    => array(
            'id'             => 'index', /*всегда*/
            'disease_tag_id' => array(
                'type'        => 'category',
                'cross_name'  => 'tag',
                'cross_index' => 'id',
                'cross_table' => DB_PREFIX . 'disease_tag',
                'first'       => array(
                    '0' => '',
                ),
                'filter'      => 'true',
                'sort_by'     => 'tag',
            ),
            'disease_id'     => array(
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
                'id'             => 'ID',
                'disease_tag_id' => 'Тег',
                'disease_id'     => 'Заболевание',
            ),
            'list'   => array(
                'fields'  => array('disease_id', 'disease_tag_id'),
                'title'   => 'Список связей заболевание-тег',
                'sort_by' => array(
                    array(
                        'field' => 'disease_id',
                        'desc'  => 'ASC'
                    ),
                )
            ),
            'edit'   => array(
                'fields' => array(
                    'Данные' => array(
                        'disease_id', 'disease_tag_id'
                    ),
                ),
                'title'  => 'Редактирование',
                'submit' => 'Сохранить',
            ),
            'add'    => array(
                'fields' => array(
                    'Данные' => array(
                        'disease_id', 'disease_tag_id'
                    ),
                ),
                'title'  => 'Создание',
                'submit' => 'Создать',
            ),
        ),
    );

    CmsGeneratorConfigRegister::add('disease_to_disease_tag', $disease_to_disease_tag);