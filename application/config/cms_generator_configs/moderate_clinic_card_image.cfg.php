<?php
$moderate_clinic_card_image = array(
	'table'         =>  DB_PREFIX.'moderate_doctor_information',
	'title'         =>  'Модерируемая информация: изображения клиники',
	'fields'        =>  array(
		'id' => 'index',
		'card_image_id' => array(
			'type' => 'image',
			'folder' => 'doctor/',
		),
		'moderate_status_id' => array(
			'type'	=>	'listvalue',
			'values'	=>	array(
				'1' =>  'Редактируется клиникой',
				'2'	=>	'Нужна проверка',
				'3'	=>	'Отправлено в клинику на доработку',
				'4'	=>	'Опубликован на '.SITE_NAME,
			),
			'filter' => 'true'
		),
	),
	'generator' => array(
		'fields' => array(
			'id' => 'ID',
			'card_image_id' => 'Изображение',
			'moderate_status_id' => 'Статус модерации',
		),
		'list' => array(
			'fields' => array(
				'card_image_id',
				'moderate_status_id',
			),
			'title'	 => 'Список',
			'sort_by' => array(
				array(
					'field' => 'id',
					'desc'  => 'DESC'
				),
			)
		),
		'edit'	=> array(
			'fields' => array(
				'Данные' => array(
					'card_image_id',
					'moderate_status_id',
				),
			),
			'title'	=> 'Редактирование',
			'submit'=> 'Сохранить',
		),
		'add'	=> array(
			'fields' => array(
				'Данные' => array(
					'card_image_id',
					'moderate_status_id',
				),
			),
			'title'	=> 'Создать',
			'submit'=> 'Создать',
		),
	),
);
CmsGeneratorConfigRegister::add('moderate_clinic_card_image', $moderate_clinic_card_image);
