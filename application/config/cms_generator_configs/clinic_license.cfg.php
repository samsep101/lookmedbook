<?php
    $clinic_license = array(
        'table'     => DB_PREFIX . 'clinic_license',
        'title'     => 'Лицензии клиники',
        'fields'    => array(
            'id'            => 'index',
            'number'        => 'input',
            'issue_date'    => array(
				'type' => 'date',
				'format' => 'd-m-Y'
			),
            'validity_date' => array(
				'type' => 'date',
				'format' => 'd-m-Y'
			),
            'name'          => 'input',
            'clinic_id'     => array(
                'type'        => 'category',
                'cross_name'  => 'name',
                'cross_index' => 'id',
                'cross_table' => DB_PREFIX . 'clinic',
                'first'       => array(
                    '0' => '',
                ),
                'sort_by'     => 'name',
            ),
        ),
        'generator' => array(
            'fields' => array(
                'id'            => 'ID',
                'number'        => 'Номер лицензии',
                'issue_date'    => 'Дата выдачи',
                'validity_date' => 'Срок действия',
                'name'          => 'Название',
                'clinic_id'     => 'Клиника',
            ),
            'list'   => array(
                'fields'  => array('number', 'issue_date', 'validity_date', 'clinic_id'),
                'title'   => 'Лицензии клиники',
                'sort_by' => array(
                    array(
                        'field' => 'number',
                        'desc'  => 'ASC'
                    ),
                )
            ),
            'edit'   => array(
                'fields' => array(
                    'Данные' => array(
                        'number', 'issue_date', 'validity_date', 'clinic_id'
                    ),
                ),
                'title'  => 'Редактирование',
                'submit' => 'Сохранить',
            ),
            'add'    => array(
                'fields' => array(
                    'Данные' => array(
                        'number', 'issue_date', 'validity_date', 'clinic_id'
                    ),
                ),
                'title'  => 'Создание',
                'submit' => 'Создать',
            ),
        ),
    );

    CmsGeneratorConfigRegister::add('clinic_license', $clinic_license);