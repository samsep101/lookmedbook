<?php
    $smis = array(
        'table'     => DB_PREFIX . 'smis', /*имя таблицы*/
        'title'     => 'Список СМИС', /*меняется "ролей"*/
        'fields'    => array(
            'id'          => 'index', /*всегда*/
            'name'        => 'input',
            'description' => 'input',
            'code'        => 'input',
        ),
        'generator' => array(
            'fields' => array(
                'id'          => 'ID',
                'name'        => 'Название',
                'description' => 'Описание',
                'code'        => 'Код'
            ),
            'list'   => array(
                'fields'  => array('name'), /*поля кот. отображаются в списке "суперадминистратор"*/
                'title'   => 'Список СМИС',
                'sort_by' => array(
                    array(
                        'field' => 'name',
                        'desc'  => 'ASC'
                    ),
                )
            ),
            'edit'   => array(
                'fields' => array(
                    'СМИС' => array(
                        'name', 'description', 'code'
                    ),
                ),
                'title'  => 'Редактирование',
                'submit' => 'Сохранить',
            ),
            'add'    => array(
                'fields' => array(
                    'СМИС' => array(
                        'name', 'description', 'code'
                    ),
                ),
                'title'  => 'Создать',
                'submit' => 'Создать',
            ),
        ),
    );

    CmsGeneratorConfigRegister::add('smis', $smis);