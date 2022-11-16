<?php
  $appeal = array(
    'table' => DB_PREFIX . 'appeal',
    'title' => 'Обращения',
    'fields' => array(
      'id' => 'index',
      'phone_number' => 'input',
      'first_name' => 'input',
      'middle_name' => 'input',
      'last_name' => 'input',
      'full_name' => 'just_text',
      'appeal_type_id' => array(
        'type' => 'category',
        'cross_name' => 'name',
        'cross_index' => 'id',
        'cross_table' => DB_PREFIX . 'appeal_type',
        'filter' => 'true',
        'sort_by' => 'name',
      ),
      'visit_source_id' => array(
        'type' => 'category',
        'cross_name' => 'name',
        'cross_index' => 'id',
        'cross_table' => DB_PREFIX . 'visit_source',
        'filter' => 'true',
        'sort_by' => 'name',
      ),
      'target_call_id' => array(
        'type' => 'category',
        'cross_name' => 'name_with_phone',
        'cross_index' => 'id',
        'cross_table' => DB_PREFIX . 'target_call',
        'first' => array(
          '0' => '-',
        ),
        'sort_by' => 'name',
      ),
      'title' => array(
        'type' => 'text'
      ),
      'specialty_id' => array(
        'type' => 'category',
        'cross_name' => 'name',
        'cross_index' => 'id',
        'cross_table' => DB_PREFIX . 'specialty',
        'filter' => 'true',
        'first' => array(
          0 => '',
        ),
        'sort_by' => 'name',
      ),
      'is_with_visit' => array(
        'type' => 'checkbox',
        'label' => 'Да/Нет'
      ),
      'account' => array(
        'type' => 'reference',
        'table' => 'account',
        'field' => 'full_name'
      ),
      'dt_create' => array(
        'type' => 'date',
        'show_time' => true
      ),
      'visit' => array(
        'type' => 'reference',
        'table' => 'visit',
        'field' => 'id'
      ),
    ),

    'generator' => array(
      'fields' => array(
        'id' => 'ID',
        'phone_number' => 'Телефон',
        'first_name' => 'Имя',
        'middle_name' => 'Отчество',
        'last_name' => 'Фамилия',
        'full_name' => 'Имя пользователя',
        'appeal_type_id' => 'Тип',
        'visit_source_id' => 'Откуда пришел',
        'target_call_id' => 'Целевой звонок',
        'title' => 'Тема',
        'specialty_id' => 'Специализация',
        'is_with_visit' => 'С записью',
        'account' => 'Аккаунт',
        'dt_create' => 'Дата и время создания',
        'visit' => 'Номер визита'
      ),
      'list' => array(
        'fields' => array(
          'id',
          'phone_number',
          'full_name',
          'appeal_type_id',
          'visit_source_id',
          'specialty_id',
          'is_with_visit',
          'dt_create'
        ),
        'title' => 'Обращения',
        'sort_by' => array(
          array(
            'field' => 'id',
            'desc' => 'DESC'
          ),
        ),
        'filters' => array(
          'use_class_params' => 'AppealSearchParams',
          'filters' => array(
            'Период создания заявки' => array(
              'dt_create_from' => array(
                'type' => 'date',
                'title' => 'c'
              ),
              'dt_create_to' => array(
                'type' => 'date',
                'title' => 'по'
              ),
            ),
          )
        ),
        'total_count' => array(
          'show' => true,
          'text' => 'Общее количество обращений'
        )
      ),
      'edit' => array(
        'fields' => array(
          'Данные' => array(
            'phone_number',
            'last_name',
            'first_name',
            'middle_name',
            'account',
            'appeal_type_id',
            'visit_source_id',
            'target_call_id',
            'title',
            'specialty_id',
            'is_with_visit',
            'visit',
      			'dt_create'
          ),
        ),
        'tooltip' => '<p>Для того, чтобы <b>изменения вступили в силу</b>, необходимо выйти и войти в систему.</p>',
        'title' => 'Редактирование',
        'submit' => 'Сохранить',
      ),
      'add' => array(
        'fields' => array(
          'Данные' => array(
            'phone_number',
            'last_name',
            'first_name',
            'middle_name',
            'appeal_type_id',
            'visit_source_id',
            'target_call_id',
            'title',
            'specialty_id',
            'is_with_visit',
            'visit',
						'dt_create'
          ),
        ),
        'tooltip' => '<p>Для того, чтобы <b>изменения вступили в силу</b>, необходимо выйти и войти в систему.</p>',
        'title' => 'Добавить',
        'submit' => 'Добавить',
      ),
    ),
  );

  CmsGeneratorConfigRegister::add('appeal', $appeal);