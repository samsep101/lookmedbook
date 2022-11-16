<?php
$moderate_clinic_pricelist = array(
    'table'         =>  DB_PREFIX.'moderate_clinic_pricelist',
    'title'         =>  'Модерируемая информация: прайслисты',
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
        'filename'  => array(
            'type'			=> 'link',
            'folder'	=> 'clinic/services/',
        ),
    ),
    'generator' => array(
        'fields' => array(
            'id' => 'ID',
            'clinic_id' => 'Клиника',
            'filename' => 'Путь к файлу',
        ),
        'list' => array(
            'fields' => array(
            'clinic_id',
            'filename',
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
                    'clinic_id',
                    'filename',
                ),
            ),
            'title'	=> 'Редактирование',
            'submit'=> 'Сохранить',
        ),
        'add'	=> array(
            'fields' => array(
                'Данные' => array(
                    'clinic_id',
                    'filename',
                ),
             ),
            'title'	=> 'Создать',
            'submit'=> 'Создать',
        ),
    ),
);
CmsGeneratorConfigRegister::add('moderate_clinic_pricelist', $moderate_clinic_pricelist);
