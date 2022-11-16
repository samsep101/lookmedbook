<?php
    $seo = array(
        'table'     => DB_PREFIX . 'seo', /*имя таблицы*/
        'title'     => 'Список SEO-тэгов', /*меняется "ролей"*/
        'fields'    => array(
            'id_num'          => 'index', /*всегда*/
            'id'              => 'input',
            'seo_title'       => 'input',
            'seo_keywords'    => 'input',
            'seo_description' => 'input',
        ),
        'generator' => array(
            'fields' => array(
                'id_num'          => 'ID',
                'id'              => 'Ид.',
                'seo_title'       => 'Title',
                'seo_keywords'    => 'Description',
                'seo_description' => 'Keywords',
            ),
            'list'   => array(
                'fields'  => array('seo_title', 'seo_keywords', 'seo_description'), /*поля кот. отображаются в списке "суперадминистратор"*/
                'title'   => 'Список SEO-тэгов',
                'sort_by' => array(
                    array(
                        'field' => 'seo_title',
                        'desc'  => 'ASC'
                    ),
                )
            ),
            'edit'   => array(
                'fields' => array(
                    'Тэги' => array(
                        'seo_title', 'seo_keywords', 'seo_description'
                    ),
                ),
                'title'  => 'Редактирование',
                'submit' => 'Сохранить',
            ),
            'add'    => array(
                'fields' => array(
                    'Тэги' => array(
                        'seo_title', 'seo_keywords', 'seo_description'
                    ),
                ),
                'title'  => 'Создать',
                'submit' => 'Создать',
            ),
        ),
    );

    CmsGeneratorConfigRegister::add('seo', $seo);