<?php
	$order = array(
		'table'     => DB_PREFIX . 'order', /*имя таблицы*/
		'title'     => 'Заказы', /*меняется "ролей"*/
		'fields'    => array(
			'id'         => 'index', /*всегда*/
			'account.full_name' => array(
				'type'        => 'category',
				'cross_name'  => 'full_name',
				'cross_index' => 'id',
				'cross_table' => DB_PREFIX . 'account',
				'first'       => array(
					'0' => '',
				),
				'filter'      => 'true',
				'sort_by'     => 'full_name',
			),
			'system_code' => 'just_text',
			'phone_number' => 'just_text',
			'name' => 'just_text',
			'email' => 'just_text',
			'address' => 'just_text',
			'comment' => 'just_text',
			'shipping_type.name' => 'just_text',
			'shipping_cost' => 'just_text',
			'payment_type.name' => 'just_text',
			'discount' => 'just_text',
			'total_cost' => 'just_text',
			'dt_order' => 'just_text',
			'order_status.name' => 'just_text'
		),
		'generator' => array(
			'fields' => array(
				'id'         => 'ID',
				'account_id' => 'Аккаунт',
				'phone_number' => 'Номер телефона',
				'name' => 'Имя',
				'email' => 'Email',
				'address' => 'Адрес',
				'comment' => 'Комментарий пользователя',
				'shipping_type.name' => 'Способ доставки',
				'shipping_cost' => 'Стоимость доставки',
				'discount' => 'Скидка',
				'total_cost' => 'Общая стоимость',
				'dt_order' => 'Дата заказа',
				'order_status.name' => 'Статус',
			),
			'list'   => array(
				'fields'  => array(
					'id',
					'phone_number',
					'name',
					'dt_order',
					'order_status.name',
					'total_cost'
				),
				'title'   => 'Заказы',
				'sort_by' => array(
					array(
						'field' => 'id',
						'desc'  => 'DESC'
					),
				),
				'filters' => array(
					'use_class_params' => 'OrderSearchCriteria',
					'filters' => array(
						'Номер заказа' => array(
							'id' => array(
								'type' => 'input',
								'title' => ''
							),
						),
						'Телефонный номер' => array(
							'phone_number' => array(
								'type' => 'input',
								'title' => ''
							),
						),
						'Период создания заказа' => array(
							'dt_from' => array(
								'type' => 'date',
								'title' => 'c'
							),
							'dt_to' => array(
								'type' => 'date',
								'title' => 'по'
							),
						),
					)
				),
			),
			'edit'   => array(
				'fields' => array(
					'Данные' => array(
						'account_id',
						'system_code',
						'phone_number',
						'name',
						'email',
						'address',
						'comment',
						'shipping_type.name',
						'shipping_cost',
						'discount',
						'dt_order',
						'order_status.name',
						'total_cost'
					),
				),
				'title'  => 'Редактирование',
				'submit' => 'Сохранить',
			),
			'add'    => array(
				'fields' => array(
					'Данные' => array(

					),
				),
				'title'  => 'Добавить',
				'submit' => 'Добавить',
			),
		),
		'extra' => array(
			'product'                => array(
				'table' => 'product_to_order',
				'title' => 'Товары',
				'field' => 'order_id'
			),
			'order_status_change'                => array(
				'table' => 'order_status_change',
				'title' => 'Изменения статуса',
				'field' => 'order_id'
			),
		)
	);

	CmsGeneratorConfigRegister::add('order', $order);
