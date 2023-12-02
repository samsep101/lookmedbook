<?php

    CmsGeneratorConfigRegister::add('service_to_clinic', [

        'table'     => 'service_to_clinic', /*имя таблицы*/
        'title'     => 'Связи услуг и клиник', /*меняется "ролей"*/
        'fields'    => array(
            'id' => 'index', /*всегда*/
            'service_id'          => array(
                'type'        => 'category',
                'cross_name'  => 'name',
                'cross_index' => 'id',
                'cross_table' => 'service_category',
                'first'       => array(
                    '0' => '',
                ),
                'filter'      => 'true',
                'sort_by'     => 'name',
            ),
            'clinic_id'          => array(
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
            'price' => 'input',
        ),
        'generator' => array(
            'fields' => array(
                'id' => 'ID',
                'service_id'      => 'Название услуги',
                'clinic_id'      => 'Клиника',
                'price' => 'Цена',
            ),
            'list'   => array(
                'fields'  => ['id', 'service_id', 'clinic_id', 'price'], /*поля кот. отображаются в списке "суперадминистратор"*/
                'title'   => 'Список связей',
                'sort_by' => array(
                    ['field' => 'id', 'desc'  => 'DESC'],
                )
            ),
            'edit'   => array(
                'fields' => array(
                    'Связь' => ['service_id', 'clinic_id', 'price']
                ),
                'title'  => 'Редактирование',
                'submit' => 'Сохранить',
            ),
            'add'    => array(
                'fields' => array(
                    'Связь' => ['service_id', 'clinic_id', 'price']
                ),
                'title'  => 'Создать',
                'submit' => 'Создать',
            ),
        ),

    ]);