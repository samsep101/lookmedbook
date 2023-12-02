<?php
    $doctor_type = array(
        'table'     => DB_PREFIX . 'doctor_type', /*имя таблицы*/
        'title'     => 'Список типов докторов', /*меняется "ролей"*/
        'fields'    => array(
            'id'   => 'index', /*всегда*/
            'name' => 'input',
        ),
        'generator' => array(
            'fields' => array(
                'id'   => 'ID',
                'name' => 'Название типа',
            ),
            'list'   => array(
                'fields'  => array('name'), /*поля кот. отображаются в списке "суперадминистратор"*/
                'title'   => 'Список типов докторов',
                'sort_by' => array(
                    array(
                        'field' => 'name',
                        'desc'  => 'ASC'
                    ),
                )
            ),
            'edit'   => array(
                'fields' => array(
                    'Статус' => array(
                        'name'
                    ),
                ),
                'title'  => 'Редактирование',
                'submit' => 'Сохранить',
            ),
            'add'    => array(
                'fields' => array(
                    'Статус' => array(
                        'name'
                    ),
                ),
                'title'  => 'Создать',
                'submit' => 'Создать',
            ),
        ),
    );

    CmsGeneratorConfigRegister::add('doctor_type', $doctor_type);