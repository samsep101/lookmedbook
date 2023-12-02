<?php
    $cms_doctor_academic_degree_to_doctor = array(
        'table'     => DB_PREFIX . 'doctor_academic_degree_to_doctor',
        'title'     => 'Отношения ученых степеней и врачей',
        'fields'    => array(
            'id'                        => 'index',
            'doctor_id'                 => array(
                'type' => 'ajax_input',
                'cross_name' => 'full_name',
                'cross_table' => DB_PREFIX . 'doctor',
            ),
            'doctor_academic_degree_id' => array(
                'type'        => 'category',
                'cross_name'  => 'name',
                'cross_index' => 'id',
                'cross_table' => DB_PREFIX . 'doctor_academic_degree',
                'first'       => array(
                    '0' => '',
                ),
                'sort_by'     => 'name',
            ),
            'dt'                        => 'date'
        ),

        'generator' => array(
            'fields' => array(
                'id'                        => 'ID',
                'doctor_id'                 => 'Доктор',
                'doctor_academic_degree_id' => 'Степень',
                'dt'                        => 'Дата'
            ),
            'list'   => array(
                'fields'  => array(
                    'doctor_id',
                    'doctor_academic_degree_id',
                ),
                'title'   => 'Список ученых степеней',
                'sort_by' => array(
                    array(
                        'field' => 'doctor_academic_degree_id',
                        'desc'  => 'ASC'
                    ),
                )

            ),
            'edit'   => array(
                'fields'  => array(
                    'Ученая степень' => array(
                        'doctor_id',
                        'doctor_academic_degree_id',
                    ),
                ),
                'tooltip' => '<p>Для того, чтобы <b>изменения вступили в силу</b>, необходимо выйти и войти в систему.</p>',
                'title'   => 'Редактирование',
                'submit'  => 'Сохранить',
            ),
            'add'    => array(
                'fields'  => array(
                    'Новая ученая степень' => array(
                        'doctor_id',
                        'doctor_academic_degree_id',
                    ),

                ),
                'tooltip' => '<p>Для того, чтобы <b>изменения вступили в силу</b>, необходимо выйти и войти в систему.</p>',
                'title'   => 'Добавить',
                'submit'  => 'Добавить',
            ),
        ),
    );

    CmsGeneratorConfigRegister::add('doctor_academic_degree_to_doctor', $cms_doctor_academic_degree_to_doctor);