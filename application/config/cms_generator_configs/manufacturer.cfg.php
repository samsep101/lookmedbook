<?php
    $cms_manufacturer = array(
		'table'     => DB_PREFIX . 'manufacturer',
		'title'     => 'Производители',
		'fields'    => array(
			'id'                => 'index',
			'name'             => 'input',
		),
		'generator' => array(
			'fields' => array(
				'id'               => 'ID',
				'name'             => 'Название',
			),
			'list'   => array(
				'fields'  => array('name'),
				'title'   => 'Список',
				'sort_by' => array(
					array(
						'field' => 'name',
						'desc'  => 'ASC'
					),
				),
				'filters' => array(
					'use_class_params' => 'ManufacturerSearchCriteria',
					'filters' => array(
						'Название' => array(
							'name' => array(
								'type' => 'input',
								'title' => ''
							),
						),
					)
				)
			),
			'edit'   => array(
				'fields' => array(
					'Данные' => array(
						'name',
					)
				),
				'title'  => 'Редактирование данных о производителе',
				'submit' => 'Сохранить',
			),
			'add'    => array(
				'fields' => array(
					'Данные' => array(
						'name',
					),
				),
				'title'  => 'Добавление производителя товаров',
				'submit' => 'Добавить',
			),
		)
	);

    CmsGeneratorConfigRegister::add('manufacturer', $cms_manufacturer);