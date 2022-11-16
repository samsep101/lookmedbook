<?php

$street_to_region = array(
	'table'         =>  DB_PREFIX.'street_to_region',
	'title'         =>  'Отношения улиц к районам',
	'fields'        =>  array(
		'id' => 'index',
		'street_id' => array(
			'type'			=> 'category',
			'cross_name'	=> 'full_name',
			'cross_index'	=> 'id',
			'cross_table'	=> DB_PREFIX.'street',
			'first'			=> array( '0'	=>	'',),
			'filter' => 'true',
			'sort_by'     => 'name'
		),
		'region_id' => array(
			'type'			=> 'category',
			'cross_name'	=> 'name',
			'cross_index'	=> 'id',
			'cross_table'	=> DB_PREFIX.'region',
			'first'			=> array( '0'	=>	'',),
			'filter' => 'true',
			'sort_by'     => 'name'
		),
	),
	'generator' => array(
		'fields' => array(
			'id' => 'ID',
			'street_id' => 'Улица',
			'region_id' => 'Регион',
		),
		'list' => array(
			'fields' => array(
				'street_id',
				'region_id',
			),
			'title'	 => 'Список',
			'sort_by' => array(
				array(
					'field' => 'region_id',
					'desc'  => 'ASC'
				),
			)
		),
		'edit'	=> array(
			'fields' => array(
				'Данные' => array(
					'street_id',
					'region_id',
				),
			),
			'title'	=> 'Редактирование',
			'submit'=> 'Сохранить',
		),
		'add'	=> array(
			'fields' => array(
				'Данные' => array(
					'street_id',
					'region_id',
				),
			),
			'title'	=> 'Создать',
			'submit'=> 'Создать',
		),
	),
);
CmsGeneratorConfigRegister::add('street_to_region', $street_to_region);
