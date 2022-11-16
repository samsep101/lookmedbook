<?php
$specialization_synonym = array(
	'table'     => DB_PREFIX . 'specialty_synonym',
	'title'     => 'Синонимы специализаций',
	'fields'    => array(
		'id'           => 'index',
		'name'         => 'input',
		'specialty_id'    => array(
			'type'        => 'category',
			'cross_name'  => 'name',
			'cross_index' => 'id',
			'cross_table' => DB_PREFIX . 'specialty',
			'first'       => array(
				'0' => '',
			),
			'sort_by'     => 'name',
		),
		'dative_name'  => 'input',
		'genitive_name'  => 'input',
		'plural_name' => 'input',
	),
	'generator' => array(
		'fields' => array(
			'id'           => 'ID',
			'name'         => 'Название',
			'specialty_id'    => 'Родительская специализация',
			'genitive_name' => 'Название в родительном падеже',
			'dative_name'  => 'Название в дательном падеже',
			'plural_name'  => 'Название в множественном числе'
		),
		'list'   => array(
			'fields'  => array('name', 'genitive_name', 'dative_name', 'plural_name', 'specialty_id'),
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
					'name',
					'specialty_id',
					'genitive_name',
					'dative_name',
					'plural_name'
				),
			),
			'title'  => 'Редактирование',
			'submit' => 'Сохранить',
		),
		'add'    => array(
			'fields' => array(
				'Данные' => array(
					'specialty_id',
					'name',
					'genitive_name',
					'dative_name',
					'plural_name'
				),
			),
			'title'  => 'Создание',
			'submit' => 'Создать',
		),
	),
);


CmsGeneratorConfigRegister::add('specialty_synonym', $specialization_synonym);