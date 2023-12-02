<?php
$clinic_to_user = array(
    'table'         =>  DB_PREFIX.'clinic_to_user',
    'title'         =>  'Отношения пользователей и клиник',
    'fields'        =>  array(
        'id' => 'index',
        'user_id' => array(
                'type'			=> 'category',
                'cross_name'	=> 'login',
                'cross_index'	=> 'id',
                'cross_table'	=> DB_PREFIX.'user',
                'first'			=> array( '0'	=>	'',),
                'filter' => 'true',
                'sort_by'     => 'login'
        ),
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
            'user_id' => 'Пользователь',
            'clinic_id' => 'Клиника',
        ),
        'list' => array(
            'fields' => array(
            'user_id',
            'clinic_id',
            ),
            'title'	 => 'Список',
            'sort_by' => array(
                array(
                    'field' => 'user_id',
                    'desc'  => 'ASC'
                ),
            )
        ),
        'edit'	=> array(
            'fields' => array(
                'Данные' => array(
                    'user_id',
                    'clinic_id',
                ),
            ),
            'title'	=> 'Редактирование',
            'submit'=> 'Сохранить',
        ),
        'add'	=> array(
            'fields' => array(
                'Данные' => array(
                    'user_id',
                    'clinic_id',
                ),
             ),
            'title'	=> 'Создать',
            'submit'=> 'Создать',
        ),
    ),
);
CmsGeneratorConfigRegister::add('clinic_to_user', $clinic_to_user);
