<?php
	$moderate_clinic_phone = array(
		'table'  => DB_PREFIX . 'moderate_clinic_phone',
		'title'  => 'Реквизиты клиники',
		'fields' => array(
			'id'   => 'index',
			'phone_number' => 'input',
			'is_use_to_distribution' => array(
				'type' => 'styled_checkbox',
				'label' => 'Для sms-уведомлений',
				'default_value' => '+7'
			)
		),
	);

	CmsGeneratorConfigRegister::add('moderate_clinic_phone', $moderate_clinic_phone);