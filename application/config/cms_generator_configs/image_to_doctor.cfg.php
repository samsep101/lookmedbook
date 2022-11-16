<?php
    $image_to_doctor = array(
        'table'     => DB_PREFIX . 'image_to_doctor', /*имя таблицы*/
        'title'     => 'Список связей изображение-доктор', /*меняется "ролей"*/
        'fields'    => array(
            'id'        => 'index',
            'doctor_id' => array(
                'type' => 'ajax_input',
                'cross_name' => 'full_name',
                'cross_table' => DB_PREFIX . 'doctor',
            ),
            'image_id'  => array(
                'type'          => 'image',
                'base_dir'      => 'doctor/',
                'upload_folder' => 'doctor/'
            ),
        ),
        'generator' => array(
            'fields' => array(
                'id'        => 'ID',
                'doctor_id' => 'Доктор',
                'image_id'  => 'Изображение',
            ),
            'list'   => array(
                'fields'  => array('doctor_id', 'image_id'), /*поля кот. отображаются в списке "суперадминистратор"*/
                'title'   => 'Список связей изображение-доктор',
                'sort_by' => array(
                    array(
                        'field' => 'doctor_id',
                        'desc'  => 'ASC'
                    ),
                )
            ),
            'edit'   => array(
                'fields' => array(
                    'Связь' => array(
                        'doctor_id', 'image_id'
                    ),
                ),
                'title'  => 'Редактирование',
                'submit' => 'Сохранить',
            ),
            'add'    => array(
                'fields' => array(
                    'Связь' => array(
                        'doctor_id', 'image_id'
                    ),
                ),
                'title'  => 'Добавить',
                'submit' => 'Добавить',
            ),
        ),
    );

    CmsGeneratorConfigRegister::add('image_to_doctor', $image_to_doctor);