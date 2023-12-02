<?php
    $specialization_to_doctor = array(
        'table'     => DB_PREFIX . 'specialization_to_doctor', /*имя таблицы*/
        'title'     => 'Список связей специализация-доктор', /*меняется "ролей"*/
        'fields'    => array(
            'id'                => 'index', /*всегда*/
            'doctor_id'         => array(
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
            'specialization_id' => array(
                'type'        => 'category',
                'cross_name'  => 'name',
                'cross_index' => 'id',
                'cross_table' => DB_PREFIX . 'specialization',
                'first'       => array(
                    '0' => '',
                ),
                'filter'      => 'true',
                'sort_by'     => 'name',
            ),
        ),
        'generator' => array(
            'fields' => array(
                'id'                => 'ID',
                'doctor_id'         => 'Идентификатор доктора',
                'specialization_id' => 'Идентификатор специализации',
            ),
            'list'   => array(
                'fields'  => array('doctor_id', 'specialization_id'), /*поля кот. отображаются в списке "суперадминистратор"*/
                'title'   => 'Список связей специализация-доктор',
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
                        'doctor_id', 'specialization_id'
                    ),
                ),
                'title'  => 'Редактирование',
                'submit' => 'Сохранить',
            ),
            'add'    => array(
                'fields' => array(
                    'Связь' => array(
                        'doctor_id', 'specialization_id'
                    ),
                ),
                'title'  => 'Создать',
                'submit' => 'Создать',
            ),
        ),
    );

    CmsGeneratorConfigRegister::add('specialization_to_doctor', $specialization_to_doctor);