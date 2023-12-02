<?php
    $specialty_to_doctor = array(
        'table'     => DB_PREFIX . 'specialty_to_doctor', /*имя таблицы*/
        'title'     => 'Список связей специальность-доктор', /*меняется "ролей"*/
        'fields'    => array(
            'id'                     => 'index', /*всегда*/
            'specialty_id'           => array(
                'type'        => 'category',
                'cross_name'  => 'name',
                'cross_index' => 'id',
                'cross_table' => DB_PREFIX . 'specialty',
                'first'       => array(
                    '0' => '',
                ),
                'filter'      => 'true',
                'sort_by'     => 'name',
            ),
            'doctor_id'              => array(
                'type'        => 'category',
                'cross_name'  => 'full_lower_name',
                'cross_index' => 'id',
                'cross_table' => DB_PREFIX . 'doctor',
                'first'       => array(
                    '0' => '',
                ),
                'filter'      => 'true',
                'sort_by'     => 'full_lower_name',
            ),
            'doctor_certificate_id'  => array(
                'type'        => 'category',
                'cross_name'  => 'id',
                'cross_index' => 'id',
                'cross_table' => DB_PREFIX . 'doctor_certificate',
                'first'       => array(
                    '0' => '',
                ),
                'filter'      => 'true',
                'sort_by'     => 'id',
            ),
            'qualifying_category_id' => array(
                'type'        => 'category',
                'cross_name'  => 'name',
                'cross_index' => 'id',
                'cross_table' => DB_PREFIX . 'qualifying_category',
                'first'       => array(
                    '0' => '',
                ),
                'filter'      => 'true',
            ),
        ),
        'generator' => array(
            'fields' => array(
                'id'                     => 'ID',
                'specialty_id'           => 'Специальность',
                'doctor_id'              => 'Доктор',
                'doctor_certificate_id'  => 'Сертификат',
                'qualifying_category_id' => 'Квалификационная категория',
            ),
            'list'   => array(
                'fields'  => array('specialty_id', 'qualifying_category_id', 'doctor_certificate_id'), /*поля кот. отображаются в списке "суперадминистратор"*/
                'title'   => 'Список связей специальность-доктор',
                'sort_by' => array(
                    array(
                        'field' => 'doctor_id',
                        'desc'  => 'ASC'
                    ),
                )
            ),
            'edit'   => array(
                'fields' => array(
                    'Данные' => array(
                        'name', 'specialty_id', 'doctor_id', 'doctor_certificate_id', 'qualifying_category_id'
                    ),
                ),
                'title'  => 'Редактирование',
                'submit' => 'Сохранить',
            ),
            'add'    => array(
                'fields' => array(
                    'Данные' => array(
                        'name', 'specialty_id', 'doctor_id', 'doctor_certificate_id', 'qualifying_category_id'
                    ),
                ),
                'title'  => 'Добавить',
                'submit' => 'Добавить',
            ),
        ),
    );

    CmsGeneratorConfigRegister::add('specialty_to_doctor', $specialty_to_doctor);