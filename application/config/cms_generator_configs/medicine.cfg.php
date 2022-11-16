<?php
    $medicine = array(
        'table'     => DB_PREFIX . 'medicine',
        'title'     => 'Лекарства',
        'fields'    => array(
            'id'       => 'index',
            'name'     => 'input',
            'price'    => 'input',
            'image_id' => array(
                'type'          => 'image',
                'base_dir'      => 'medicine/',
                'upload_folder' => 'medicine/'
            ),
        ),
        'generator' => array(
            'fields' => array(
                'id'       => 'ID',
                'name'     => 'Название',
                'price'    => 'Цена',
                'image_id' => 'Изображение',
            ),
            'list'   => array(
                'fields'  => array(
                    'name', 'price',
                ),
                'title'   => 'Лекарства',
                'sort_by' => array(
                    array(
                        'field' => 'name',
                        'desc'  => 'ASC'
                    ),
                )
            ),
            'edit'   => array(
                'fields' => array(
                    'Данные' => array(
                        'name', 'price', 'image_id',
                    ),
                ),
                'title'  => 'Редактирование',
                'submit' => 'Сохранить',
            ),
            'add'    => array(
                'fields' => array(
                    'Данные' => array(
                        'name', 'price', 'image_id',
                    ),
                ),
                'title'  => 'Создание',
                'submit' => 'Создать',
            ),
        ),
    );

    CmsGeneratorConfigRegister::add('medicine', $medicine);