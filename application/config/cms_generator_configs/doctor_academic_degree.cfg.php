<?php
    $cms_doctor_academic_degree = array(
        'table'     => DB_PREFIX . 'doctor_academic_degree',
        'title'     => 'Учёные степени',
        'fields'    => array(
            'id'          => 'index',
            'name'        => 'input',
            'description' => 'text',
        ),

        'generator' => array(
            'fields' => array(
                'id'          => 'ID',
                'name'        => 'Название',
                'description' => 'Описание',
            ),
            'list'   => array(
                'fields'  => array(
                    'name',
                    'description',
                ),
                'title'   => 'Список ученых степеней',
                'sort_by' => array(
                    array(
                        'field' => 'name',
                        'desc'  => 'ASC'
                    ),
                )

            ),
            'edit'   => array(
                'fields'  => array(
                    'Ученая степень' => array(
                        'name',
                        'description',
                    ),
                ),
                'tooltip' => '<p>Для того, чтобы <b>изменения вступили в силу</b>, необходимо выйти и войти в систему.</p>',
                'title'   => 'Редактирование',
                'submit'  => 'Сохранить',
            ),
            'add'    => array(
                'fields'  => array(
                    'Новая ученая степень' => array(
                        'name',
                        'description',
                    ),

                ),
                'tooltip' => '<p>Для того, чтобы <b>изменения вступили в силу</b>, необходимо выйти и войти в систему.</p>',
                'title'   => 'Добавить',
                'submit'  => 'Добавить',
            ),
        ),
    );

    CmsGeneratorConfigRegister::add('doctor_academic_degree', $cms_doctor_academic_degree);