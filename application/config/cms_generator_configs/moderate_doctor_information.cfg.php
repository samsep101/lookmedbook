<?php
$moderate_doctor_information = array(
    'table'         =>  DB_PREFIX.'moderate_doctor_information',
    'title'         =>  'Модерируемая информация: информация о враче',
    'fields'        =>  array(
        'id' => 'index',
        'doctor_id' => array(
            'type' => 'ajax_input',
            'cross_name' => 'full_name',
            'cross_table' => DB_PREFIX . 'doctor',
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
        'revision_number' => 'input',
        'first_name' => 'input',
        'second_name' => 'input',
        'last_name' => 'input',
        'sex_id'               => array(
            'type'   => 'listvalue',
            'values' => array(
                '1' => 'Мужской',
                '2' => 'Женский',
            )
        ),
        'is_leave_the_house' => array('type' => 'checkbox', 'label' => 'Да/Нет'),
        'is_adult' => array('type' => 'checkbox', 'label' => 'Да/Нет'),
        'is_children' => array('type' => 'checkbox', 'label' => 'Да/Нет'),
        'is_pregnant' => array('type' => 'checkbox', 'label' => 'Да/Нет'),
        'about_internal' => 'input',
        'about' => 'input',
    ),
    'generator' => array(
        'fields' => array(
            'id' => 'ID',
            'doctor_id' => 'Врач',
            'moderate_status_id' => 'Статус модерации',
            'revision_number' => 'Ревизия',
            'first_name' => 'Имя',
            'second_name' => 'Отчество',
            'last_name' => 'Фамилия',
            'sex_id' => 'Пол',
            'is_leave_the_house' => 'Выезд на дом',
            'is_adult' => 'Для взрослых',
            'is_children' => 'Для детей',
            'is_pregnant' => 'Для беременных',
            'about_internal' => 'О докторе (не изменяется при импорте)',
            'about' => 'О докторе',
        ),
        'list' => array(
            'fields' => array(
            'doctor_id',
            'first_name',
            'second_name',
            'last_name',
            ),
            'title'	 => 'Список',
            'sort_by' => array(
                array(
                    'field' => 'last_name',
                    'desc'  => 'ASC'
                ),
            )
        ),
        'edit'	=> array(
            'fields' => array(
                'Данные' => array(
                    'doctor_id',
                    'moderate_status_id',
                    'revision_number',
                    'first_name',
                    'second_name',
                    'last_name',
                    'sex_id',
                    'is_leave_the_house',
                    'is_adult',
                    'is_children',
                    'is_pregnant',
                    'about_internal',
                    'about',
                ),
            ),
            'title'	=> 'Редактирование',
            'submit'=> 'Сохранить',
        ),
        'add'	=> array(
            'fields' => array(
                'Данные' => array(
                    'doctor_id',
                    'moderate_status_id',
                    'revision_number',
                    'first_name',
                    'second_name',
                    'last_name',
                    'sex_id',
                    'is_leave_the_house',
                    'is_adult',
                    'is_children',
                    'is_pregnant',
                    'about_internal',
                    'about',
                ),
             ),
            'title'	=> 'Создать',
            'submit'=> 'Создать',
        ),
    ),
);
CmsGeneratorConfigRegister::add('moderate_doctor_information', $moderate_doctor_information);
