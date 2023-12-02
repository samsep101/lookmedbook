<?php
    $purpose_of_visit = array(
        'table'     => DB_PREFIX . 'purpose_of_visit',
        'title'     => 'Цели визита',
        'fields'    => array(
            'id'   => 'index',
            'name' => 'input',
			'purpose_of_visit_type_id' => array(
				'type'        => 'category',
				'cross_name'  => 'name',
				'cross_index' => 'id',
				'cross_table' => DB_PREFIX . 'purpose_of_visit_type',
				'first'       => array(
					'0' => '',
				),
				'filter'      => 'true',
			),
        ),
        'generator' => array(
            'fields' => array(
                'id'   => 'ID',
                'name' => 'Название',
                'purpose_of_visit_type_id' => 'Тип',
            ),
            'list'   => array(
                'fields'  => array(
					'name',
					'purpose_of_visit_type_id',
				),
                'title'   => 'Цели визита',
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
                        'name',
						'purpose_of_visit_type_id'
                    ),
                ),
                'title'  => 'Редактирование',
                'submit' => 'Сохранить',
            ),
            'add'    => array(
                'fields' => array(
                    'Данные' => array(
                        'name'
                    ),
                ),
                'title'  => 'Добавление',
                'submit' => 'Добавить',
            ),
        )
    );

    CmsGeneratorConfigRegister::add('purpose_of_visit', $purpose_of_visit);