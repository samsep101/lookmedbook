<?php
$specialization_synonym = array(
	'table'     => DB_PREFIX . 'specialization_synonym',
	'title'     => 'Синонимы областей ' . MEDICYNY,
	'fields'    => array(
		'id'           => 'index',
		'name'         => 'input',
		'specialization_id'    => array(
			'type'        => 'category',
			'cross_name'  => 'name',
			'cross_index' => 'id',
			'cross_table' => DB_PREFIX . 'specialization',
			'first'       => array(
				'0' => '',
			),
			'sort_by'     => 'name',
		),

	),
	'generator' => array(
		'fields' => array(
			'id'           => 'ID',
			'name'         => 'Название',
			'specialization_id'         => 'Область ' . MEDICYNY,
		),
		'list'   => array(
			'fields'  => array('specialization_id', 'name'),
			'title'   => 'Синонимы названий специализации',
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
					'specialization_id',
					'name',
				),
			),
			'title'  => 'Редактирование',
			'submit' => 'Сохранить',
		),
		'add'    => array(
			'fields' => array(
				'Данные' => array(
					'specialization_id',
					'name',
				),
			),
			'title'  => 'Создание',
			'submit' => 'Создать',
		),
	),
);


CmsGeneratorConfigRegister::add('specialization_synonym', $specialization_synonym);