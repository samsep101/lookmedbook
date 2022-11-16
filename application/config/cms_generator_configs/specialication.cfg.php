<?php
    $specialication = array(
        'table'     => DB_PREFIX . 'specialication', /*имя таблицы*/
        'title'     => 'Список специализаций', /*меняется "ролей"*/
        'fields'    => array(
            'id'        => 'index', /*всегда*/
            'name'      => 'input',
            'is_active' => 'checkbox'
        ),
        'generator' => array(
            'fields' => array(
                'id'        => 'ID',
                'name'      => 'Специализация',
                'is_active' => 'Выводить на сайте?'
            ),
            'list'   => array(
                'fields'  => array('name'), /*поля кот. отображаются в списке "суперадминистратор"*/
                'title'   => 'Список специализаций',
                'sort_by' => array(
                    array(
                        'field' => 'name',
                        'desc'  => 'ASC'
                    ),
                )
            ),
            'edit'   => array(
                'fields' => array(
                    'Специализация' => array(
                        'name', 'is_active'
                    ),
                ),
                'title'  => 'Редактирование',
                'submit' => 'Сохранить',
            ),
            'add'    => array(
                'fields' => array(
                    'Специализация' => array(
                        'name', 'is_active'
                    ),
                ),
                'title'  => 'Создать',
                'submit' => 'Создать',
            ),
        ),
    );

    CmsGeneratorConfigRegister::add('specialication', $specialication);