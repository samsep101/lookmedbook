<?php
    $goal_to_specialization = array(
        'table'     => DB_PREFIX . 'goal_to_specialization', /*имя таблицы*/
        'title'     => 'Список связей цель визита-специализация', /*меняется "ролей"*/
        'fields'    => array(
            'id'                => 'index', /*всегда*/
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
            'visit_goal_id'     => array(
                'type'        => 'category',
                'cross_name'  => 'name',
                'cross_index' => 'id',
                'cross_table' => DB_PREFIX . 'visit_goal',
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
                'specialization_id' => 'Специализация',
                'visit_goal_id'     => 'Цель визита',
            ),
            'list'   => array(
                'fields'  => array('specialization_id', 'visit_goal_id'), /*поля кот. отображаются в списке "суперадминистратор"*/
                'title'   => 'Список связей цель визита-специализация',
                'sort_by' => array(
                    array(
                        'field' => 'specialization_id',
                        'desc'  => 'ASC'
                    ),
                )
            ),
            'edit'   => array(
                'fields' => array(
                    'Связь' => array(
                        'specialization_id', 'visit_goal_id'
                    ),
                ),
                'title'  => 'Редактирование',
                'submit' => 'Сохранить',
            ),
            'add'    => array(
                'fields' => array(
                    'Связь' => array(
                        'specialization_id', 'visit_goal_id'
                    ),
                ),
                'title'  => 'Создать',
                'submit' => 'Создать',
            ),
        ),
    );

    CmsGeneratorConfigRegister::add('goal_to_specialization', $goal_to_specialization);