<?php
    $controller = array(
        'table'     => DB_PREFIX . 'controller', /*имя таблицы*/
        'title'     => 'Список контроллеров', /*меняется "ролей"*/
        'fields'    => array(
            'id'        => 'index', /*всегда*/
            'name'      => 'input',
            'code'      => 'input',
            'is_active' => 'checkbox',
            'sort'      => 'input'
        ),
        'generator' => array(
            'fields' => array(
                'id'        => 'ID',
                'name'      => 'Название',
                'code'      => 'Код',
                'is_active' => 'Выводить в меню?',
                'sort'      => 'Вес сортировки'
            ),
            'list'   => array(
                'fields'  => array('name', 'code', 'is_active', 'sort'), /*поля кот. отображаются в списке "суперадминистратор"*/
                'title'   => 'Список контроллеров',
                'sort_by' => array(
                    array(
                        'field' => 'name',
                        'desc'  => 'ASC'
                    ),
                )
            ),
            'edit'   => array(
                'fields' => array(
                    'Контроллер' => array(
                        'name', 'code', 'is_active', 'sort'
                    ),
                ),
                'title'  => 'Редактирование',
                'submit' => 'Сохранить',
            ),
            'add'    => array(
                'fields' => array(
                    'Контроллер' => array(
                        'name', 'code', 'is_active', 'sort'
                    ),
                ),
                'title'  => 'Создать',
                'submit' => 'Создать',
            ),
        ),
    );

    CmsGeneratorConfigRegister::add('controller', $controller);