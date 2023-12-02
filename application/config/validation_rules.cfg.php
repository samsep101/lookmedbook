<?php
    $validation_rules = new ValidationRules();

	$rules = array(
		'required'     => array(
			'value'   => TRUE,
			'message' => 'Поле обязательно для заполнения',
			'code'    => ValidationErrorCodes::IS_REQUIRED_FIELD,
		),
		'email'  => array(
			'value'   => TRUE,
			'message' => 'В поле email должен быть введён корректный email-адрес',
			'code'    => ValidationErrorCodes::INVALID_EMAIL
		),
		'letters_with_symbols'     => array(
			'value'   => 3,
			'message' => 'Поле содержит недопустимые символы',
			'code' => ValidationErrorCodes::WRONG_VALUE_FORMAT
		),
		'time'     => array(
			'value'   => TRUE,
			'message' => 'Поле должно содержать корректное время в формате hh:ii',
			'code'    => ValidationErrorCodes::WRONG_VALUE_FORMAT,
		),
		'date'     => array(
			'value'   => TRUE,
			'message' => 'Поле должно содержать корректную дату',
			'code'    => ValidationErrorCodes::WRONG_VALUE_FORMAT,
		),
	);

	$validation_rules->add(
		'required_email',
		array(
			'email' => $rules['email'],
			'required' => $rules['required']
		)
	);

	$validation_rules->add(
		'correct_email',
		array(
			 'email' => $rules['email'],
		)
	);

	$validation_rules->add(
		'required',
		array(
			'required'     => array(
				'value'   => TRUE,
				'message' => 'Поле обязательно для заполнения',
				'code'    => ValidationErrorCodes::IS_REQUIRED_FIELD,
			),
		)
	);

	$validation_rules->add(
		'date',
		array(
			'date' => $rules['date'],
		)
	);

	$validation_rules->add(
		'required_date',
		array(
			'required' => $rules['required'],
			'date' => $rules['date'],
		)
	);

	$validation_rules->add(
		'required_time',
		array(
			'time' => $rules['time'],
			'required' => $rules['required'],
		)
	);

	$validation_rules->add(
		'digits',
		array(
			'digits'     => array(
				'value'   => TRUE,
				'message' => 'Поле может содержать только числа',
				'code'    => ValidationErrorCodes::WRONG_VALUE_FORMAT,
			),
		)
	);

    $validation_rules->add(
        'time',
        array(
            'time'     => array(
                'value'   => TRUE,
                'message' => 'Поле должно содержать время в формате hh:ii',
                'code'    => ValidationErrorCodes::WRONG_VALUE_FORMAT,
            ),
        )
    );

    $validation_rules->add(
        'full_name',
        array(
            'min_words_count' => array(
                'value'   => 2,
                'message' => 'Пожалуйста, укажи полные фамилию, имя и отчество',
                'code'    => ValidationErrorCodes::WRONG_FULL_NAME_LENGTH
            ),
            'required'    => $rules['required']
        )
    );

    $validation_rules->add(
        'nick_name',
        array(
            'length_range' => array(
                'value'   => '2-10',
                'message' => 'Длина поля "Имя на сайте" должна быть от 2 до 10 символов',
                'code'    => ValidationErrorCodes::WRONG_NICK_LENGTH
            ),
        )
    );

    $validation_rules->add(
        'birthday_date',
        array(
            'date' => array(
                'value'   => TRUE,
                'message' => 'Укажите корректную дату рождения',
                'code'    => ValidationErrorCodes::WRONG_DATE
            ),
        )
    );

    $validation_rules->add(
        'email',
        array(
            'email'  => array(
                'value'   => TRUE,
                'message' => 'В поле email должен быть введён корректный email-адрес',
                'code'    => ValidationErrorCodes::INVALID_EMAIL
            ),
            'unique' => array(
                'value'       => 'account.email',
                'js_callback' => '/ajax/checkEmail',
                'message'     => 'Адрес уже зарегистрирован',
                'code'        => ValidationErrorCodes::ALREADY_REGISTERED,
            ),
            'required' => array(
                'value'   => TRUE,
                'message' => 'Поле обязательно для заполнения',
                'code'    => ValidationErrorCodes::IS_REQUIRED_FIELD
            ),
        )
    );

    $validation_rules->add(
        'user_email',
        array(
            'email'  => array(
                'value'   => TRUE,
                'message' => 'В поле email должен быть введён корректный email-адрес',
                'code'    => ValidationErrorCodes::INVALID_EMAIL
            ),
            'unique' => array(
                'value'       => 'user.email',
                'js_callback' => '/ajax/checkUserEmail',
                'message'     => 'Адрес уже зарегистрирован',
                'code'        => ValidationErrorCodes::ALREADY_REGISTERED,
            ),
        )
    );



    $validation_rules->add(
        'clinic_email',
        array(
            'email'  => array(
                'value'   => TRUE,
                'message' => 'В поле должен быть введён корректный email-адрес',
                'code'    => ValidationErrorCodes::INVALID_EMAIL
            ),
        )
    );

    $validation_rules->add(
        'login',
        array(
            'length_range' => array(
                'value'   => '5-20',
                'message' => 'Длина поля логином должна быть от 5 до 20 символов',
                'code'    => ValidationErrorCodes::INVALID_LOGIN
            ),
            'unique'       => array(
                'value'       => 'account.login',
                'js_callback' => '/ajax/checkLogin',
                'message'     => 'Пользователь с данным логином уже зарегистрирован',
                'code'        => ValidationErrorCodes::ALREADY_REGISTERED,
            ),
        )
    );

	$validation_rules->add(
		'visit_purpose',
		array(
			'required' => array(
				'value'   => true,
				'message' => 'Укажи цель визита',
				'code'    => ValidationErrorCodes::WRONG_PASSWORD
			),
		)
	);

    $validation_rules->add(
        'password',
        array(
            'required' => array(
                'value'   => true,
                'message' => 'Длина пароля должна быть не менее 6 символов',
                'code'    => ValidationErrorCodes::WRONG_PASSWORD
            ),
            'min_length' => array(
                'value'   => '6',
                'message' => 'Длина пароля должна быть не менее 6 символов',
                'code'    => ValidationErrorCodes::WRONG_PASSWORD
            ),
        )
    );


	$validation_rules->add(
		'edit_password',
		array(
			'min_length' => array(
				'value'   => '6',
				'message' => 'Длина пароля должна быть не менее 6 символов',
				'code'    => ValidationErrorCodes::WRONG_PASSWORD
			),
		)
	);


    $validation_rules->add(
        'password2',
        array(
            'match' => array(
                'value'   => 'password',
                'message' => 'Пароль не совпадает',
                'code'    => ValidationErrorCodes::WRONG_PASSWORD
            ),
        )
    );

    $validation_rules->add(
        'password_repeat',
        array(
            'match' => array(
                'value'   => 'set_password',
                'message' => 'Пароль не совпадает',
                'code'    => ValidationErrorCodes::WRONG_PASSWORD
            ),
        )
    );

    $validation_rules->add(
        'phone',
        array(
            'phone'  => array(
                'value'   => TRUE,
                'message' => 'В поле "Телефон" должен быть введён корректный телефон',
                'code'    => ValidationErrorCodes::INVALID_PHONE
            ),
            'unique' => array(
                'value'       => 'account_phone.phone',
                'js_callback' => '/ajax/checkPhone',
                'message'     => 'Пользователь с данным номером телефона уже зарегистрирован',
                'code'        => ValidationErrorCodes::ALREADY_REGISTERED,
            ),
        )
    );

	$validation_rules->add(
		'clinic_phone',
		array(
			'phone'  => array(
				'value'   => TRUE,
				'message' => 'В поле должен быть введён корректный телефон',
				'code'    => ValidationErrorCodes::INVALID_PHONE
			),
		)
	);

    $validation_rules->add(
        'target_call_phone',
        array(
            'phone'  => array(
                'value'   => TRUE,
                'message' => 'В поле "Телефон" должен быть введён корректный телефон',
                'code'    => ValidationErrorCodes::INVALID_PHONE
            ),
            'unique' => array(
                'value'       => 'target_call.phone',
                'js_callback' => '/ajax/checkTargetCallPhone',
                'message'     => 'Запись с данным номером телефона уже есть в базе',
                'code'        => ValidationErrorCodes::ALREADY_REGISTERED,
            ),
        )
    );

    $validation_rules->add(
        'cabinet',
        array(
            'required' => array(
                'value'   => TRUE,
                'message' => 'Вы не оценили "Кабинет"',
                'code'    => ValidationErrorCodes::WRONG_REVIEW_DATA
            ),
        )
    );

    $validation_rules->add(
        'waiting_time',
        array(
            'required' => array(
                'value'   => TRUE,
                'message' => 'Вы не оценили "Время ожидания"',
                'code'    => ValidationErrorCodes::WRONG_REVIEW_DATA
            ),
        )
    );

    $validation_rules->add(
        'relationship',
        array(
            'required' => array(
                'value'   => TRUE,
                'message' => 'Вы не оценили "Отношения к пациенту"',
                'code'    => ValidationErrorCodes::WRONG_REVIEW_DATA
            ),
        )
    );

    $validation_rules->add(
        'value_for_money',
        array(
            'required' => array(
                'value'   => TRUE,
                'message' => 'Вы не оценили "Соответствие цене"',
                'code'    => ValidationErrorCodes::WRONG_REVIEW_DATA
            ),
        )
    );

    $validation_rules->add(
        'diagnosis_is_clear',
        array(
            'required' => array(
                'value'   => TRUE,
                'message' => 'Вы не оценили "Диагноз и лечение мне ясны',
                'code'    => ValidationErrorCodes::WRONG_REVIEW_DATA
            ),
        )
    );

    $validation_rules->add(
        'service_at_the_reception',
        array(
            'required' => array(
                'value'   => TRUE,
                'message' => 'Вы не оценили "Сервис в регистратуре"',
                'code'    => ValidationErrorCodes::WRONG_REVIEW_DATA
            ),
        )
    );

    $validation_rules->add(
        'is_doctor_advice',
        array(
            'required' => array(
                'value'   => TRUE,
                'message' => 'Вы не оценили "Врача"',
                'code'    => ValidationErrorCodes::WRONG_REVIEW_DATA
            ),
        )
    );

    $validation_rules->add(
        'is_clinic_advice',
        array(
            'required' => array(
                'value'   => TRUE,
                'message' => 'Вы не оценили "Клинику"',
                'code'    => ValidationErrorCodes::WRONG_REVIEW_DATA
            ),
        )
    );

    $validation_rules->add(
        'doctor_review',
        array(
            'required' => array(
                'value'   => TRUE,
                'message' => 'Вы не написали отзыв',
                'code'    => ValidationErrorCodes::WRONG_REVIEW_DATA
            ),
        )
    );

    $validation_rules->add(
        'doctor',
        array(
            'required' => array(
                'value'   => TRUE,
                'message' => 'Вы не выбрали врача',
                'code'    => ValidationErrorCodes::WRONG_REVIEW_DATA
            ),
        )
    );

    $validation_rules->add(
        'account',
        array(
            'required' => array(
                'value'   => TRUE,
                'message' => 'Вы не выбрали пользователя',
                'code'    => ValidationErrorCodes::WRONG_REVIEW_DATA
            ),
        )
    );

    $validation_rules->add(
        'clinic_review',
        array(
            'required' => array(
                'value'   => TRUE,
                'message' => 'Вы не написали отзыв',
                'code'    => ValidationErrorCodes::WRONG_REVIEW_DATA
            ),
        )
    );

    $validation_rules->add(
        'private_review',
        array(
            'required' => array(
                'value'   => TRUE,
                'message' => 'Вы не написали отзыв',
                'code'    => ValidationErrorCodes::WRONG_REVIEW_DATA
            ),
        )
    );

    $validation_rules->add(
        'order_time',
        array(
            'order_time' => array(
                'value'   => TRUE,
                'message' => 'Данное время уже прошло Пожалуйста, выберите актуальное время',
                'code'    => ValidationErrorCodes::OLD_DATE
            ),
        )
    );

    $validation_rules->add(
        'email_right',
        array(
            'email'  => array(
                'value'   => TRUE,
                'message' => 'В поле "E-mail" должен быть введён корректный email-адрес',
                'code'    => ValidationErrorCodes::INVALID_EMAIL
            ),
            'required'  => array(
                'value'   => TRUE,
                'message' => 'Поле "E-mail" обязательно для заполнения',
                'code'    => ValidationErrorCodes::IS_REQUIRED_FIELD,
            ),
        )
    );

    $validation_rules->add(
        'phone_right',
        array(
            'phone'  => array(
                'value'   => TRUE,
                'message' => 'В поле должен быть введён корректный телефон! Пример: +7-495-111-11-11',
                'code'    => ValidationErrorCodes::WRONG_FULL_NAME_LENGTH,
            ),
        )
    );

    $validation_rules->add(
        'visit_phone',
        array(
            'phone'  => array(
                'value'   => TRUE,
                'message' => 'В поле должен быть введён корректный телефон! Пример: +7-495-111-11-11',
                'code'    => ValidationErrorCodes::WRONG_FULL_NAME_LENGTH,
            ),
            'required'  => array(
                'value'   => TRUE,
                'message' => 'Необходимо ввести телефон пациента',
                'code'    => ValidationErrorCodes::IS_REQUIRED_FIELD,
            ),
        )
    );

    $validation_rules->add(
        'full_name_required',
        array(
            'required'  => array(
                'value'   => TRUE,
                'message' => 'Поле "ФИО" обязательно для заполнения',
                'code'    => ValidationErrorCodes::WRONG_FULL_NAME_LENGTH,
            ),
        )
    );

    $validation_rules->add(
        'first_name',
        array(
            'required'     => array(
                'value'   => TRUE,
                'message' => 'Поле "Имя" обязательно для заполнения',
                'code'    => ValidationErrorCodes::WRONG_FIRST_NAME_LENGTH,
            ),
        )
    );

    $validation_rules->add(
        'last_name',
        array(
            'required'     => array(
                'value'   => TRUE,
                'message' => 'Поле "Фамилия" обязательно для заполнения',
                'code'    => ValidationErrorCodes::WRONG_LAST_NAME_LENGTH,
            ),
        )
    );

    $validation_rules->add(
        'second_name',
        array(
            'required'     => array(
                'value'   => TRUE,
                'message' => 'Поле "Отчество" обязательно для заполнения',
                'code'    => ValidationErrorCodes::WRONG_SECOND_NAME_LENGTH,
            ),
        )
    );

    $validation_rules->add(
        'about',
        array(
            'required'     => array(
                'value'   => TRUE,
                'message' => 'Поле "Опыт / Компетенции" обязательно для заполнения',
                'code'    => ValidationErrorCodes::WRONG_ABOUT_LENGTH,
            ),
        )
    );

	$validation_rules->add(
		'director_fio',
		array(
			'letters' => array(
				'value' => TRUE,
				'message' => 'Поле должно содержать только буквы',
				'code' => ValidationErrorCodes::WRONG_VALUE_FORMAT
			),
            /*
            'min_words_count' => array(
                'value'   => 3,
                'message' => 'Пожалуйста, укажи полные фамилию, имя и отчество',
                'code'    => ValidationErrorCodes::WRONG_FULL_NAME_LENGTH
            ),*/

		)
	);

	$validation_rules->add(
		'about_clinic',
		array(
			'required' => array(
				'value' => TRUE,
				'message' => 'Поле обязательно для заполнения',
				'code' => ValidationErrorCodes::IS_REQUIRED_FIELD
			),
			'letters_with_symbols'     => array(
				'value'   => 3,
				'message' => 'Поле содержит недопустимые символы',
				'code' => ValidationErrorCodes::WRONG_VALUE_FORMAT
			),
		)
	);

    $validation_rules->add(
        'clinic_postcode',
        array(
            'required' => array(
                'value' => TRUE,
                'message' => 'Поле обязательно для заполнения',
                'code' => ValidationErrorCodes::IS_REQUIRED_FIELD
            ),
            'numeric' => array(
                'value' => TRUE,
                'message' => 'Поле может содержать только цифры',
                'code' => ValidationErrorCodes::WRONG_VALUE_FORMAT
            ),
            'postcode_length' => array(
                'value' => 6,
                'message' => 'Длина почтового индекса должна быть 6 символов',
                'code' => ValidationErrorCodes::INVALID_POSTCODE
            ),
        )
    );

	$validation_rules->add(
		'clinic_full_name',
		array(
			'required' => array(
				'value' => TRUE,
				'message' => 'Поле обязательно для заполнения',
				'code' => ValidationErrorCodes::IS_REQUIRED_FIELD
			),
		)
	);

	$validation_rules->add(
		'clinic_type_id',
		array(
			'list_items' => array(
				'value' => TRUE,
				'message' => 'Выберите к какому типу принажит клиника',
				'code' => ValidationErrorCodes::IS_REQUIRED_FIELD
			),
		)
	);

	$validation_rules->add(
		'license_number',
		array(
			'regex' => array(
				'value' => '^[0-9a-zA-Zа-яА-Я]{2}\-[0-9a-zA-Zа-яА-Я]{2}\-[0-9a-zA-Zа-яА-Я]{6}$',
				'message' => 'Значение в поле не соответствует формату',
				'code' => ValidationErrorCodes::WRONG_VALUE_FORMAT
			),
		)
	);

	$validation_rules->add(
		'license_issue_date',
		array(
			'date' => array(
				'value' => TRUE,
				'message' => 'Поле содержит неверное значение даты',
				'code' => ValidationErrorCodes::WRONG_VALUE_FORMAT
			),
		)
	);

//	$validation_rules->add(
//		'license_validity_date',
//		array(
//			'date' => array(
//				'value' => TRUE,
//				'message' => 'Поле содержит неверное значение даты',
//				'code' => ValidationErrorCodes::WRONG_VALUE_FORMAT
//			),
//		)
//	);

	$validation_rules->add(
		'clinic_address',
		array(
			'required' => $rules['required']
		)
	);



    $validation_rules->add(
        'user_phone',
        array(
            'phone'  => array(
                'value'   => TRUE,
                'message' => 'В поле должен быть введён корректный телефон! Пример: +7-495-111-11-11',
                'code'    => ValidationErrorCodes::WRONG_FULL_NAME_LENGTH,
            ),
            'required'  => array(
                'value'   => TRUE,
                'message' => 'Поле обязательно для заполнения',
                'code'    => ValidationErrorCodes::IS_REQUIRED_FIELD,
            ),
        )
    );

	$validation_rules->add(
		'user_login',
		array(

			'unique' => array(
				'value' => 'user.login',
				'message' => 'Пользователь с таким логином уже есть в базе',
				'code' => ValidationErrorCodes::WRONG_DATA
			),
			'required'  => array(
				'value'   => TRUE,
				'message' => 'Поле обязательно для заполнения',
				'code'    => ValidationErrorCodes::IS_REQUIRED_FIELD,
			),
			'min_length' => array(
				'value' => 3,
				'message' => 'Поле не может содержать меньше 3 символов',
				'code' => ValidationErrorCodes::WRONG_DATA
			)
		)
	);

	$validation_rules->add(
		'user_role',
		array(
			'required'  => array(
				'value'   => TRUE,
				'message' => 'Поле обязательно для заполнения',
				'code'    => ValidationErrorCodes::IS_REQUIRED_FIELD,
			),
		)
	);

    $validation_rules->add(
        'coordinates',
        array(
            'required'  => array(
                'value'   => TRUE,
                'message' => 'Поле обязательно для заполнения',
                'code'    => ValidationErrorCodes::IS_REQUIRED_FIELD,
            ),
            'regex' => array(
                'value' => '^[0-9]{1,}\.[0-9]{1,}$',
                'message' => 'Значение в поле не соответствует формату',
                'code' => ValidationErrorCodes::WRONG_VALUE_FORMAT
            ),
        )
    );

    $validation_rules->add(
		'certificate_date',
		array(
			'regex' => array(
				'value' => '^[0-9a-zA-Zа-яА-Я]{2}\-[0-9a-zA-Zа-яА-Я]{2}\-[0-9a-zA-Zа-яА-Я]{4}$',
				'message' => 'Значение в поле не соответствует формату',
				'code' => ValidationErrorCodes::WRONG_VALUE_FORMAT
			),
		)
	);

    $validation_rules->add(
        'appeal_first_name',
        array(
            'required' => array(
                'value' => true,
                'message' => 'Поле имя обязательно для заполнения',
                'code' => ValidationErrorCodes::WRONG_VALUE_FORMAT
            ),
        )
    );

    $validation_rules->add(
        'appeal_title',
        array(
            'max_length' => array(
                'value' => 255,
                'message' => 'Поле "Заголовок" не может быть более 255 символов',
                'code' => ValidationErrorCodes::WRONG_VALUE_FORMAT
            ),
        )
    );


    $validation_rules->add(
        'appeal_phone_number',
        array(
            'required' => array(
                'value' => true,
                'message' => 'Поле "Номер телефона" обязательно для заполнения',
                'code' => ValidationErrorCodes::WRONG_DATA
            ),
            'phone' => array(
                'value' => true,
                'message' => 'Поле "Номер телефона" должно содержать корректный номер телефона',
                'code' => ValidationErrorCodes::WRONG_VALUE_FORMAT
            ),
        )
    );

    $validation_rules->add(
        'appeal_specialty_id',
        array(
            'required' => array(
                'value' => true,
                'message' => 'Поле "Специализация" обязательно для заполнения',
                'code' => ValidationErrorCodes::WRONG_VALUE_FORMAT
            ),
            'specialty' => array(
                'value' => true,
                'message' => 'Указанная вами специальность отсутствует',
                'code' => ValidationErrorCodes::WRONG_VALUE_FORMAT
            )
        )
    );

    $validation_rules->add(
        'call_to_user_phone_number',
        array(
            'required' => array(
                'value' => true,
                'message' => 'Поле обязательно для заполнения',
                'code' => ValidationErrorCodes::WRONG_DATA
            ),
            'phone' => array(
                'value' => true,
                'message' => 'Поле должно содержать корректный номер',
                'code' => ValidationErrorCodes::WRONG_VALUE_FORMAT
            ),
        )
    );

    /*$validation_rules->add(
        'two_coordinates',
        array(
            'double_field' => array(
                'value'       => 'clinic.latitude.longitude',
                'message'     => 'Клиника с данными координатами уже существует',
                'code'        => ValidationErrorCodes::ALREADY_REGISTERED,
            ),
        )
    );*/

    $validation_rules->add(
        'clinic_address_required_and_unique',
        array(
            'unique' => array(
                'value'       => 'clinic.address',
                'message'     => 'Клиника с данным адресом уже существует',
                'code'        => ValidationErrorCodes::ALREADY_REGISTERED,
            ),
            'required' => array(
                'value'   => TRUE,
                'message' => 'Поле обязательно для заполнения',
                'code'    => ValidationErrorCodes::IS_REQUIRED_FIELD
            ),
        )
    );

    $validation_rules->add(
        'existing_doctor_by_fio',
        array(
            'triple_field' => array(
                'value'       => 'doctor.first_name.second_name.last_name',
                'message'     => 'Врач с введенными ФИО уже существует',
                'code'        => ValidationErrorCodes::ALREADY_REGISTERED,
            ),
        )
    );

	$validation_rules->add(
		'shipping_address',
		array(
			 'required'  => array(
				 'value'   => TRUE,
				 'message' => 'Адрес доставки должен быть указан обязательно!',
				 'code'    => ValidationErrorCodes::SHIPPING_ADDRESS_REQUIRED,
			 )
		)
	);

    $validation_rules->add(
        'widget_name',
        array(
            'required'  => array(
                'value'   => TRUE,
                'message' => 'Поле "Название" обязательно для заполнения',
                'code'    => ValidationErrorCodes::IS_REQUIRED_FIELD
            ),
            'unique' => array(
                'value'       => 'widget.name',
                'js_callback' => '/ajax/checkUniqueWidgetName',
                'message'     => 'Запись с данным названием уже есть в базе',
                'code'        => ValidationErrorCodes::ALREADY_REGISTERED,
            ),
        )
    );

    $validation_rules->add(
        'widget_folder',
        array(
            'required'  => array(
                'value'   => TRUE,
                'message' => 'Поле "Каталог" обязательно для заполнения',
                'code'    => ValidationErrorCodes::IS_REQUIRED_FIELD
            ),
            'unique' => array(
                'value'       => 'widget.folder',
                'js_callback' => '/ajax/checkUniqueWidgetFolder',
                'message'     => 'Запись с данным каталогом уже есть в базе',
                'code'        => ValidationErrorCodes::ALREADY_REGISTERED,
            ),
        )
    );

    $validation_rules->add(
        'widget_element',
        array(
            'required'  => array(
                'value'   => TRUE,
                'message' => 'Поле "Идентификатор элемента" обязательно для заполнения',
                'code'    => ValidationErrorCodes::IS_REQUIRED_FIELD
            ),
        )
    );

    $validation_rules->add(
        'widget_specialty',
        array(
            'required'  => array(
                'value'   => TRUE,
                'message' => 'Поле "Специализация" обязательно для заполнения',
                'code'    => ValidationErrorCodes::IS_REQUIRED_FIELD
            ),
        )
    );

    $validation_rules->add(
        'docdoc_service_category_mapping_service_service_id',
        array(
            'required'  => array(
                'value'   => TRUE,
                'message' => 'Поле "Услуга-двойник" обязательно для заполнения',
                'code'    => ValidationErrorCodes::IS_REQUIRED_FIELD
            ),
        )
    );

    $validation_rules->add(
        'docdoc_service_category_mapping_service_mapped_service_id',
        array(
            'required'  => array(
                'value'   => TRUE,
                'message' => 'Поле "Услуга, отображаемая на сайте" обязательно для заполнения',
                'code'    => ValidationErrorCodes::IS_REQUIRED_FIELD
            ),
        )
    );

$validation_rules->add(
    'name',
    array(
        'required'  => array(
            'value'   => TRUE,
            'message' => 'Поле "Название" обязательно для заполнения',
            'code'    => ValidationErrorCodes::IS_REQUIRED_FIELD
        ),
    )
);
$validation_rules->add(
    'genitive_name',
    array(
        'required'  => array(
            'value'   => TRUE,
            'message' => 'Поле "Название в родительном падеже" обязательно для заполнения',
            'code'    => ValidationErrorCodes::IS_REQUIRED_FIELD
        ),
    )
);

$validation_rules->add(
    'prepositional_name',
    array(
        'required'  => array(
            'value'   => TRUE,
            'message' => 'Поле "Название в предложном падеже" обязательно для заполнения',
            'code'    => ValidationErrorCodes::IS_REQUIRED_FIELD
        ),
    )
);

$validation_rules->add(
    'specialty_id',
    array(
        'required'  => array(
            'value'   => TRUE,
            'message' => 'Поле "Специальность" обязательно для заполнения',
            'code'    => ValidationErrorCodes::IS_REQUIRED_FIELD
        ),
    )
);

$validation_rules->add(
    'disease_draft_id',
    array(
        'required'  => array(
            'value'   => TRUE,
            'message' => 'Поле "Черновик статьи о заболевании" обязательно для заполнения',
            'code'    => ValidationErrorCodes::IS_REQUIRED_FIELD
        ),
    )
);
$validation_rules->add('city_id', [
    'required'  => [
        'value'   => true,
        'message' => 'Поле "Город" обязательно для заполнения',
        'code'    => ValidationErrorCodes::IS_REQUIRED_FIELD,
    ],
]);

Register::add('validation_rules', $validation_rules);

