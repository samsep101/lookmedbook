<?php
    $specialty_descr = array(
        'table'     => DB_PREFIX . 'specialty_descr',
        'title'     => 'Контент для специальностей',
        'fields'    => array(
            'id'           => 'index',
    		'specialty_id' => array(
                'type'        => 'category',
                'cross_name'  => 'name',
                'cross_index' => 'id',
                'cross_table' => DB_PREFIX . 'specialty',
                'first'       => array(
                    '0' => '',
                ),
                'filter'      => 'true',
                'sort_by'     => 'name',
            ),
			'specialty_page_descr' => 'htmlarea',
			'clinic_page_descr' => 'htmlarea',
        ),
        'generator' => array(
            'fields' => array(
                'id'           => 'ID',
        		'specialty_id' => 'Специальность',
        		'specialty_page_descr' => 'Контент на странице специальности',
        		'clinic_page_descr' => 'Контент на странице категории клиник',
            ),
            'list'   => array(
                'fields'  => array(
            		'specialty_id',
            		'specialty_page_descr',
					'clinic_page_descr',
				),
                'title'   => 'Контент для специальностей',
                'sort_by' => array(
                    array(
                        'field' => 'specialty_id',
                        'desc'  => 'ASC'
                    ),
                )
            ),
            'edit'   => array(
                'fields' => array(
                    'Данные' => array(
                        'specialty_id',
            			'specialty_page_descr',
						'clinic_page_descr',
                    ),
                ),
                'title'  => 'Редактирование',
                'submit' => 'Сохранить',
            ),
            'add'    => array(
                'fields' => array(
                    'Данные' => array(
                        'specialty_id',
            			'specialty_page_descr',
						'clinic_page_descr',
                    ),
                ),
                'title'  => 'Создание',
                'submit' => 'Создать',
            ),
        ),
    );


    CmsGeneratorConfigRegister::add('specialty_descr', $specialty_descr);