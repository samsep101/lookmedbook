<?php
    $mail_template = array(
        'table'     => DB_PREFIX . 'mail_template',
        'title'     => 'Шаблоны редактируемых писем',
        'fields'    => array(
            'id'      => 'index',
            'name'    => 'just_text',
            'code'    => 'input',
            'title'   => 'input',
            'content' => 'htmlarea',
            'description' => 'just_text',
        ),
        'generator' => array(
            'fields' => array(
                'id'      => 'ID',
                'name'    => 'Название',
                'code'    => 'Код',
                'title'   => 'Заголовок',
                'content' => 'Содержание',
                'description' => 'Описание',
            ),
            'list'   => array(
                'fields'  => array('name'),
                'title'   => 'Список шаблонов',
                'sort_by' => array(
                    array(
                        'field' => 'name',
                        'desc'  => 'ASC'
                    ),
                )
            ),
            'edit'   => array(
                'fields' => array(
                    'Данные' => array(
                        'name', 'title', 'content', 'description'
                    )
                ),
                'title'  => 'Редактирование шаблона',
                'submit' => 'Сохранить',
            ),
            'add'    => array(
                'fields' => array(
                    'Данные' => array(
                        'name', 'title', 'content'
                    ),
                ),
                'title'  => 'Создание',
                'submit' => 'Создать',
            ),
        )
    );

    CmsGeneratorConfigRegister::add('mail_template', $mail_template);