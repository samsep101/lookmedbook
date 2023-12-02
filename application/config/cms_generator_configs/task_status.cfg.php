<?php
    $task_status = array(
        'table'     => DB_PREFIX . 'task_status', /*имя таблицы*/
        'title'     => 'Список типов заданий', /*меняется "ролей"*/
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
                'title'   => 'Список типов заданий',
                'sort_by' => array(
                    array(
                        'field' => 'name',
                        'desc'  => 'ASC'
                    ),
                )
            ),
            'edit'   => array(
                'fields' => array(
                    'Тип' => array(
                        'name'
                    ),
                ),
                'title'  => 'Редактирование',
                'submit' => 'Сохранить',
            ),
            'add'    => array(
                'fields' => array(
                    'Тип' => array(
                        'name'
                    ),
                ),
                'title'  => 'Создать',
                'submit' => 'Создать',
            ),
        ),
    );

    CmsGeneratorConfigRegister::add('task_status', $task_status);