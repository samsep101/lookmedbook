<?php
	$moderate_clinic_email = array(
		'table'  => DB_PREFIX . 'moderate_clinic_email',
		'title'  => 'Список email клиники',
		'fields' => array(
			'id'   => 'index',
			'email' => array(
				'type' => 'input',
				'placeholder' => 'example@google.com'
			),
			'is_use_to_distribution' => array(
				'type' => 'styled_checkbox',
				'label' => 'Для email-уведомлений'
			)
		),
	);

	CmsGeneratorConfigRegister::add('moderate_clinic_email', $moderate_clinic_email);