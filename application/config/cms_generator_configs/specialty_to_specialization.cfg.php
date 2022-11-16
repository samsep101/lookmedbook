<?php
$specialty_to_specialization = array(
    'table'     => DB_PREFIX . 'specialty_to_specialization', /*имя таблицы*/
    'title'     => 'Список связей специальность-специализация', /*меняется "ролей"*/
    'fields'    => array(
        'id'           => 'index', /*всегда*/
        'specialty_id' => array(
            'type'        => 'category',
            'cross_name'  => 'name',
            'cross_index' => 'id',
            'cross_table' => DB_PREFIX . 'specialty',
            'first'       => array(
                '0' => '',
            ),
            'filter'      => 'true',
            'sort_by'     => 'name',
        ),
        'specialization_id'    => array(
            'type'        => 'category',
            'cross_name'  => 'name',
            'cross_index' => 'id',
            'cross_table' => DB_PREFIX . 'specialization',
            'first'       => array(
                '0' => '',
            ),
            'filter'      => 'true',
            'sort_by' => 'name',
        ),
		'is_main'            => array(
			'type'  => 'checkbox',
			'label' => 'Да/Нет'
		),
    ),
    'generator' => array(
        'fields' => array(
            'id'           => 'ID',
            'specialty_id' => 'Специальность',
            'specialization_id'    => 'Область ' . MEDICYNY,
			'is_main' => 'Основная'
        ),
        'list'   => array(
            'fields'  => array('specialty_id', 'specialization_id', 'is_main'), /*поля кот. отображаются в списке "суперадминистратор"*/
            'title'   => 'Список связей область ' . MEDICYNY . ' - специализация',
            'sort_by' => array(
                array(
                    'field' => 'specialization_id',
                    'desc'  => 'ASC'
                ),
            ),
			'sortable' => true
        ),
        'edit'   => array(
            'fields' => array(
                'Данные' => array(
					'specialization_id', 'specialty_id'
                ),
            ),
            'title'  => 'Редактирование',
            'submit' => 'Сохранить',
        ),
        'add'    => array(
            'fields' => array(
                'Данные' => array(
					'specialization_id', 'specialty_id'
                ),
            ),
            'title'  => 'Добавить',
            'submit' => 'Добавить',
        ),
    ),
);

CmsGeneratorConfigRegister::add('specialty_to_specialization', $specialty_to_specialization);