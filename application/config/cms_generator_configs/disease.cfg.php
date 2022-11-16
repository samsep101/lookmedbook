<?php
    $disease = array(
        'table'     => DB_PREFIX . 'disease',
        'title'     => 'Заболевания',
        'fields'    => array(
            'id'                 => 'index',
            'title'              => 'input',
            'alias'              => 'input',
            'content'            => 'htmlarea',
            'content_markup'     => 'text',
            'is_active'          => array(
                'type'  => 'checkbox',
                'label' => 'Да/нет'
            ),
            'votes_count'        => 'input',
            'procent_understand' => 'input',
            'genitive_name' => 'input',
            'prepositional_name' => 'input',
            'page_ad' => 'text',
            'page_ad_after_diagnostics' => 'text',
        ),
        'extra'     => array(
            'disease_alt_name'       => array(
                'table' => 'disease_alt_name',
                'title' => 'Альтернативные названия',
                'field' => 'disease_id'
            ),
            'disease_to_disease_tag' => array(
                'table' => 'disease_to_disease_tag',
                'title' => 'Теги',
                'field' => 'disease_id'
            ),
            'disease_understand'     => array(
                'table' => 'disease_understand',
                'title' => 'Понятие пользователями текста заболевания',
                'field' => 'disease_id'
            ),
            'medicine_to_disease'    => array(
                'table' => 'medicine_to_disease',
                'title' => 'Лекарства',
                'field' => 'disease_id'
            ),
            'specialty_to_disease'   => array(
                'table' => 'specialty_to_disease',
                'title' => 'Специальности',
                'field' => 'disease_id'
            ),
            'my_disease'             => array(
                'table' => 'my_disease',
                'title' => 'Пользователи, которые добавили в избранное',
                'field' => 'disease_id'
            ),
        ),
        'generator' => array(
            'fields' => array(
                'id'                 => 'ID',
                'title'              => 'Название',
                'alias'              => 'Алиас',
                'content'            => 'Содержимое',
                'content_markup'     => 'Разметка',
                'is_active'			 => 'Активен',
                'votes_count'        => 'Голоса за понятие текста заболевания',
                'procent_understand' => 'Процент понявших текст заболевания',
                'genitive_name'      => 'Название в родительном падеже',
                'prepositional_name' => 'Название в предложном падеже',
                'page_ad' => 'Рекламный блок на странице заболевания',
                'page_ad_after_diagnostics' => 'Рекламный блок на странице заболевания (после блока диагностики)',
            ),
            'list'   => array(
                'fields'  => array(
                    'id',
                    'title',
                    'genitive_name',
                    'prepositional_name',
                    'alias',
                    'is_active',
                    'votes_count',
                    'procent_understand'
                ),
                'title'   => 'Список заболеваний',
                'sort_by' => array(
                    array(
                        'field' => 'title',
                        'desc'  => 'ASC'
                    ),
                )

            ),
            'edit'   => array(
                'fields'  => array(
                    'Данные' => array(
                        'title',
                        'genitive_name',
                        'prepositional_name',
                        'alias',
                        'is_active',
                        'content',
                    ),
                    'Рекламные блоки' => [
                        'page_ad',
                        'page_ad_after_diagnostics',
                    ],
                ),
                'tooltip' => '<p>Для того, чтобы <b>изменения вступили в силу</b>, необходимо выйти и войти в систему.</p>',
                'title'   => 'Редактирование',
                'submit'  => 'Сохранить',
            ),
            'add'    => array(
                'fields'  => array(
                    'Данные' => array(
                        'title',
                        'genitive_name',
                        'prepositional_name',
                        'alias',
                        'content',
                    ),
                    'Рекламные блоки' => [
                        'page_ad',
                        'page_ad_after_diagnostics',
                    ],
                ),
                'tooltip' => '<p>Для того, чтобы <b>изменения вступили в силу</b>, необходимо выйти и войти в систему.</p>',
                'title'   => 'Создать новое',
                'submit'  => 'Создать новое',
            ),
        ),
    );

    CmsGeneratorConfigRegister::add('disease', $disease);
