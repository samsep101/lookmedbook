<?php
    $purpose_of_visit_to_specialty = array(
        'table'     => DB_PREFIX . 'purpose_of_visit_to_specialty', /*имя таблицы*/
        'title'     => 'Список связей цель - специализация',
        'fields'    => array(
            'id'                  => 'index', /*всегда*/
            'purpose_of_visit_id' => array(
                'type'        => 'category',
                'cross_name'  => 'name',
                'cross_index' => 'id',
                'cross_table' => DB_PREFIX . 'purpose_of_visit',
                'first'       => array(
                    '0' => '',
                ),
                'filter'      => 'true',
                'sort_by'     => 'name',
            ),
            'specialty_id'        => array(
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
			'is_main'            => array(
				'type'  => 'ajax_checkbox',
				'label' => 'Да/Нет'
			),
        ),
		'extra'     => array(
			'specialties' => array(
				'table' => 'suitable_specialty',
				'title' => 'Подходящие специализации',
				'fields' => array(
					'specialty_id' => 'specialty_id',
					'purpose_of_visit_id' => 'purpose_of_visit_id'
				),
			),
		),
        'generator' => array(
            'fields' => array(
                'id'                  => 'ID',
                'purpose_of_visit_id' => 'Цель',
                'specialty_id'        => 'Специализация',
				'is_main' 				=> 'Основная'
            ),
            'list'   => array(
                'fields'  => array('purpose_of_visit_id', 'specialty_id', 'is_main'), /*поля кот. отображаются в списке "суперадминистратор"*/
                'title'   => 'Список связей цель - специализация',
				'sortable' => true,
                'sort_by' => array(
					array(
						'field' => 'is_main',
						'desc' => 'DESC'
					),
					array(
						'field' => 'sort',
						'desc' => 'ASC'
					),
                    array(
                        'field' => 'purpose_of_visit.name',
                        'desc'  => 'ASC'
                    ),
                ),
            ),
            'edit'   => array(
                'fields' => array(
                    'Связь' => array(
						'specialty_id', 'purpose_of_visit_id', 'is_main'
                    ),
                ),
                'title'  => 'Редактирование',
                'submit' => 'Сохранить',
            ),
            'add'    => array(
                'fields' => array(
                    'Связь' => array(
                        'purpose_of_visit_id', 'specialty_id', 'is_main'
                    ),
                ),
                'title'  => 'Добавить',
                'submit' => 'Добавить',
            ),
        ),
    );

    CmsGeneratorConfigRegister::add('purpose_of_visit_to_specialty', $purpose_of_visit_to_specialty);