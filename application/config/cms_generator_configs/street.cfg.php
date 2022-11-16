<?php
$street = array(
	'table'     => DB_PREFIX . 'street',
	'title'     => 'Улицы',
	'fields'    => array(
		'id'           => 'index',
		'name'         => 'input',
		'full_name'         => 'just_text',
		'prefix'         => 'input',
		'alias'        => 'input',
	),
	'generator' => array(
		'fields' => array(
			'id'           => 'ID',
			'prefix'           => 'тип',
			'name'         => 'Название',
			'full_name'         => 'Название',
			'alias'   => 'Алиас',
		),
		'list'   => array(
			'fields'  => array(
				'full_name',
				'alias',
			),
			'title'   => 'Улицы',
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
					'prefix',
					'name',
					'alias',
				)
			),
			'tooltip' => '<p>Для того, чтобы <b>изменения вступили в силу</b>, необходимо выйти и войти в систему.</p>',
			'title'   => 'Редактирование',
			'submit'  => 'Сохранить',
		),
		'add'    => array(
			'fields'  => array(
				'Данные' => array(
					'prefix',
					'name',
					'alias',
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
			'title' => 'Районы',
			'field' => 'street_id'
		),
	),
);

CmsGeneratorConfigRegister::add('street', $street);