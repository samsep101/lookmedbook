<?php
    $role = array(
        'table'     => DB_PREFIX . 'role', /*имя таблицы*/
        'title'     => 'Список ролей', /*меняется "ролей"*/
        'fields'    => array(
            'id'   => 'index', /*всегда*/
            'name' => 'input',
        ),
        'generator' => array(
            'fields' => array(
                'id'   => 'ID',
                'name' => 'Роль',
            ),
            'list'   => array(
                'fields'  => array('name'), /*поля кот. отображаются в списке "суперадминистратор"*/
                'title'   => 'Список ролей',
                'sort_by' => array(
                    array(
                        'field' => 'name',
                        'desc'  => 'ASC'
                    ),
                )
            ),
            'edit'   => array(
                'fields'  => array(
                    'Должность' => array(
                        'name'
                    ),
                ),
                'tooltip' => '<p>Для того, чтобы <b>изменения вступили в силу</b>, необходимо выйти и войти в систему.</p>',
                'title'   => 'Редактирование',
                'submit'  => 'Сохранить',
            ),
            'add'    => array(
                'fields'  => array(
                    'Должность' => array(
                        'name'
                    ),
                ),
                'tooltip' => '<p>Для того, чтобы <b>изменения вступили в силу</b>, необходимо выйти и войти в систему.</p>',
                'title'   => 'Создать новую',
                'submit'  => 'Создать новую',
            ),
        ),
    );

    CmsGeneratorConfigRegister::add('role', $role);