<?php
    $schedule = array(
        'table'     => DB_PREFIX . 'schedule', /*имя таблицы*/
        'title'     => 'Список пунктов расписаний', /*меняется "ролей"*/
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
            'doctor_id' => array(
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
            'dt_start'  => array('type' => 'date', 'show_time' => TRUE),
            'dt_end'    => array('type' => 'date', 'show_time' => TRUE),
            'is_busy'   => 'checkbox',
            'visit_id'  => array(
                'type'        => 'category',
                'cross_name'  => 'id',
                'cross_index' => 'id',
                'cross_table' => DB_PREFIX . 'visit',
                'first'       => array(
                    '0' => '',
                ),
                'filter'      => 'true',
                'sort_by'     => 'id',
            ),
        ),
        'generator' => array(
            'fields' => array(
                'id'        => 'ID',
                'clinic_id' => 'Клиника',
                'doctor_id' => 'Доктор',
                'dt_start'  => 'Дата начала',
                'dt_end'    => 'Дата окончания',
                'is_busy'   => 'Флаг занятости',
                'visit_id'  => 'Визит',
            ),
            'list'   => array(
                'fields'  => array('clinic_id', 'doctor_id', 'dt_start', 'dt_end'), /*поля кот. отображаются в списке "суперадминистратор"*/
                'title'   => 'Список пунктов расписаний',
                'sort_by' => array(
                    array(
                        'field' => 'clinic_id',
                        'desc'  => 'ASC'
                    ),
                )
            ),
            'edit'   => array(
                'fields' => array(
                    'Пункт расписания' => array(
                        'clinic_id', 'doctor_id', 'dt_start', 'dt_end', 'is_busy', 'visit_id'
                    ),
                ),
                'title'  => 'Редактирование',
                'submit' => 'Сохранить',
            ),
            'add'    => array(
                'fields' => array(
                    'Пункт расписания' => array(
                        'clinic_id', 'doctor_id', 'dt_start', 'dt_end', 'is_busy', 'visit_id'
                    ),
                ),
                'title'  => 'Создать',
                'submit' => 'Создать',
            ),
        ),
    );

    CmsGeneratorConfigRegister::add('schedule', $schedule);