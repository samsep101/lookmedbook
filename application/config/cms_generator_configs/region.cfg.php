<?php
$region = array(
	'table'     => DB_PREFIX . 'region',
	'title'     => 'Районы города',
	'fields'    => array(
		'id'           => 'index',
		'name'         => 'input',
		'full_name'         => 'just_text',
		'alias'        => 'input',
		'district_id' => array(
			'type'			=> 'category',
			'cross_name'	=> 'name',
			'cross_index'	=> 'id',
			'cross_table'	=> DB_PREFIX.'district',
			'first'			=> array( '0'	=>	'',),
			'filter' => 'true',
			'sort_by'     => 'name'
		),
	),
	'generator' => array(
		'fields' => array(
			'id'           => 'ID',
			'prefix'           => 'тип',
			'name'         => 'Название',
			'full_name'         => 'Название',
			'alias'   => 'Алиас',
			'district_id'   => 'Округ',
		),
		'list'   => array(
			'fields'  => array(
				'name',
				'alias',
			),
			'title'   => 'Районы',
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
					'district_id',
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
					'district_id',
				),
			),
			'tooltip' => '<p>Для того, чтобы <b>изменения вступили в силу</b>, необходимо выйти и войти в систему.</p>',
			'title'   => 'Добавить',
			'submit'  => 'Добавить',
		),
	),
	'extra'     => array(
		'street_to_region'                 => array(
			'table' => 'street_to_region',
			'title' => 'Улицы',
			'field' => 'region_id'
		),
	),
);

CmsGeneratorConfigRegister::add('region', $region);