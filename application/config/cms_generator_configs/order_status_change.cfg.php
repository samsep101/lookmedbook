<?php
	$order_status_change = array(
		'table'     => DB_PREFIX . 'order_status_change', /*имя таблицы*/
		'title'     => 'Изменения статуса заказа', /*меняется "ролей"*/
		'fields'    => array(
			'id'         => 'index', /*всегда*/
			'prev_order_status.name' => 'just_text',
			'new_order_status.name' => 'just_text',
			'order_id' => 'just_text',
			'dt' => 'just_text'
		),
		'generator' => array(
			'fields' => array(
				'id'         => 'ID',
				'prev_order_status.name' => 'Предыдущий статус',
				'new_order_status.name' => 'Новый статус',
				'dt' => 'Время',
				'order_id' => 'Номер заказа'
			),
			'list'   => array(
				'fields'  => array(
					'order_id',
					'dt',
					'prev_order_status.name',
					'new_order_status.name',
				),
				'title'   => 'Изменения статуса заказа',
				'sort_by' => array(
					array(
						'field' => 'id',
						'desc'  => 'DESC'
					),
				),
			),
			'edit'   => array(
				'fields' => array(
					'Данные' => array(
						'order_id',
						'dt',
						'prev_order_status.name',
						'new_order_status.name',
					),
				),
				'title'  => 'Редактирование',
				'submit' => 'Сохранить',
			),
			'add'    => array(
				'fields' => array(
					'Данные' => array(
						'order_id',
						'dt',
						'prev_order_status.name',
						'new_order_status.name',
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
		)
	);

	CmsGeneratorConfigRegister::add('order_status_change', $order_status_change);
