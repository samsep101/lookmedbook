<?php
    $specialty_to_clinic = array(
        'table'     => DB_PREFIX . 'specialty_to_clinic', /*имя таблицы*/
        'title'     => 'Список связей специальность-клиника', /*меняется "ролей"*/
        'fields'    => array(
            'id'           => 'index', /*всегда*/
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
                'specialty_id' => 'Специальность',
                'clinic_id'    => 'Клиника',
            ),
            'list'   => array(
                'fields'  => array('specialty_id', 'clinic_id'), /*поля кот. отображаются в списке "суперадминистратор"*/
                'title'   => 'Список связей специальность-клиника',
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
                        'specialty_id', 'clinic_id'
                    ),
                ),
                'title'  => 'Редактирование',
                'submit' => 'Сохранить',
            ),
            'add'    => array(
                'fields' => array(
                    'Данные' => array(
                        'specialty_id', 'clinic_id'
                    ),
                ),
                'title'  => 'Добавить',
                'submit' => 'Добавить',
            ),
        ),
    );

    CmsGeneratorConfigRegister::add('specialty_to_clinic', $specialty_to_clinic);