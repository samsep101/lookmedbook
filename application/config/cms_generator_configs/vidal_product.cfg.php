<?php
	$vidal_product = array(
		'table' => DB_PREFIX . 'product',
		'title' => 'Товары VIDAL',
		'fields' => array(
			'ProductID' => 'index',
			'RusName' => 'just_text',
			'dosage_form' => 'just_text',
			'unit_size' => 'just_text',
			'Composition' => 'just_text',
			'document.PhInfluence' => 'just_text',
			'document.PhKinetics' => 'just_text',
			'document.Dosage' => 'just_text',
			'document.OverDosage' => 'just_text',
			'document.SideEffects' => 'just_text',
			'document.Indications' => 'just_text',
			'document.ContraIndication' => 'just_text',
			'document.manufacturer.RusName' => 'just_text',
		),
		'generator' => array(
			'fields' => array(
				'ProductID' => 'ID',
				'RusName' => 'Название',
				'dosage_form' => 'Форма выпуска',
				'unit_size' => 'Размер единицы',
				'Composition' => 'Состав и форма выпуска',
				'document.PhInfluence' => 'Фармакологическое воздействие',
				'document.PhKinetics' => 'Фармакокинектика',
				'document.Dosage' => 'Дозировка',
				'document.OverDosage' => 'Передозировка',
				'document.SideEffects' => 'Побочные эффекты',
				'document.Indications' => 'Показания',
				'document.ContraIndication' => 'Противопоказания',
				'document.manufacturer.RusName' => 'Производитель',
			),
			'list' => array(
				'filters' => array(
					'use_class_params' => 'VidalProductSearchCriteria',
					'filters' => array(

					),
				),
				'buttons' => array(
					array(
						'class' => 'show-vidal-product',
						'img' => '/media/images/eye.png',
						'title' => 'Показать информацию о товаре',
					),
					array(
						'class' => 'import-vidal-product',
						'img' => '/media/images/misc.png',
						'title' => 'Загрузить данные',
					),
				),
				'fields' => array(
					'ProductID',
					'RusName',
					'dosage_form',
					'unit_size',
					'document.manufacturer.RusName',
				),
				'title' => 'Список товаров VIDAL',
				'sort_by' => array(
					array(
						'field' => 'rus_name_clean',
						'desc' => 'ASC'
					),
				),
			),
			'edit' => array(
				'fields' => array(
					'Основные данные' => array(
						'ProductID',
						'RusName',
						'Composition',
						'unit_size',
						'document.PhInfluence',
						'document.PhKinetics',
						'document.Dosage',
						'document.OverDosage',
						'document.SideEffects',
						'document.Indications',
						'document.ContraIndication',
					),
				),
				'title' => 'Редактирование информацию о товарах',
				'submit' => 'Сохранить',
			),
			'add' => array(
				'fields' => array(
					'Основные данные' => array(
						'ProductID',
						'RusName',
					),
				),
				'title' => 'Добавление информации о товарах',
				'submit' => 'Добавить',
			),
		)
	);

	CmsGeneratorConfigRegister::add('vidal_product', $vidal_product);