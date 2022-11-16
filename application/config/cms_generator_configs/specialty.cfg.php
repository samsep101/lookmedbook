<?php
  $specialty = array(
    'table'   => DB_PREFIX . 'specialty',
    'title'   => 'Специальности',
    'fields'  => array(
      'id'       => 'index',
      'name'     => 'input',
      'alias_synonim'     => 'input',
      'dative_name'  => 'input',
      'genitive_name'  => 'input',
      'genitive_name_plural' => 'input',
      'service_name' => 'input',
      'plural_name' => 'input',
      'purposes_of_visit_count' => array(
        'type' => 'hightlight_row',
        'colors' => array(
          0 => '#FFE4C4'
        ),
      ),
      'specializations_count' => array(
        'type' => 'hightlight_row',
        'colors' => array(
          0 => '#FFE4C4'
        ),
      ),
      'main_purpose_of_visit' => array(
        'type' => 'hightlight_row',
        'colors' => array(
          false => '#FFE4C4'
        ),
      ),
      'for_whom' => array(
        'type'  =>  'radio',
        'values'  =>  array(
          '1' => 'Для детей и для взрослых',
          '2' => 'Только для взрослых',
          '3' => 'Только для детей'
        ),
      ),
      'view_specializations' => 'just_text',
    ),
    'extra'   => array(
      'specializations' => array(
        'table' => 'specialty_to_specialization',
        'title' => 'Области ' . MEDICYNY,
        'field' => 'specialty_id'
      ),
      'synonyms' => array(
        'table' => 'specialty_synonym',
        'title' => 'Синонимы специализаций',
        'field' => 'specialty_id'
      ),
      'purposes' => array(
        'table' => 'purpose_of_visit_to_specialty',
        'title' => 'Цели визита',
        'field' => 'specialty_id'
      ),
      'seo_text' => array(
        'table' => 'seo_text',
        'title' => 'SEO-тексты',
        'field' => 'specialty_id'
      ),
    ),
    'generator' => array(
      'fields' => array(
        'id'       => 'ID',
        'name'     => 'Название',
        'alias_synonim'     => 'Синонимы',
        'parent_id'  => 'Родительская специализация',
        'service_name' => 'Название услуги',
        'genitive_name' => 'Название в родительном падеже',
        'genitive_name_plural' => 'Название в родительном падеже во множественном числе',
        'dative_name'  => 'Название в дательном падеже',
        'plural_name'  => 'Название в множественном числе',
        'specializations_count'  => ' ',
        'purposes_of_visit_count'  => ' ',
        'for_whom'  => 'Взрослая/Детская',
        'view_specializations'  => 'Области ' . MEDICYNY,
      ),
      'list'   => array(
        'fields'  => array(
          'name',
          'genitive_name',
          'dative_name',
          'plural_name',
          'specializations_count',
          'purposes_of_visit_count',
          'for_whom',
          'specialty_page_descr',
          'clinic_page_descr',
          'view_specializations',  
        ),
        'title'   => 'Специализации',
        'sort_by' => array(
          array(
            'field' => 'name',
            'desc'  => 'ASC'
          ),
        )
      ),
      'edit'   => array(
        'fields' => array(
          'Данные' => array(
            'name',
            'alias_synonim',
            'genitive_name',
            'genitive_name_plural',
            'dative_name',
            'plural_name',
            'for_whom',
          ),
        ),
        'title'  => 'Редактирование',
        'submit' => 'Сохранить',
      ),
      'add'  => array(
        'fields' => array(
          'Данные' => array(
            'name',
            'alias_synonim',
            'genitive_name',
            'genitive_name_plural',
            'dative_name',
            'plural_name',
            'for_whom',
          ),
        ),
        'title'  => 'Создание',
        'submit' => 'Создать',
      ),
    ),
  );


  CmsGeneratorConfigRegister::add('specialty', $specialty);