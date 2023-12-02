<?php
$moderate_clinic_email = array(
    'table'         =>  DB_PREFIX.'moderate_clinic_email',
    'title'         =>  'Модерируемая информация: почта клиники',
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
        'email' => 'input',
        'is_use_to_distribution' => array('type' => 'checkbox', 'label' => 'Да/Нет'),
        'revision_number' => 'input',
    ),
    'generator' => array(
        'fields' => array(
            'id' => 'ID',
            'clinic_id' => 'Клиника',
            'email' => 'Email',
            'is_use_to_distribution' => 'Для рассылок',
            'revision_number' => 'Ревизия',
        ),
        'list' => array(
            'fields' => array(
            'clinic_id',
            'email',
            ),
            'title'	 => 'Список',
            'sort_by' => array(
                array(
                    'field' => 'email',
                    'desc'  => 'ASC'
                ),
            )
        ),
        'edit'	=> array(
            'fields' => array(
                'Данные' => array(
                    'clinic_id',
                    'email',
                    'is_use_to_distribution',
                    'revision_number',
                ),
            ),
            'title'	=> 'Редактирование',
            'submit'=> 'Сохранить',
        ),
        'add'	=> array(
            'fields' => array(
                'Данные' => array(
                    'clinic_id',
                    'email',
                    'is_use_to_distribution',
                    'revision_number',
                ),
             ),
            'title'	=> 'Создать',
            'submit'=> 'Создать',
        ),
    ),
);
CmsGeneratorConfigRegister::add('moderate_clinic_email', $moderate_clinic_email);
