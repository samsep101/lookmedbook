<?php
	$cms_product = array(
		'table' => DB_PREFIX . 'product',
		'title' => 'Товары',
		'fields' => array(
			'id' => 'index',
			'article' => 'input',
			'manufacturer.name' => 'just_text',
			'clean_name' => 'just_text',
			'ru_name' => 'input',
			'price' => 'just_text',
			'quantity' => 'just_text',
			'product_category.name' => 'just_text',
			'uploaded_image_id' => array(
				'type' => 'image',
				'base_dir' => 'product/',
				'upload_folder' => 'product/'
			),
			'is_image_confirmed' => array(
				'type' => 'ajax_checkbox',
				'label' => 'Да/Нет',
				'callback' => '$(this).parent().parent().find(\'.field-fill_information_status_id a\').html("Готово");',
			),
			'is_leader' => array(
				'type' => 'ajax_checkbox',
				'label' => 'Да/Нет'
			),
			'zip_info' => array(
				'type' => 'htmlarea',
				'style' => 'width: 400px;'
			),
			'composition' => array(
				'type' => 'htmlarea',
				'style' => 'width: 400px;'
			),
			'dosage' => array(
				'type' => 'htmlarea',
				'style' => 'width: 400px;'
			),
			'side_effects' => array(
				'type' => 'htmlarea',
				'style' => 'width: 400px;'
			),
			'overdosage' => array(
				'type' => 'htmlarea',
				'style' => 'width: 400px;'
			),
			'storage_condition' => array(
				'type' => 'htmlarea',
				'style' => 'width: 400px;'
			),
			'pharma_effects' => array(
				'type' => 'htmlarea',
				'style' => 'width: 400px;'
			),
			'indications' => array(
				'type' => 'htmlarea',
				'style' => 'width: 400px;'
			),
			'interaction' => array(
				'type' => 'htmlarea',
				'style' => 'width: 400px;'
			),
			'lactation' => array(
				'type' => 'htmlarea',
				'style' => 'width: 400px;'
			),
			'storage_conditions' => array(
				'type' => 'htmlarea',
				'style' => 'width: 400px;'
			),
			'special_information' => array(
				'type' => 'htmlarea',
				'style' => 'width: 400px;'
			),
			'pharm_delivery' => array(
				'type' => 'htmlarea',
				'style' => 'width: 400px;'
			),
			'contra_indications' => array(
				'type' => 'htmlarea',
				'style' => 'width: 400px;'
			),
			'fill_information_status_id' => array(
				'type' => 'category',
				'cross_name' => 'name',
				'cross_index' => 'id',
				'cross_table' => DB_PREFIX . 'fill_information_status',
				'first' => array(
					'0' => '',
				),
				'filter' => 'true',
				'sort_by' => 'name',
			),
            'image_find_status.name' => 'just_text',
			'search_field' => array(
				'type' => 'vidal_finder'
			),
            'find_button' => array(
                'type' => 'bing_finder'
            ),
		),
		'extra'     => array(
			'shop_contentmanager_log'                => array(
				'table' => 'shop_contentmanager_log',
				'title' => 'Лог действий контент-менеджера',
				'field' => 'product_id'
			),
		),
		'generator' => array(
			'fields' => array(
				'id' => 'ID',
				'article' => 'Артикул',
				'manufacturer.name' => 'Производитель',
				'product_category.name' => 'Категория',
				'ru_name' => 'Наименование',
				'clean_name' => 'Наименование',
				'price' => 'Цена',
				'quantity' => 'Количество',
				'category_id' => 'Категория товара',
				'uploaded_image_id' => 'Изображение',
				'is_image_confirmed' => ' ',
				'is_leader' => 'Лидер продаж',
				'composition' => 'Состав',
				'zip_info' => 'Форма выпуска',
				'dosage' => 'Способ применения дозы',
				'side_effects' => 'Побочные действия',
				'overdosage' => 'Передозировка',
				'storage_condition' => 'Условия хранения',
				'pharma_effects' => 'Фармакологическое действие',
				'indications' => 'Показания',
				'contra_indications' => 'Противопоказания',
				'fill_information_status_id' => 'Статус заполнения информации',
                'image_find_status.name' => 'Статус загрузки изображения',
				'search_field' => 'Поиск в базе Vidal',
                'find_button' => 'Поиск изображений Bing',
                'interaction' => 'Лекарственное взаимодействие',
                'lactation' => 'Беременность и лактация',
                'special_information' => 'Особые указания',
                'pharm_delivery' => 'Условия отпуска из аптеки',
			),
			'list' => array(
				'fields' => array(
					'uploaded_image_id',
					'is_image_confirmed',
					'clean_name',
					'manufacturer.name',
					'product_category.name',
					'price',
					'quantity',
					'is_active',
					'is_leader',
					'fill_information_status_id',
                    'image_find_status.name',
				),
				'title' => 'Список товаров',
				'search_params' => array(
					'is_active' => '1',
				),
				'sort_by' => array(
					array(
						'field' => 'clean_name',
						'desc' => 'ASC'
					),
				),
				'filters' => array(
					'use_class_params' => 'ProductSearchCriteria',
					'filters' => array(
						'Название товара' => array(
							'full_name' => array(
								'type' => 'input',
								'title' => ''
							),
						),
						'Только активные' => array(
							'is_active' => array(
								'type' => 'checkbox',
								'title' => '',
							),
						),
						'Только лидеры продаж' => array(
							'is_leader' => array(
								'type' => 'checkbox',
								'title' => '',
							),
						),
						'Категория' => array(
							'product_category_id' => array(
								'type' => 'select',
								'manager_class_name' => 'product_category',
								'search_criteria_class_name' => 'ProductCategorySearchCriteria',
								'option_field_name' => 'name',
								'title' => '',
								'search_criteria' => array(
									'is_active' => 1
								),
								'sort_by' => 'name',
							)
						),
						'Производитель' => array(
							'manufacturer_id' => array(
								'type' => 'select',
								'manager_class_name' => 'manufacturer',
								'search_criteria_class_name' => 'ManufacturerSearchCriteria',
								'option_field_name' => 'name',
								'title' => '',
								'sort_by' => 'name',
							)
						),
						'Статус заполнения' => array(
							'fill_information_status_id' => array(
								'type' => 'select',
								'manager_class_name' => 'fill_information_status',
								'search_criteria_class_name' => 'ModelSearchCriteria',
								'option_field_name' => 'name',
								'title' => '',
							)
						),
                        'Статус загрузки изображения' => array(
                            'image_find_status_id' => array(
                                'type' => 'select',
                                'manager_class_name' => 'image_find_status',
                                'search_criteria_class_name' => 'ModelSearchCriteria',
                                'option_field_name' => 'name',
                                'title' => '',
                            )
                        ),

					)
				),
			),
			'edit' => array(
				'fields' => array(
					'Основные данные' => array(
						'product_category.name',
						'manufacturer.name',
						'clean_name',
						'uploaded_image_id',
						'is_image_confirmed',
                        'find_button',
						'price',
						'quantity',
						'is_leader',
						'image_id',
						'fill_information_status_id',
                        'image_find_status.name',
						'search_field',
					),
					'Подробное описание' => array(
						'composition',
						'zip_info',
						'dosage',
						'side_effects',
						'overdosage',
						'storage_condition',
						'pharma_effects',
						'indications',
						'contra_indications',
						'interaction',
						'lactation',
						'special_information',
						'pharm_delivery',
					)
				),
				'title' => 'Редактирование информацию о товарах',
				'submit' => 'Сохранить',

			),
			'add' => array(
				'fields' => array(
					'Основные данные' => array(
						'image_id',
						'manufacturer_id',
						'clean_name',
						'price',
						'quantity',
						'product_category_id',
						'is_leader',
						'image_id',
						'fill_information_status_id',
                        'image_find_status_id',
					),
					'Подробное описание' => array(
						'composition',
						'zip_info',
						'dosage',
						'side_effects',
						'overdosage',
						'storage_condition',
						'pharma_effects',
						'indications',
						'contra_indications',
						'interaction',
						'lactation',
						'special_information',
						'pharm_delivery',
					)
				),
				'title' => 'Добавление информации о товарах',
				'submit' => 'Добавить',
			),
		)
	);

	CmsGeneratorConfigRegister::add('product', $cms_product);