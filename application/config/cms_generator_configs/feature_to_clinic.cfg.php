<?php
    $feature_to_clinic = array(
        'table'     => DB_PREFIX . 'feature_to_clinic', /*имя таблицы*/
        'title'     => 'Список связей сервис-клиника', /*меняется "ролей"*/
        'fields'    => array(
            'id'         => 'index', /*всегда*/
            'clinic_id'  => array(
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
            'feature_id' => array(
                'type'        => 'category',
                'cross_name'  => 'name',
                'cross_index' => 'id',
                'cross_table' => DB_PREFIX . 'feature',
                'first'       => array(
                    '0' => '',
                ),
                'filter'      => 'true',
                'sort_by'     => 'name',
            ),
        ),
        'generator' => array(
            'fields' => array(
                'id'         => 'ID',
                'clinic_id'  => 'Клиника',
                'feature_id' => 'Сервис',
            ),
            'list'   => array(
                'fields'  => array('clinic_id', 'feature_id'), /*поля кот. отображаются в списке "суперадминистратор"*/
                'title'   => 'Список связей сервис-клиника',
                'sort_by' => array(
                    array(
                        'field' => 'clinic_id',
                        'desc'  => 'ASC'
                    ),
                )
            ),
            'edit'   => array(
                'fields' => array(
                    'Связь' => array(
                        'clinic_id', 'feature_id'
                    ),
                ),
                'title'  => 'Редактирование',
                'submit' => 'Сохранить',
            ),
            'add'    => array(
                'fields' => array(
                    'Связь' => array(
                        'clinic_id', 'feature_id'
                    ),
                ),
                'title'  => 'Создание',
                'submit' => 'Создать',
            ),
        ),
    );

    CmsGeneratorConfigRegister::add('feature_to_clinic', $feature_to_clinic);