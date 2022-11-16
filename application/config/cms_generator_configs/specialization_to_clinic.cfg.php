<?php
$specialization_to_clinic = array(
    'table'     => DB_PREFIX . 'specialization_to_clinic', /*имя таблицы*/
    'title'     => 'Список связей специализация-клиника', /*меняется "ролей"*/
    'fields'    => array(
        'id'           => 'index', /*всегда*/
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
        'clinic_id'    => array(
            'type'        => 'category',
            'cross_name'  => 'name',
            'cross_index' => 'id',
            'cross_table' => DB_PREFIX . 'clinic',
            'first'       => array(
                '0' => '',
            ),
            'filter'      => 'true',
        ),
    ),
    'generator' => array(
        'fields' => array(
            'id'           => 'ID',
            'specialization_id' => 'Специализация',
            'clinic_id'    => 'Клиника',
        ),
        'list'   => array(
            'fields'  => array('specialization_id', 'clinic_id'), /*поля кот. отображаются в списке "суперадминистратор"*/
            'title'   => 'Список связей специализация-клиника',
            'sort_by' => array(
                array(
                    'field' => 'clinic_id',
                    'desc'  => 'ASC'
                ),
            )
        ),
        'edit'   => array(
            'fields' => array(
                'Данные' => array(
                    'specialization_id', 'clinic_id'
                ),
            ),
            'title'  => 'Редактирование',
            'submit' => 'Сохранить',
        ),
        'add'    => array(
            'fields' => array(
                'Данные' => array(
                    'specialization_id', 'clinic_id'
                ),
            ),
            'title'  => 'Добавить',
            'submit' => 'Добавить',
        ),
    ),
);

CmsGeneratorConfigRegister::add('specialization_to_clinic', $specialization_to_clinic);