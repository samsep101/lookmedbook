<?php
	$shop_content_manager_log = array(
		'table' => DB_PREFIX . 'shop_contentmanager_log',
		'title' => 'Лог действий менеджера интернет-магазина',
		'fields' => array(
			'id' => 'index',
			'action' => 'just_text',
			'dt' => 'just_text',
			'product_id' => array(
				'type' => 'category',
				'cross_name' => 'clean_name',
				'cross_index' => 'id',
				'cross_table' => DB_PREFIX . 'product',
				'filter' => 'true',
			),
			'user_id' => array(
				'type' => 'category',
				'cross_name' => 'login',
				'cross_index' => 'id',
				'cross_table' => DB_PREFIX . 'user',
				'filter' => 'true',
				'first' => array(
					0 => '',
				)
			),
		),

		'generator' => array(
			'fields' => array(
				'id' => 'ID',
				'dt' => 'Дата и время',
				'action' => 'Действие',
				'product_id' => 'Товар',
				'user_id' => 'Пользователь',
			),
			'list' => array(
				'fields' => array(
					'id',
					'dt',
					'user_id',
					'product_id',
					'action',
				),
				'title' => 'Обращения',
				'sort_by' => array(
					array(
						'field' => 'id',
						'desc' => 'DESC'
					),
				),
				'filters' => array(
					'use_class_params' => 'AppealSearchParams',
					'filters' => array(
						'Период создания заявки' => array(
							'dt_create_from' => array(
								'type' => 'date',
								'title' => 'c'
							),
							'dt_create_to' => array(
								'type' => 'date',
								'title' => 'по'
							),
						),
					)
				),
			),
			'edit' => array(
				'fields' => array(
					'Данные' => array(
						'id',
						'dt',
						'user_id',
						'product_id',
						'action',
					),
				),
				'tooltip' => '<p>Для того, чтобы <b>изменения вступили в силу</b>, необходимо выйти и войти в систему.</p>',
				'title' => 'Редактирование',
				'submit' => 'Сохранить',
			),
			'add' => array(
				'fields' => array(
					'Данные' => array(
						'id',
						'dt',
						'user_id',
						'product_id',
						'action',
					),
				),
				'tooltip' => '<p>Для того, чтобы <b>изменения вступили в силу</b>, необходимо выйти и войти в систему.</p>',
				'title' => 'Добавить',
				'submit' => 'Добавить',
			),
		),
	);

	CmsGeneratorConfigRegister::add('shop_contentmanager_log', $shop_content_manager_log);