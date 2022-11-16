<?php
    $medicine_to_disease = array(
        'table'     => DB_PREFIX . 'medicine_to_disease',
        'title'     => 'Список связей лекарство-заболевание',
        'fields'    => array(
            'id'          => 'index',
            'medicine_id' => array(
                'type'        => 'category',
                'cross_name'  => 'name',
                'cross_index' => 'id',
                'cross_table' => DB_PREFIX . 'medicine',
                'first'       => array(
                    '0' => '',
                ),
                'filter'      => 'true',
                'sort_by'     => 'name',
            ),
            'disease_id'  => array(
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
                'id'          => 'ID',
                'medicine_id' => 'Лекарство',
                'disease_id'  => 'Заболевание'
            ),
            'list'   => array(
                'fields'  => array(
                    'medicine_id', 'disease_id',
                ),
                'title'   => 'Список связей лекарство-заболевание',
                'sort_by' => array(
                    array(
                        'field' => 'medicine_id',
                        'desc'  => 'ASC'
                    ),
                )
            ),
            'edit'   => array(
                'fields' => array(
                    'Данные' => array(
                        'medicine_id', 'disease_id',
                    ),
                ),
                'title'  => 'Редактирование',
                'submit' => 'Сохранить',
            ),
            'add'    => array(
                'fields' => array(
                    'Данные' => array(
                        'medicine_id', 'disease_id',
                    ),
                ),
                'title'  => 'Создание',
                'submit' => 'Создать',
            ),
        ),
    );

    CmsGeneratorConfigRegister::add('medicine_to_disease', $medicine_to_disease);