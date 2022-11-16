<?php
	$httpHost = $_SERVER['HTTP_HOST'];

	$clinic = array(
		'table'	 => DB_PREFIX . 'clinic',
		'title'	 => 'Города',
		'fields'	=> array(
			'id'					  => 'index',
			'name'					  => 'input',
			'seo_name'				  => 'input',
			'direct_phone'					=> 'input',
			'docdoc_id'					=> 'input',
			'alias'				   => 'input',
			'about'				   => 'htmlarea',
			'address'				 => 'input',
			'postcode'				=> 'input',
			'house'				   => 'input',
			'case'					=> 'input',
			'room'					=> 'input',
			'top_phone'			   => 'input',
            'top_phone_text'	   => 'htmlarea',
			'street_id'			   => array(
				'type'		=> 'category',
				'cross_name'  => 'full_name',
				'cross_index' => 'id',
				'cross_table' => DB_PREFIX . 'street',
				'first'	   => array(
					'0' => '',
				),
				'filter'	  => 'true',
				'sort_by'	 => 'name'
			),
			'image_id'				=> array(
				'type'		  => 'image',
				'base_dir'	  => 'clinic/',
				'upload_folder' => 'clinic/'
			),
			'card_image_id'		   => array(
				'type'		  => 'image',
				'base_dir'	  => 'clinic/',
				'upload_folder' => 'clinic/'
			),
			'latitude'				=> 'input',
			'longitude'			   => 'input',
			'rate'					=> 'input', 
			'is_best'			  => array(
				'type'  => 'checkbox',
				'label' => 'Да/Нет',
			),
			'city_id'				 => array(
				'type'		=> 'category',
				'cross_name'  => 'name',
				'cross_index' => 'id',
				'cross_table' => DB_PREFIX . 'city',
				'first'	   => array(
					'0' => '',
				),
				'filter'	  => 'true',
				'sort_by'	 => 'name'
			),
            'primary_clinic_id'				 => array(
                'type'		=> 'category',
                'cross_name'  => 'name',
                'cross_index' => 'id',
                'cross_table' => DB_PREFIX . 'clinic',
                'first'	   => array(
                    '0' => '',
                ),
                'filter'	  => 'true',
                'sort_by'	 => 'name'
            ),
			'availability'			=> 'input',
			'is_children'			 => array(
				'type'  => 'checkbox',
				'label' => 'Да/Нет'
			),
			'is_adult'				=> array(
				'type'  => 'checkbox',
				'label' => 'Да/Нет'
			),
			'is_handicapped'		  => array(
				'type'  => 'checkbox',
				'label' => 'Да/Нет'
			),
			'is_pregnant'			 => array(
				'type'  => 'checkbox',
				'label' => 'Да/Нет'
			),
			'is_day_and_night'		=> array(
				'type'  => 'checkbox',
				'label' => 'Да/Нет'
			),
			'start_time_monday'	   => 'time',
			'end_time_monday'		 => 'time',
			'start_time_tuesday'	  => 'time',
			'end_time_tuesday'		=> 'time',
			'start_time_wednesday'	=> 'time',
			'end_time_wednesday'	  => 'time',
			'start_time_thursday'	 => 'time',
			'end_time_thursday'	   => 'time',
			'start_time_friday'	   => 'time',
			'end_time_friday'		 => 'time',
			'start_time_saturday'	 => 'time',
			'end_time_saturday'	   => 'time',
			'start_time_sunday'	   => 'time',
			'end_time_sunday'		 => 'time',
			'name_of_bank'			=> 'input',
			'bank_bik'				=> 'input',
			'bank_inn'				=> 'input',
			'bank_kpp'				=> 'input',
			'correspondent_account'   => 'input',
			'current_account'		 => 'input',
			'ogrn'					=> 'input',
			'legal_address'		   => 'input',
			'fact_address'			=> 'input',
			'only_children'		   => array(
				'type'  => 'checkbox',
				'label' => 'Да/Нет'
			),
			'only_adult'			  => array(
				'type'  => 'checkbox',
				'label' => 'Да/Нет'
			),
			'is_active'			   => array(
				'type'  => 'checkbox',
				'label' => 'Да/Нет'
			),
            'visit_disallow'			   => array(
                'type'  => 'checkbox',
                'label' => 'Да/Нет'
            ),
			'not_work'				=> array(
				'type'  => 'checkbox',
				'label' => 'Да/Нет'
			),
			'is_state'				=> array(
				'type'  => 'checkbox',
				'label' => 'Да/Нет'
			),
			'is_prescribe_sick_leave' => array(
				'type'  => 'checkbox',
				'label' => 'Да/Нет'
			),
			'is_yandex_send'		  => array(
				'type'  => 'checkbox',
				'label' => 'Да/Нет'
			),
			'is_import_enabled' => array(
				'type'  => 'checkbox',
				'label' => 'Да/Нет'
			),
			'is_import_of_metro_stations_enabled' => array(
				'type'  => 'checkbox',
				'label' => 'Да/Нет'
			),
			'email'				   => 'input',
			'site'					=> 'input',
			'director_fio'			=> 'input',
			'contract_number'		 => 'input',
			'date_contract'		   => array(
				'type'   => 'date',
				'format' => 'd-m-Y'
			),
			'legal_entity'			=> 'input',
			'representative_user_id'  => array(
				'type'		=> 'category',
				'cross_name'  => 'name',
				'cross_index' => 'id',
				'cross_table' => DB_PREFIX . 'user',
				'first'	   => array(
					'0' => '',
				),
				'filter'	  => 'true',
				'sort_by'	 => 'fio'
			),
		),
		'extra'	 => array(
			'doctor_to_clinic'		 => array(
				'table' => 'doctor_to_clinic',
				'title' => 'Врачи',
				'field' => 'clinic_id'
			),
			'specialization_to_clinic' => array(
				'table' => 'specialization_to_clinic',
				'title' => 'Специализации',
				'field' => 'clinic_id'
			),
			'specialties'			  => array(
				'table' => 'specialty_to_clinic',
				'title' => 'Специальности',
				'field' => 'clinic_id'
			),
            'service_to_clinic'		 => array(
                'table' => 'service_to_clinic',
                'title' => 'Услуги',
                'field' => 'clinic_id'
            ),
			'feature_to_clinic'		=> array(
				'table' => 'feature_to_clinic',
				'title' => 'Сервис',
				'field' => 'clinic_id'
			),
			'images'				   => array(
				'table' => 'image_to_clinic',
				'title' => 'Фотографии',
				'field' => 'clinic_id'
			),
			'clinic_license'		   => array(
				'table' => 'clinic_license',
				'title' => 'Лицензии клиники',
				'field' => 'clinic_id'
			),
			'clinic_review'			=> array(
				'table' => 'clinic_review',
				'title' => 'Отзывы о клиниках',
				'field' => 'clinic_id'
			),
			'my_clinic'				=> array(
				'table' => 'my_clinic',
				'title' => 'Пользователи, которые добавили в избранное',
				'field' => 'clinic_id'
			),
			'phones'				   => array(
				'table' => 'clinic_phone',
				'title' => 'Телефоные номера',
				'field' => 'clinic_id'
			),
			'email'					=> array(
				'table' => 'clinic_email',
				'title' => 'Почта клиники',
				'field' => 'clinic_id'
			),
			'metro_station_to_clinic'	=> array(
				'table' => 'metro_station_to_clinic',
				'title' => 'Станции метро',
				'field' => 'clinic_id',
			),
		),

		'generator' => array(
			'fields' => array(
				'id'					  => 'ID',
				'name'					  => 'Название',
				'seo_name'				  => 'SEO-название',
				'direct_phone'					=> 'Прямой телефон',
				'docdoc_id'					=> 'ID DocDoc',
                'primary_clinic_id' => 'Основная клиника',
				'alias'				   => 'Алиас',
				'original_alias'    => 'Оригинальный алиас',
				'about'				   => 'Описание',
				'address'				 => 'Адрес',
				'postcode'				=> 'Почтовый индекс',
				'street_id'			   => 'ул./пер.',
				'house'				   => 'д.',
				'case'					=> 'к.',
				'room'					=> 'кв.',
				'top_phone'			   => 'Телефон отображаемый в верхней части страницы (Звони, мы поможем)',
				'top_phone_text'	   => 'Текст, отображаемый над телефоном в верхней части страницы (вместо "Звони, мы поможем")',
				'image_id'				=> 'Изображение',
				'card_image_id'		   => 'Изображение для карточек',
				'latitude'				=> 'Широта',
				'longitude'			   => 'Долгота',
				'rate'					=> 'Рейтинг',
				'city_id'				 => 'Город',
				'availability'			=> 'Доступность',
				'only_children'		   => 'Только для детей',
				'only_adult'			  => 'Только для взрослых',
				'is_children'			 => 'Для детей',
				'is_adult'				=> 'Для взрослых',
				'is_handicapped'		  => 'Для инвалидов',
				'is_pregnant'			 => 'Для беременных',
				'is_day_and_night'		=> 'Круглосуточная',
				'start_time_monday'	   => 'Пн с',
				'end_time_monday'		 => 'Пн до',
				'start_time_tuesday'	  => 'Вт с',
				'end_time_tuesday'		=> 'Вт до',
				'start_time_wednesday'	=> 'Ср с',
				'end_time_wednesday'	  => 'Ср до',
				'start_time_thursday'	 => 'Чт с',
				'end_time_thursday'	   => 'Чт до',
				'start_time_friday'	   => 'Пт с',
				'end_time_friday'		 => 'Пт до',
				'start_time_saturday'	 => 'Сб с',
				'end_time_saturday'	   => 'Сб до',
				'start_time_sunday'	   => 'Вс с',
				'end_time_sunday'		 => 'Вс до',
				'name_of_bank'			=> 'Наименование банка',
				'bank_bik'				=> 'БИК банка',
				'bank_inn'				=> 'ИНН',
				'bank_kpp'				=> 'КПП',
				'correspondent_account'   => 'Расчетный счет',
				'current_account'		 => 'Корреспондентский счет',
				'ogrn'					=> 'ОГРН',
				'legal_address'		   => 'Юридический адрес',
				'fact_address'			=> 'Фактический адрес',
				'is_active'			   => 'Выводить на сайте',
				'visit_disallow'			   => 'Запись запрещена',
				'not_work'				=> 'Не работаем с клиникой',
				'redirect_list'		   => 'Добавить клинику в список отображаемх страниц при отсутствии страницы',
				'is_state'				=> 'Государственная клиника',
				'is_prescribe_sick_leave' => 'Выписывает больничные листы',
				'is_yandex_send'		  => 'Публиковать на Яндексе',
				'is_import_enabled' => 'Обновлять данные с DocDoc',
				'is_import_of_metro_stations_enabled' => 'Обновлять станции метро при импорте',
				'email'				   => 'Email',
				'site'					=> 'Сайт',
				'director_fio'			=> 'ФИО директора',
				'contract_number'		 => 'Номер договора',
				'date_contract'		   => 'Дата контракта',
				'legal_entity'			=> 'Юр. лицо клиники',
				'representative_user_id'  => 'Пользователь, добавивший клинику',
				'is_best'			  => 'Пометка "Лучший"',
			),
			'list'   => array(
				'fields'  => array(
					'name',
					'alias',
					'address',
					'availability'
				),
				'title'   => 'Список клиник',
				'sort_by' => array(
					array(
						'field' => 'name',
						'desc'  => 'ASC'
					),
				),
                'filters' => array(
                    'use_class_params' => 'ClinicSearchCriteria',
                    'filters' => array(
                        'Название' => array(
                            'name' => array(
                                'type' => 'input',
                                'title' => ''
                            ),
                        ),
                        'Алиас' => array(
                            'alias' => array(
                                'type' => 'input',
                                'title' => ''
                            ),
                        )
                    )
                ),
				'additionalHTML' => <<<HTML
					<script type="application/javascript">
						$(function() {
							$('#generateYandexFeed').bind('click', function() {
								var errorMessage = $('.generate-file-container .load-message'),
									button = $(this);
								button.attr('disabled', true);
								$.ajax({
									url: '/test/checkExistYandexFeedAllClinic',
									dataType: 'JSON',
									type: 'POST',
									success: function(response) {
										if(response.status == 'exist') {
											location.href = "//{$httpHost}/test/saveXMLFileAllClinic";
											errorMessage.hide();
											button.attr('disabled', false);
										} else if(response.status == 'notExist') {
											errorMessage.html('Файл отсутствует.').show();
										}
									},
									error: function() {
										errorMessage.html('При попытке обращения к файлу возникли ошибки. Попробуйте повторить операцию позже.').show();
										button.attr('disabled', false);
									}
								});
							});
						});

					</script>

					<div class="generate-file-container">
						<div class="button-area">
							<div class="load-img">
								<img src="/media/images/loader.gif">
							</div>
							<input type="button" id="generateYandexFeed" class="" value="Сгенерировать Яндекс Feed">
							<a href="/import/docdoc" target="_blank">Импортировать данные из DocDoc</a>
						</div>
						<div class="load-message"></div>
					</div>
HTML

			),
			'edit'   => array(
				'fields'  => array(
					'Информация'			=> array(
						'name',
						'seo_name',
						'direct_phone',
						'docdoc_id',
						'primary_clinic_id',
						'alias',
						'original_alias',
						'about',
						'address',
						'postcode',
						'image_id',
						'card_image_id',
						'latitude',
						'longitude',
						'rate',
						'is_best',
						'city_id',
						'only_children',
						'only_adult',
						'is_children',
						'is_adult',
						'is_handicapped',
						'is_pregnant',
						'is_day_and_night',
						'start_time_monday',
						'end_time_monday',
						'start_time_tuesday',
						'end_time_tuesday',
						'start_time_wednesday',
						'end_time_wednesday',
						'start_time_thursday',
						'end_time_thursday',
						'start_time_friday',
						'end_time_friday',
						'start_time_saturday',
						'end_time_saturday',
						'start_time_sunday',
						'end_time_sunday',
						'is_active',
						'visit_disallow',
						'not_work',
						'is_state',
						'is_prescribe_sick_leave',
						'is_yandex_send',
						'is_import_enabled',
						'is_import_of_metro_stations_enabled',
						'representative_user_id',
					),
					'Реквизиты'			 => array(
						'name_of_bank',
						'bank_bik',
						'bank_inn',
						'bank_kpp',
						'correspondent_account',
						'current_account',
						'ogrn',
						'legal_address',
						'fact_address'
					),
					'Контактная информация' => array(
						'email',
						'site',
						'director_fio',
						'contract_number',
						'date_contract',
						'legal_entity',
					),
                    'Номер телефона наверху страницы' => [
                        'top_phone',
                        'top_phone_text',
                    ],
				),
				'tooltip' => '<p>Для того, чтобы <b>изменения вступили в силу</b>, необходимо выйти и войти в систему.</p>',
				'title'   => 'Редактирование',
				'submit'  => 'Сохранить',
			),
			'add'	=> array(
				'fields'  => array(
					'Информация'			=> array(
						'name',
						'seo_name',
						'direct_phone',
						'docdoc_id',
						'primary_clinic_id',
						'alias',
						'original_alias',
						'about',
						'address',
						'postcode',
						'image_id',
						'card_image_id',
						'latitude',
						'longitude',
						'rate',
						'is_best',
						'city_id',
						'only_children',
						'only_adult',
						'is_children',
						'is_adult',
						'is_handicapped',
						'is_pregnant',
						'is_day_and_night',
						'start_time_monday',
						'end_time_monday',
						'start_time_tuesday',
						'end_time_tuesday',
						'start_time_wednesday',
						'end_time_wednesday',
						'start_time_thursday',
						'end_time_thursday',
						'start_time_friday',
						'end_time_friday',
						'start_time_saturday',
						'end_time_saturday',
						'start_time_sunday',
						'end_time_sunday',
						'is_active',
						'visit_disallow',
						'not_work',
						'is_state',
						'is_prescribe_sick_leave',
						'is_yandex_send',
						'is_import_enabled',
						'is_import_of_metro_stations_enabled',
						'representative_user_id',
					),
					'Реквизиты'			 => array(
						'name_of_bank',
						'bank_bik',
						'bank_inn',
						'bank_kpp',
						'correspondent_account',
						'current_account',
						'ogrn',
						'legal_address',
						'fact_address'
					),
					'Контактная информация' => array(
						'email',
						'site',
						'director_fio',
						'contract_number',
						'date_contract',
						'legal_entity',
					),
                    'Номер телефона наверху страницы' => [
                        'top_phone',
                        'top_phone_text',
                    ],
				),
				'tooltip' => '<p>Для того, чтобы <b>изменения вступили в силу</b>, необходимо выйти и войти в систему.</p>',
				'title'   => 'Создать новую',
				'submit'  => 'Создать новую',
			),
		),
	);

	CmsGeneratorConfigRegister::add('clinic', $clinic);
