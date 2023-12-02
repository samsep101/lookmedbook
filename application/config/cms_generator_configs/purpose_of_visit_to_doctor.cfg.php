<?php
$purpose_of_visit_to_doctor = array(
    'table'     => DB_PREFIX . 'purpose_of_visit_to_doctor', /*имя таблицы*/
    'title'     => 'Список связей цель-доктор', /*меняется "ролей"*/
    'fields'    => array(
        'id'                  => 'index', /*всегда*/
        'purpose_of_visit_id' => array(
            'type'        => 'category',
            'cross_name'  => 'name',
            'cross_index' => 'id',
            'cross_table' => DB_PREFIX . 'purpose_of_visit',
            'first'       => array(
                '0' => '',
            ),
            'filter'      => 'true',
            'sort_by'     => 'name',
        ),
        'specialty_id'        => array(
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
        'doctor_id'           => array(
            'type' => 'ajax_input',
            'cross_name' => 'full_name',
            'cross_table' => DB_PREFIX . 'doctor',
        ),
        'clinic_id'           => array(
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
        'visit_price'   => 'input',
    ),
    'generator' => array(
        'fields' => array(
            'id'                  => 'ID',
            'purpose_of_visit_id' => 'Цель',
            'specialty_id'        => 'Специальность',
            'doctor_id'           => 'Доктор',
            'clinic_id'           => 'Клиника',
            'visit_price'   => 'Цена визита',
        ),
        'list'   => array(
            'fields'  => array('purpose_of_visit_id', 'specialty_id', 'doctor_id', 'clinic_id','visit_price'), /*поля кот. отображаются в списке "суперадминистратор"*/
            'title'   => 'Список связей цель-специальность',
            'sort_by' => array(
                array(
                    'field' => 'purpose_of_visit_id',
                    'desc'  => 'ASC'
                ),
            )
        ),
        'edit'   => array(
            'fields' => array(
                'Данные' => array(
                    'clinic_id', 'specialty_id', 'purpose_of_visit_id', 'doctor_id','visit_price'
                ),
            ),
            'title'  => 'Редактирование',
            'submit' => 'Сохранить',
        ),
        'add'    => array(
            'fields' => array(
                'Данные' => array(
                    'clinic_id', 'specialty_id', 'purpose_of_visit_id', 'doctor_id','visit_price'
                ),
            ),
            'title'  => 'Добавить',
            'submit' => 'Добавить',
        ),
    ),
);

CmsGeneratorConfigRegister::add('purpose_of_visit_to_doctor', $purpose_of_visit_to_doctor);