<?php

$seo_text = array(
	'table'         =>  DB_PREFIX.'seo_text',
	'title'         =>  'SEO-тексты',
	'fields'        =>  array(
		'id' => 'index',
		'specialty_id' => array(
			'type'			=> 'category',
			'cross_name'	=> 'name',
			'cross_index'	=> 'id',
			'cross_table'	=> DB_PREFIX.'specialty',
			'first'			=> array( '0'	=>	'',),
			'filter' => 'true',
			'sort_by'     => 'name'
		),
		'city_id' => array(
			'type'			=> 'category',
			'cross_name'	=> 'name',
			'cross_index'	=> 'id',
			'cross_table'	=> DB_PREFIX.'city',
			'first'			=> array( '0'	=>	'',),
			'filter' => 'true',
			'sort_by'     => 'name'
		),
		'district_id' => array(
			'type'			=> 'category',
			'cross_name'	=> 'name',
			'cross_index'	=> 'id',
			'cross_table'	=> DB_PREFIX.'district',
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
		'street_id' => array(
			'type'			=> 'category',
			'cross_name'	=> 'full_name',
			'cross_index'	=> 'id',
			'cross_table'	=> DB_PREFIX.'street',
			'first'			=> array( '0'	=>	'',),
			'filter' => 'true',
			'sort_by'     => 'name'
		),
		'metro_station_id' => array(
			'type'			=> 'category',
			'cross_name'	=> 'name_with_city_name',
			'cross_index'	=> 'id',
			'cross_table'	=> DB_PREFIX.'metro_station',
			'first'			=> array( '0'	=>	'',),
			'filter' => 'true',
			'sort_by'     => 'name'
		),
		'text' => 'text',
	),
	'generator' => array(
		'fields' => array(
			'id' => 'ID',
			'specialty_id' => 'Специальность',
			'city_id' => 'Город',
			'district_id' => 'Округ',
			'region_id' => 'Регион',
			'street_id' => 'Улица',
			'metro_station_id' => 'Станция метро',
			'text' => 'Текст',
		),
		'list' => array(
			'fields' => array(
				'specialty_id',
				'city_id',
				'district_id',
				'region_id',
				'street_id',
				'metro_station_id',
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
					'specialty_id',
					'city_id',
					'district_id',
					'region_id',
					'street_id',
					'metro_station_id',
					'text',
				),
			),
			'title'	=> 'Редактирование',
			'submit'=> 'Сохранить',
		),
		'add'	=> array(
			'fields' => array(
				'Данные' => array(
					'specialty_id',
					'city_id',
					'district_id',
					'region_id',
					'street_id',
					'metro_station_id',
					'text',
				),
			),
			'title'	=> 'Создать',
			'submit'=> 'Создать',
		),
	),
);
CmsGeneratorConfigRegister::add('seo_text', $seo_text);
