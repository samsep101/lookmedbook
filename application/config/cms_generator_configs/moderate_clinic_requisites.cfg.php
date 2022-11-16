<?php
$moderate_clinic_requisites = array(
    'table'         =>  DB_PREFIX.'moderate_clinic_requisites',
    'title'         =>  'Модерируемая информация: реквизиты клиники',
    'fields'        =>  array(
        'id' => 'index',
        'name_of_bank' => 'input',
        'bank_bik' => 'input',
        'bank_inn' => 'input',
        'bank_kpp' => 'input',
        'correspondent_account' => 'input',
        'current_account' => 'input',
        'ogrn' => 'input',
        'legal_address' => 'input',
        'fact_address' => 'input',
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
            'name_of_bank' => 'Наименование банка',
            'bank_bik' => 'БИК банка',
            'bank_inn' => 'ИНН',
            'bank_kpp' => 'КПП',
            'correspondent_account' => 'Корреспондентский счет',
            'current_account' => 'Расчетный счет',
            'ogrn' => 'ОГРН',
            'legal_address' => 'Юридический адрес',
            'fact_address' => 'Фактический адрес',
            'moderate_status_id' => 'Статус модерации',
            'revision_number' => 'Ревизия',
            'clinic_id' => 'Клиника',
        ),
        'list' => array(
            'fields' => array(
            'name_of_bank',
            'clinic_id',
            ),
            'title'	 => 'Список',
            'sort_by' => array(
                array(
                    'field' => 'name_of_bank',
                    'desc'  => 'ASC'
                ),
            )
        ),
        'edit'	=> array(
            'fields' => array(
                'Данные' => array(
                    'name_of_bank',
                    'bank_bik',
                    'bank_inn',
                    'bank_kpp',
                    'correspondent_account',
                    'current_account',
                    'ogrn',
                    'legal_address',
                    'fact_address',
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
                    'name_of_bank',
                    'bank_bik',
                    'bank_inn',
                    'bank_kpp',
                    'correspondent_account',
                    'current_account',
                    'ogrn',
                    'legal_address',
                    'fact_address',
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
CmsGeneratorConfigRegister::add('moderate_clinic_requisites', $moderate_clinic_requisites);
