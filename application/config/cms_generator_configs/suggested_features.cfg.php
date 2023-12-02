<?php
    $suggested_features = array(
        'table'     => DB_PREFIX . 'suggested_features',
        'title'     => 'Предложенные сервисы к клиникам',
        'fields'    => array(
            'id'           => 'index',
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
            'feature_name' => 'input',
            'status_id'    => array(
                'type'   => 'listvalue',
                'values' => array(
                    '1' => 'На рассмотрении',
                    '2' => 'Одобрен',
                    '3' => 'Отклонен'
                )
            ),
        ),
        'generator' => array(
            'fields' => array(
                'id'           => 'ID',
                'clinic_id'    => 'Клиника',
                'feature_name' => 'Название',
                'status_id'    => 'Статус',
            ),
            'list'   => array(
                'fields'  => array(
                    'feature_name',
                    'clinic_id',
                    'status_id'
                ),
                'title'   => 'Список предложенных сервисов к клиникам',
                'sort_by' => array(
                    array(
                        'field' => 'feature_name',
                        'desc'  => 'ASC'
                    ),
                )
            ),
            'edit'   => array(
                'fields' => array(
                    'Данные' => array(
                        'feature_name',
                        'clinic_id',
                        'status_id'
                    ),
                ),
                'title'  => 'Редактирование',
                'submit' => 'Сохранить',
            ),
            'add'    => array(
                'fields' => array(
                    'Данные' => array(
                        'feature_name',
                        'clinic_id',
                        'status_id'
                    ),
                ),
                'title'  => 'Добавление',
                'submit' => 'Добавить',
            ),
        )
    );

    CmsGeneratorConfigRegister::add('suggested_features', $suggested_features);