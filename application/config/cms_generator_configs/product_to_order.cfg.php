<?php
	$product_to_order = array(
		'table'     => DB_PREFIX . 'product_to_order', /*имя таблицы*/
		'title'     => 'Отношение товаров к заказам', /*меняется "ролей"*/
		'fields'    => array(
			'id'                 => 'index', /*всегда*/
			'product_id'          => array(
				'type'        => 'category',
				'cross_name'  => 'clean_name',
				'cross_index' => 'id',
				'cross_table' => DB_PREFIX . 'product',
				'first'       => array(
					'0' => '',
				),
				'filter'      => 'true',
				'sort_by'     => 'clean_name',
			),
			'order_id'          => array(
				'type'        => 'category',
				'cross_name'  => 'id',
				'cross_index' => 'id',
				'cross_table' => DB_PREFIX . 'order',
				'first'       => array(
					'0' => '',
				),
				'filter'      => 'true',
				'sort_by'     => 'name',
			),
			'amount' => 'just_text',
			'price' => 'just_text'
		),
		'generator' => array(
			'fields' => array(
				'id'                 => 'ID',
				'product_id'          => 'Товар',
				'order_id'          => 'Заказ',
				'amount'          => 'Количество',
				'price'          => 'Цена',

			),
			'list'   => array(
				'fields'  => array('role_id', 'product_id', 'amount', 'price'), /*поля кот. отображаются в списке "суперадминистратор"*/
				'title'   => 'Товары для заказов',
			),
			'edit'   => array(
				'fields' => array(
					'Отношение' => array('role_id', 'product_id', 'amount', 'price')
				),
				'title'  => 'Редактирование',
				'submit' => 'Сохранить',
			),
			'add'    => array(
				'fields' => array(
					'Связь' => array('role_id', 'product_id', 'amount', 'price')
				),
				'title'  => 'Добавить',
				'submit' => 'Добавить',
			),
		),
	);

	CmsGeneratorConfigRegister::add('product_to_order', $product_to_order);