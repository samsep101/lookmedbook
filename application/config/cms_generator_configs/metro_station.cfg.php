<?php
    $metro_station = array(
        'table'     => DB_PREFIX . 'metro_station', /*имя таблицы*/
        'title'     => 'Список станций метро', /*меняется "ролей"*/
        'fields'    => array(
            'id'              => 'index', /*всегда*/
            'name'            => 'input',
            'metro_branch_id' => array(
                'type'        => 'category',
                'cross_name'  => 'name',
                'cross_index' => 'id',
                'cross_table' => DB_PREFIX . 'metro_branch',
                'first'       => array(
                    '0' => '',
                ),
                'filter'      => 'true',
                'sort_by'     => 'name'
            ),
			'region_id' => array(
				'type'        => 'category',
				'cross_name'  => 'name',
				'cross_index' => 'id',
				'cross_table' => DB_PREFIX . 'region',
				'first'       => array(
					'0' => '',
				),
				'filter'      => 'true',
				'sort_by'     => 'name'
			),
            'longitude'       => 'input',
            'latitude'        => 'input',
            'number'          => 'input'
        ),
        'generator' => array(
            'fields' => array(
                'id'              => 'ID',
                'name'            => 'Название станции метро',
                'metro_branch_id' => 'Ветка метро',
                'region_id' => 'Район',
                'longitude'       => 'Долгота',
                'latitude'        => 'Широта',
                'number'          => 'Номер станции'
            ),
            'list'   => array(
                'fields'  => array('name', 'metro_branch_id', 'region_id', 'longitude', 'latitude', 'number'), /*поля кот. отображаются в списке "суперадминистратор"*/
                'title'   => 'Список станций',
                'sort_by' => array(
                    array(
                        'field' => 'name',
                        'desc'  => 'ASC'
                    ),
                )
            ),
            'edit'   => array(
                'fields' => array(
                    'Станция' => array(
                        'name', 'metro_branch_id', 'region_id', 'longitude', 'latitude', 'number'
                    ),
                ),
                'title'  => 'Редактирование',
                'submit' => 'Сохранить',
            ),
            'add'    => array(
                'fields' => array(
                    'Станция' => array(
                        'name', 'metro_branch_id', 'region_id', 'longitude', 'latitude', 'number'
                    ),
                ),
                'title'  => 'Добавить',
                'submit' => 'Добавить',
            ),
        ),
    );

    CmsGeneratorConfigRegister::add('metro_station', $metro_station);