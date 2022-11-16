<?php
$district = array(
	'table'     => DB_PREFIX . 'district',
	'title'     => 'Округа',
	'fields'    => array(
		'id'           => 'index',
		'name'         => 'input',
		'full_name'         => 'just_text',
		'alias'        => 'input',
		'city_id' => array(
			'type'			=> 'category',
			'cross_name'	=> 'name',
			'cross_index'	=> 'id',
			'cross_table'	=> DB_PREFIX.'city',
			'first'			=> array( '0'	=>	'',),
			'filter' => 'true',
			'sort_by'     => 'name'
		),
	),
	'generator' => array(
		'fields' => array(
			'id'           => 'ID',
			'name'         => 'Название',
			'full_name'         => 'Название',
			'alias'   => 'Алиас',
			'city_id'   => 'Город',
		),
		'list'   => array(
			'fields'  => array(
				'name',
				'city_id',
				'alias',
			),
			'title'   => 'Округа',
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
					'name',
					'alias',
					'city_id',
				)
			),
			'tooltip' => '<p>Для того, чтобы <b>изменения вступили в силу</b>, необходимо выйти и войти в систему.</p>',
			'title'   => 'Редактирование',
			'submit'  => 'Сохранить',
		),
		'add'    => array(
			'fields'  => array(
				'Данные' => array(
					'name',
					'alias',
					'city_id',
				),
			),
			'tooltip' => '<p>Для того, чтобы <b>изменения вступили в силу</b>, необходимо выйти и войти в систему.</p>',
			'title'   => 'Добавить',
			'submit'  => 'Добавить',
		),
	),
	'extra'     => array(
		'region'                 => array(
			'table' => 'region',
			'title' => 'Районы',
			'field' => 'district_id'
		),
	),
);

CmsGeneratorConfigRegister::add('district', $district);