<?php
    $doctor_specialty_to_clinic = array(
        'table'     => DB_PREFIX . 'doctor_specialty_to_clinic', /*имя таблицы*/
        'title'     => 'Отношения специальностей к клинике и доктору', /*меняется "ролей"*/
        'fields'    => array(
            'id'           => 'index', /*всегда*/
            'doctor_id'    => array(
                'type' => 'ajax_input',
                'cross_name' => 'full_name',
                'cross_table' => DB_PREFIX . 'doctor',
            ),
            'clinic_id'    => array(
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
            'specialty_id' => array(
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
        ),
        'generator' => array(
            'fields' => array(
                'id'           => 'ID',
                'doctor_id'    => 'Доктор',
                'clinic_id'    => 'Клиника',
                'specialty_id' => 'Специальность',
            ),
            'list'   => array(
                'fields'  => array(
                    'doctor_id',
                    'clinic_id',
                    'specialty_id'
                ), /*поля кот. отображаются в списке "суперадминистратор"*/
                'title'   => 'Отношения специальностей к клинике и доктору',
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
                        'doctor_id',
                        'clinic_id',
                        'specialty_id'
                    ),
                ),
                'title'  => 'Редактирование',
                'submit' => 'Сохранить',
            ),
            'add'    => array(
                'fields' => array(
                    'Связь' => array(
                        'doctor_id',
                        'clinic_id',
                        'specialty_id'
                    ),
                ),
                'title'  => 'Добавить',
                'submit' => 'Добавить',
            ),
        ),
    );

    CmsGeneratorConfigRegister::add('doctor_specialty_to_clinic', $doctor_specialty_to_clinic);