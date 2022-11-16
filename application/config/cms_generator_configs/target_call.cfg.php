<?php
    $target_call = array(
        'table'     => DB_PREFIX . 'street',
        'title'     => 'Целевой звонок',
        'fields'    => array(
            'id'           => 'index',
            'phone'        => array(
                'type' => 'input',
                'style' => 'width:400px',
            ),
            'name'         => array(
                'type' => 'input',
                'style' => 'width:400px',
            ),
            'description'  => array(
                'type' => 'text',
                'style' => 'width:400px; height:200px',
            ),
        ),
        'generator' => array(
            'fields' => array(
                'id'          => 'ID',
                'phone'       => 'Номер телефона',
                'name'        => 'Название',
                'description' => 'Описание',
            ),
            'list'   => array(
                'fields'  => array(
                    'phone',
                    'name',
                ),
                'title'   => 'Целевой звонок',
                'sort_by' => array(
                    array(
                        'field' => 'name',
                        'desc'  => 'ASC'
                    ),
                )
            ),
            'edit'   => array(
                'fields'  => array(
                    'Данные' => array(
                        'phone',
                        'name',
                        'description',
                    )
                ),
                'title'   => 'Редактирование',
                'submit'  => 'Сохранить',
            ),
            'add'    => array(
                'fields'  => array(
                    'Данные' => array(
                        'phone',
                        'name',
                        'description',
                    ),
                ),
                'title'   => 'Добавить',
                'submit'  => 'Добавить',
            ),
        ),
    );

    CmsGeneratorConfigRegister::add('target_call', $target_call);