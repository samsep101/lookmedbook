<?php
    $doctor_character = array(
        'table'     => DB_PREFIX . 'doctor_character', /*имя таблицы*/
        'title'     => 'Список характеристик оценки доктора', /*меняется "ролей"*/
        'fields'    => array(
            'id'        => 'index', /*всегда*/
            'name'      => 'input',
            'is_active' => 'checkbox',
        ),
        'generator' => array(
            'fields' => array(
                'id'        => 'ID',
                'name'      => 'Характеристика',
                'is_active' => 'Выводить на сайте?',
            ),
            'list'   => array(
                'fields'  => array('name', 'is_active'), /*поля кот. отображаются в списке "суперадминистратор"*/
                'title'   => 'Список характеристик оценки доктора',
                'sort_by' => array(
                    array(
                        'field' => 'name',
                        'desc'  => 'ASC'
                    ),
                )
            ),
            'edit'   => array(
                'fields' => array(
                    'Характеристика' => array(
                        'name', 'is_active'
                    ),
                ),
                'title'  => 'Редактирование',
                'submit' => 'Сохранить',
            ),
            'add'    => array(
                'fields' => array(
                    'Характеристика' => array(
                        'name', 'is_active'
                    ),
                ),
                'title'  => 'Создать',
                'submit' => 'Создать',
            ),
        ),
    );

    CmsGeneratorConfigRegister::add('doctor_character', $doctor_character);