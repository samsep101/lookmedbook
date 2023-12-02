<?php
$moderate_clinic_description = array(
    'table'         =>  DB_PREFIX.'moderate_clinic_description',
    'title'         =>  'Модерируемая информация: описание клиники',
    'fields'        =>  array(
        'id' => 'index',
        'about' => array(
            'type'  =>  'text',
            'style' => 'min-width: 800px; min-height:200px'
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
        'clinic_id' => array(
                'type'			=> 'category',
                'cross_name'	=> 'name',
                'cross_index'	=> 'id',
                'cross_table'	=> DB_PREFIX.'clinic',
                'first'			=> array( '0'	=>	'',),
                'filter' => 'true',
                'sort_by'     => 'name'
        ),
    ),
    'generator' => array(
        'fields' => array(
            'id' => 'ID',
            'about' => 'Описание',
            'moderate_status_id' => 'Статус модерации',
            'revision_number' => 'Ревизия',
            'clinic_id' => 'Клиника',
        ),
        'list' => array(
            'fields' => array(
            'clinic_id',
            'about'
            ),
            'title'	 => 'Список',
            'sort_by' => array(
                array(
                    'field' => 'clinic_id',
                    'desc'  => 'ASC'
                ),
            )
        ),
        'edit'	=> array(
            'fields' => array(
                'Данные' => array(
                    'about',
                    'moderate_status_id',
                    'revision_number',
                    'clinic_id',
                ),
            ),
            'title'	=> 'Редактирование',
            'submit'=> 'Сохранить',
        ),
        'add'	=> array(
            'fields' => array(
                'Данные' => array(
                    'about',
                    'moderate_status_id',
                    'revision_number',
                    'clinic_id',
                ),
             ),
            'title'	=> 'Создать',
            'submit'=> 'Создать',
        ),
    ),
);
CmsGeneratorConfigRegister::add('moderate_clinic_description', $moderate_clinic_description);
