<?php
    $image_to_clinic = array(
        'table'     => DB_PREFIX . 'image_to_clinic', /*имя таблицы*/
        'title'     => 'Список связей изображение-клиника', /*меняется "ролей"*/
        'fields'    => array(
            'id'        => 'index', /*всегда*/
            'clinic_id' => array(
                'type'        => 'category',
                'cross_name'  => 'name',
                'cross_index' => 'id',
                'cross_table' => DB_PREFIX . 'clinic',
                'first'       => array(
                    '0' => '',
                ),
                'filter'      => 'true',
                'sort_by'     => 'name',
            ),
            'image_id'  => array(
                'type'          => 'image',
                'base_dir'      => 'clinic/',
                'upload_folder' => 'clinic/'
            ),
        ),
        'generator' => array(
            'fields' => array(
                'id'        => 'ID',
                'clinic_id' => 'Клиника',
                'image_id'  => 'Изображение',
            ),
            'list'   => array(
                'fields'  => array('clinic_id', 'image_id'), /*поля кот. отображаются в списке "суперадминистратор"*/
                'title'   => 'Список связей изображение-клиника',
                'sort_by' => array(
                    array(
                        'field' => 'clinic_id',
                        'desc'  => 'ASC'
                    ),
                )
            ),
            'edit'   => array(
                'fields' => array(
                    'Связь' => array(
                        'clinic_id', 'image_id'
                    ),
                ),
                'title'  => 'Редактирование',
                'submit' => 'Сохранить',
            ),
            'add'    => array(
                'fields' => array(
                    'Связь' => array(
                        'clinic_id', 'image_id'
                    ),
                ),
                'title'  => 'Создать',
                'submit' => 'Создать',
            ),
        ),
    );

    CmsGeneratorConfigRegister::add('image_to_clinic', $image_to_clinic);