<?php
$moderate_clinic_phone = array(
    'table'         =>  DB_PREFIX.'moderate_clinic_phone',
    'title'         =>  'Модерируемая информация: телефоны клиники',
    'fields'        =>  array(
        'id' => 'index',
        'clinic_id' => array(
                'type'			=> 'category',
                'cross_name'	=> 'name',
                'cross_index'	=> 'id',
                'cross_table'	=> DB_PREFIX.'clinic',
                'first'			=> array( '0'	=>	'',),
                'filter' => 'true',
                'sort_by'     => 'name'
        ),
        'phone_number' => 'input',
        'revision_number' => 'input',
        'is_use_to_distribution' => array('type' => 'checkbox', 'label' => 'Да/Нет'),
    ),
    'generator' => array(
        'fields' => array(
            'id' => 'ID',
            'clinic_id' => 'Клиника',
            'phone_number' => 'Номер',
            'revision_number' => 'Ревизия',
            'is_use_to_distribution' => 'Для уведомлений'
        ),
        'list' => array(
            'fields' => array(
            'clinic_id',
            'phone_number',
            ),
            'title'	 => 'Список',
            'sort_by' => array(
                array(
                    'field' => 'phone_number',
                    'desc'  => 'ASC'
                ),
            )
        ),
        'edit'	=> array(
            'fields' => array(
                'Данные' => array(
                    'clinic_id',
                    'phone_number',
                    'revision_number',
                    'is_use_to_distribution',
                ),
            ),
            'title'	=> 'Редактирование',
            'submit'=> 'Сохранить',
        ),
        'add'	=> array(
            'fields' => array(
                'Данные' => array(
                    'clinic_id',
                    'phone_number',
                    'revision_number',
                    'is_use_to_distribution',
                ),
             ),
            'title'	=> 'Создать',
            'submit'=> 'Создать',
        ),
    ),
);
CmsGeneratorConfigRegister::add('moderate_clinic_phone', $moderate_clinic_phone);
