<?php
$clinic_email = array(
    'table'         =>  DB_PREFIX.'clinic_email',
    'title'         =>  'Почта клиники',
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
    ),
    'generator' => array(
        'fields' => array(
            'id' => 'ID',
            'clinic_id' => 'Клиника',
            'email' => 'Email',
            'is_use_to_distribution' => 'Для уведомлений',
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
                ),
             ),
            'title'	=> 'Создать',
            'submit'=> 'Создать',
        ),
    ),
);
CmsGeneratorConfigRegister::add('clinic_email', $clinic_email);
