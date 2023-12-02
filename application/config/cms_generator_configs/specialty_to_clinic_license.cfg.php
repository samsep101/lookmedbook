<?php
    $specialty_to_clinic_license = array(
        'table'     => DB_PREFIX . 'specialty_to_clinic_license', /*имя таблицы*/
        'title'     => 'Список связей специальность-лицензия', /*меняется "ролей"*/
        'fields'    => array(
            'id'                => 'index', /*всегда*/
            'clinic_license_id' => array(
                'type'        => 'category',
                'cross_name'  => 'name',
                'cross_index' => 'id',
                'cross_table' => DB_PREFIX . 'clinic_license',
                'first'       => array(
                    '0' => '',
                ),
                'filter'      => 'true',
                'sort_by'     => 'name',
            ),
            'specialty_id'      => array(
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
                'id'                => 'ID',
                'clinic_license_id' => 'Лицензия',
                'specialty_id'      => 'Специальность',
            ),
            'list'   => array(
                'fields'  => array('clinic_license_id', 'specialty_id'), /*поля кот. отображаются в списке "суперадминистратор"*/
                'title'   => 'Список связей специальность-лицензия',
                'sort_by' => array(
                    array(
                        'field' => 'clinic_license_id',
                        'desc'  => 'ASC'
                    ),
                )
            ),
            'edit'   => array(
                'fields' => array(
                    'Данные' => array(
                        'clinic_license_id', 'specialty_id'
                    ),
                ),
                'title'  => 'Редактирование',
                'submit' => 'Сохранить',
            ),
            'add'    => array(
                'fields' => array(
                    'Данные' => array(
                        'clinic_license_id', 'specialty_id'
                    ),
                ),
                'title'  => 'Создание',
                'submit' => 'Создать',
            ),
        ),
    );

    CmsGeneratorConfigRegister::add('specialty_to_clinic_license', $specialty_to_clinic_license);