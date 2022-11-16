<?php
$clinic_problem = array(
    'table'     => DB_PREFIX . 'clinic_problem', /*имя таблицы*/
    'title'     => 'Клиники, не выгруженные в яндекс', /*меняется "ролей"*/
    'fields'    => array(
        'id'               => 'index', /*всегда*/
        'text' => array(
            'type' => 'input',
            'style' => 'width: 400px;'
        ),
        'dt' => 'just_text',
        'clinic_id' => array(
            'type' => 'category',
            'cross_name' => 'name',
            'cross_index' => 'id',
            'cross_table' => DB_PREFIX . 'clinic',
            'first' => array(
                '0' => '',
            ),
            'sort_by' => 'id',
        ),
    ),
    'generator' => array(
        'fields' => array(
            'id'               => 'ID',
            'text' => 'Проблема клиники',
            'dt' => 'Проблема выявлена',
            'clinic_id' => 'Клиника',
        ),
        'list'   => array(
            'fields'  => array('clinic_id', 'text', 'dt'), /*поля кот. отображаются в списке "суперадминистратор"*/
            'title'   => 'Клиники, не выгруженные в яндекс',
            'sort_by' => array(
                array(
                    'field' => 'dt',
                    'desc' => 'ASC'
                ),
            ),
        ),
        'edit'   => array(
            'fields' => array(
                'Данные' => array(
                    'clinic_id',
                    'text',
                    'dt',
                ),
            ),
            'title'  => 'Редактирование',
            'submit' => 'Сохранить',
        ),
        'add'    => array(
            'fields' => array(
                'Данные' => array(
                    'clinic_id',
                    'text',
                    'dt',
                ),
            ),
            'title'  => 'Добавить',
            'submit' => 'Добавить',
        ),
    ),
);

CmsGeneratorConfigRegister::add('clinic_problem', $clinic_problem);