<?php
$moderate_clinic_license_image = array(
    'table'         =>  DB_PREFIX.'moderate_clinic_license_image',
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
        'image_id'  => array(
            'type'          => 'image',
            'base_dir'      => 'clinic/license/',
            'upload_folder' => 'clinic/license/'
        ),
    ),
    'generator' => array(
        'fields' => array(
            'id' => 'ID',
            'clinic_id' => 'Клиника',
            'image_id' => 'Изображение',
        ),
        'list' => array(
            'fields' => array(
            'clinic_id',
            'image_id',
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
                    'image_id',
                ),
            ),
            'title'	=> 'Редактирование',
            'submit'=> 'Сохранить',
        ),
        'add'	=> array(
            'fields' => array(
                'Данные' => array(
                    'clinic_id',
                    'image_id',
                ),
             ),
            'title'	=> 'Создать',
            'submit'=> 'Создать',
        ),
    ),
);
CmsGeneratorConfigRegister::add('moderate_clinic_license_image', $moderate_clinic_license_image);
