<?php
$doctor_to_clinic = array(
    'table'     => DB_PREFIX . 'doctor_to_clinic', /*имя таблицы*/
    'title'     => 'Отношения докторов и клиник', /*меняется "ролей"*/
    'fields'    => array(
        'id'                 => 'index', /*всегда*/
        'doctor_id'          => array(
            'type' => 'ajax_input',
            'cross_name' => 'full_name',
            'cross_table' => DB_PREFIX . 'doctor',
        ),
        'clinic_id'          => array(
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
        'first_visit_price'  => 'input',
        'second_visit_price' => 'input'
    ),
    'generator' => array(
        'fields' => array(
            'id'                 => 'ID',
            'doctor_id'          => 'Доктор',
            'clinic_id'          => 'Клиника',
            'specialty_id'       => 'Специальность',
            'first_visit_price'  => 'Цена первого визита',
            'second_visit_price' => 'Цена повторного визита',
        ),
        'list'   => array(
            'fields'  => array('doctor_id', 'clinic_id',  'first_visit_price', 'second_visit_price'), /*поля кот. отображаются в списке "суперадминистратор"*/
            'title'   => 'Отношения докторов и клиник',
            'sort_by' => array(
                array(
                    'field' => 'doctor_id',
                    'desc'  => 'ASC'
                ),
            )
        ),
        'edit'   => array(
            'fields' => array(
                'Отношение' => array(
                    'doctor_id', 'clinic_id', 'first_visit_price', 'second_visit_price'
                ),
            ),
            'title'  => 'Редактирование',
            'submit' => 'Сохранить',
        ),
        'add'    => array(
            'fields' => array(
                'Связь' => array(
                    'doctor_id', 'clinic_id', 'first_visit_price', 'second_visit_price'
                ),
            ),
            'title'  => 'Добавить',
            'submit' => 'Добавить',
        ),
    ),
);

CmsGeneratorConfigRegister::add('doctor_to_clinic', $doctor_to_clinic);