<?php
	$cms_doctor = array(
		'table'	 => DB_PREFIX . 'doctor',
		'title'	 => 'Доктора',
		'fields'	=> array(
			'id'				   => 'index',
			'first_name'		   => 'input',
			'second_name'		  => 'input',
			'last_name'			=> 'input',
			'full_name'			=> 'input',
			'image_id'			 => array(
				'type'		  => 'image',
				'base_dir'	  => 'doctor/',
				'upload_folder' => 'doctor/'
			),
			'card_image_id'		=> array(
				'type'		  => 'image',
				'base_dir'	  => 'doctor/',
				'upload_folder' => 'doctor/'
			),
			'rate'				 => 'input',
			'is_best'			  => array(
				'type'  => 'checkbox',
				'label' => 'Да/Нет',
			),
			'work_experience'	  => 'input',
			'about_internal'	=> 'htmlarea',
			'about'				=> 'htmlarea',
			'education'			=> 'htmlarea',
			'course'			   => 'htmlarea',
			'certificate'		  => 'htmlarea',
			'academic_title'	   => 'htmlarea',
			'sex_id'			   => array(
				'type'   => 'listvalue',
				'values' => array(
					'1' => 'Мужской',
					'2' => 'Женский',
				)
			),
			'is_active'			=> array(
				'type'  => 'checkbox',
				'label' => 'Да/Нет'
			),
			'not_work'			=> array(
				'type'  => 'checkbox',
				'label' => 'Да/Нет'
			),
			'doctor_type_id'	   => array(
				'type'		=> 'category',
				'cross_name'  => 'name',
				'cross_index' => 'id',
				'cross_table' => DB_PREFIX . 'doctor_type',
				'first'	   => array(
					'0' => '',
				),
				'sort_by'	 => 'name',
			),

			'is_has_morning_time'  => array(
				'type'  => 'checkbox',
				'label' => 'Да/Нет'
			),
			'is_has_weekend_time'  => array(
				'type'  => 'checkbox',
				'label' => 'Да/Нет'
			),
			'is_has_evening_time'  => array(
				'type'  => 'checkbox',
				'label' => 'Да/Нет'
			),
			'is_leave_the_house'   => array(
				'type'  => 'checkbox',
				'label' => 'Да/Нет'
			),
			'is_adult'			 => array(
				'type'  => 'checkbox',
				'label' => 'Да/Нет'
			),
			'is_children'		  => array(
				'type'  => 'checkbox',
				'label' => 'Да/Нет'
			),
			'is_pregnant'		  => array(
				'type'  => 'checkbox',
				'label' => 'Да/Нет'
			),
			'is_handicapped'	   => array(
				'type'  => 'checkbox',
				'label' => 'Да/Нет'
			),
			'alias'				=> 'input',
		),

		'extra'	 => array(
			'doctor_to_clinic'			=> array(
				'table' => 'doctor_to_clinic',
				'title' => 'Клиники',
				'field' => 'doctor_id'
			),
			'specialties'				 => array(
				'table' => 'doctor_specialty_to_clinic',
				'title' => 'Специальности',
				'field' => 'doctor_id'
			),
			'purposes'					=> array(
				'table' => 'purpose_of_visit_to_doctor',
				'title' => 'Цели визита',
				'field' => 'doctor_id'
			),
			'images'					  => array(
				'table' => 'image_to_doctor',
				'title' => 'Фотографии',
				'field' => 'doctor_id'
			),
			'my_doctor'				   => array(
				'table' => 'my_doctor',
				'title' => 'Пользователи, которые добавили в избранное',
				'field' => 'doctor_id'
			),
			'doctor_review'			   => array(
				'table' => 'doctor_review',
				'title' => 'Отзывы о враче',
				'field' => 'doctor_id'
			),
			'moderate_doctor_information' => array(
				'table' => 'moderate_doctor_information',
				'title' => 'Модерируемая информация: информация о враче',
				'field' => 'doctor_id'
			),
		),

		'generator' => array(
			'fields' => array(
				'id'				   => 'id',
				'full_name'			=> 'ФИО',
				'first_name'		   => 'Имя',
				'second_name'		  => 'Отчество',
				'last_name'			=> 'Фамилия',
				'sex_id'			   => 'Пол',
				'doctor_type_id'	   => 'Врач для',
				'image_id'			 => 'Изображение',
				'card_image_id'		=> 'Изображение для карточек',
				'alias'				=> 'Алиас',
				'about_internal'	=> 'О докторе (не изменяется при импорте)',
				'about'				=> 'О докторе',
				'education'			=> 'Образование',
				'course'			   => 'Курсы повышения квалификации',
				'certificate'		  => 'Сертификаты',
				'academic_title'	   => 'Ученые степени',
				'rate'				 => 'Рейтинг доктора',
				'work_experience'	  => 'Стаж работы',
				'not_work'			 => 'Не работаем с врачом',
				'is_active'			=> 'Активен',
				'is_has_morning_time'  => 'Принимает утром',
				'is_has_evening_time'  => 'Принимает вечером',
				'is_has_weekend_time'  => 'Принимает в выходные дни',
				'is_leave_the_house'   => 'Выезд на дом',
				'is_adult'			 => 'Врач для взрослых',
				'is_children'		  => 'Врач для детей',
				'is_pregnant'		  => 'Врач для беременных',
				'is_handicapped'	   => 'Для инвалидов',
				'is_best'			  => 'Пометка "Лучший"',
			),
			'list'   => array(
				'fields'  => array(
					'full_name',
					'alias',
					'rate',
					'work_experience',
					'is_active',
					'not_work',
					'is_has_morning_time',
					'is_has_evening_time',
					'is_has_weekend_time',
					'is_leave_the_house',
					'is_adult',
					'is_children',
					'is_pregnant',
					'is_handicapped',
				),
				'title'   => 'Список докторов',
				'sort_by' => array(
					array(
						'field' => 'last_name',
						'desc'  => 'ASC'
					),
				),
				'filters' => array(
					'use_class_params' => 'AdminDoctorSearchParams',
					'filters'		  => array(
						'ФИО врача' => array(
							'doctor_name' => array(
								'type'  => 'input',
								'title' => ''
							),
						),
					)
				),

			),
			'edit'   => array(
				'fields' => array(
					'Данные' => array(
						'first_name',
						'second_name',
						'last_name',
						'alias',
						'sex_id',
						'image_id',
						'card_image_id',
						'rate',
						'is_best',
						'work_experience',
						//'doctor_type_id',
						'about_internal',
						'about',
						'education',
						'course',
						'certificate',
						'academic_title',
						'is_active',
						'not_work',
						'is_has_morning_time',
						'is_has_evening_time',
						'is_has_weekend_time',
						'is_leave_the_house',
						'is_adult',
						'is_children',
						'is_pregnant',
						'is_handicapped',
					),
				),
				'title'  => 'Редактирование',
				'submit' => 'Сохранить',
			),
			'add'	=> array(
				'fields' => array(
					'Данные' => array(
						'first_name',
						'second_name',
						'last_name',
						'alias',
						'sex_id',
						'image_id',
						'card_image_id',
						'rate',
						'is_best',
						'work_experience',
						//'doctor_type_id',
						'about_internal',
						'about',
						'education',
						'course',
						'certificate',
						'academic_title',
						'is_active',
						'not_work',
						//'is_has_morning_time',
						//'is_has_evening_time',
						//'is_has_weekend_time',
						'is_leave_the_house',
						'is_adult',
						'is_children',
						'is_pregnant',
						'is_handicapped',
					),
				),
				'title'  => 'Добавить',
				'submit' => 'Добавить',
			),
		),
	);

	CmsGeneratorConfigRegister::add('doctor', $cms_doctor);
