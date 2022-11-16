<?php
    $cms_account = array(
        'table'     => DB_PREFIX . 'account',
        'title'     => 'Аккаунты',
        'fields'    => array(
            'id'               => 'index',
            'first_name'       => 'input',
            'last_name'        => 'input',
            'middle_name'      => 'input',
            'full_name'        => 'input',
            'nick'             => 'input',
            'birthday'         => 'date',
            'email'            => 'input',
            'is_confirm_email' => array(
                'type'  => 'checkbox',
                'label' => 'Да/нет'
            ),
            'password'         => 'input',
            'image_id'         => array(
                'type'          => 'image',
                'base_dir'      => 'account/',
                'upload_folder' => 'account/'
            ),
            'city_id'          => array(
                'type'        => 'category',
                'cross_name'  => 'name',
                'cross_index' => 'id',
                'cross_table' => DB_PREFIX . 'city',
                'first'       => array(
                    '0' => '',
                ),
                'filter'      => 'true',
                'sort_by'     => 'name',
            ),
            'is_confirmed'     => array(
                'type'  => 'checkbox',
                'label' => 'Да/нет'
            ),
            'dt'               => 'date',
            'is_system_access'     => array(
                'type'  => 'checkbox',
                'label' => 'Да/нет'
            ),
            'is_call_centre_operator'     => array(
                'type'  => 'checkbox',
                'label' => 'Да/нет'
            ),
            'is_product_admin'     => array(
                'type'  => 'checkbox',
                'label' => 'Да/нет'
            ),
        ),
        'extra'     => array(
            'appeals'                => array(
                'table' => 'appeal',
                'title' => 'Обращения',
                'field' => 'account_id'
            ),
            'phones'                => array(
                'table' => 'account_phone',
                'title' => 'Телефонные номера',
                'field' => 'account_id'
            ),
            'vk_account'            => array(
                'table' => 'vk_account',
                'title' => 'Профиль vk.com',
                'field' => 'account_id'
            ),
            'fb_account'            => array(
                'table' => 'fb_account',
                'title' => 'Профиль facebook.com',
                'field' => 'account_id'
            ),
            'mailru_account'        => array(
                'table' => 'mailru_account',
                'title' => 'Профиль mail.ru',
                'field' => 'account_id'
            ),
            'ok_account'            => array(
                'table' => 'ok_account',
                'title' => 'Профиль odnoklassniki.ru',
                'field' => 'account_id'
            ),
            'family'                => array(
                'table' => 'family_relation',
                'title' => 'Добавил в список родственников',
                'field' => 'account1_id'
            ),
            'my_clinic'             => array(
                'table' => 'my_clinic',
                'title' => 'Избранные клиники',
                'field' => 'account_id'
            ),
            'my_doctor'             => array(
                'table' => 'my_doctor',
                'title' => 'Избранные врачи',
                'field' => 'account_id'
            ),
            'my_disease'            => array(
                'table' => 'my_disease',
                'title' => 'Избранные заболевания',
                'field' => 'account_id'
            ),
            'notification_settings' => array(
                'table' => 'notification_settings',
                'title' => 'Настройки уведомлений',
                'field' => 'account_id'
            ),
            'visit'                 => array(
                'table' => 'visit',
                'title' => 'Записи к врачу',
                'field' => 'account_id'
            ),
            'doctor_review'         => array(
                'table' => 'doctor_review',
                'title' => 'Отзывы о врачах',
                'field' => 'account_id'
            ),
            'clinic_review'         => array(
                'table' => 'clinic_review',
                'title' => 'Отзывы о клиниках',
                'field' => 'account_id'
            ),
        ),
        'generator' => array(
            'fields' => array(
                'id'               => 'ID',
                'first_name'       => 'Имя',
                'last_name'        => 'Фамилия',
                'middle_name'      => 'Отчество',
                'full_name'        => 'ФИО',
                'nick'             => 'Имя на сайте',
                'birthday'         => 'День рождения',
                'email'            => 'Email',
                'is_confirm_email' => 'Email подтверждён',
                'password'         => 'Пароль',
                'image_id'         => 'Фото',
                'is_confirmed'     => 'Подтвержден',
                'is_system_access' => 'Доступ к аналитической информации',
                'is_call_centre_operator' => 'Оператор call-центра',
                'is_product_admin' => 'Администратор лекарств',
            ),
            'list'   => array(
                'fields'  => array(
                    'full_name',
                    'nick',
                    'birthday',
                    'email',
                    'is_confirm_email',
                ),
                'title'   => 'Список аккаунтов',
                'sort_by' => array(
                    array(
                        'field' => 'id',
                        'desc'  => 'DESC'
                    ),
                ),
				'filters' => array(
					'use_class_params' => 'AccountSearchCriteria',
					'filters' => array(
						'ФИО' => array(
							'last_name' => array(
								'type' => 'input',
								'title' => 'Фамилия'
							),
							'first_name' => array(
								'type' => 'input',
								'title' => 'Имя'
							),
							'middle_name' => array(
								'type' => 'input',
								'title' => 'Отчество'
							)
						),
						'Телефонный номер' => array(
							'phone_number' => array(
								'type' => 'input',
								'title' => ''
							),
						),
						'Email' => array(
							'email' => array(
								'type' => 'input',
								'title' => ''
							),
						)
					)
				)
            ),
            'edit'   => array(
                'fields'  => array(
                    'Данные аккаунта' => array(
                        'last_name',
                        'first_name',
                        'middle_name',
                        'nick',
                        'birthday',
                        'image_id',
                        'email',
                        'is_confirm_email',
                        'password',
                        'is_confirmed',
                        'is_system_access',
                        'is_call_centre_operator',
                        'is_product_admin',
                    ),
                ),
                'tooltip' => '<p>Для того, чтобы <b>изменения вступили в силу</b>, необходимо выйти и войти в систему.</p>',
                'title'   => 'Редактирование',
                'submit'  => 'Сохранить',
            ),
            'add'    => array(
                'fields'  => array(
                    'Тип аккаунта' => array(
                        'last_name',
                        'first_name',
                        'middle_name',
                        'nick',
                        'birthday',
                        'image_id',
                        'email',
                        'is_confirm_email',
                        'password',
                        'is_confirmed',
                        'is_system_access',
                        'is_call_centre_operator',
                        'is_product_admin'
                    ),
                ),
                'tooltip' => '<p>Для того, чтобы <b>изменения вступили в силу</b>, необходимо выйти и войти в систему.</p>',
                'title'   => 'Добавить',
                'submit'  => 'Добавить',
            ),
        ),
    );

    CmsGeneratorConfigRegister::add('account', $cms_account);