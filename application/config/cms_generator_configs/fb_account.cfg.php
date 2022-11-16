<?php
    $cms_fb_account = array(
        'table'     => DB_PREFIX . 'fb_account',
        'title'     => 'Facebook.com аккаунты',
        'fields'    => array(
            'id'                      => 'index',
            'account_id'              => array(
                'type'        => 'category',
                'cross_name'  => 'full_name',
                'cross_index' => 'id',
                'cross_table' => DB_PREFIX . 'account',
                'first'       => array(
                    '0' => '',
                ),
                'filter'      => 'true',
                'sort_by'     => 'full_name',
            ),
            'uid'                     => 'input',
            'first_name'              => 'input',
            'last_name'               => 'input',
            'middle_name'             => 'input',
            'user_name'               => 'input',
            'profile_url'             => 'input',
            'bio'                     => 'text',
            'quotes'                  => 'text',
            'hometown'                => 'text',
            'political_view'          => 'text',
            'is_interested_in_male'   => array('type' => 'checkbox', 'label' => 'Да/Нет'),
            'is_interested_in_female' => array('type' => 'checkbox', 'label' => 'Да/Нет'),
            'relationship_status'     => array(
                'type'   => 'listvalue',
                'values' => array(
                    'Single'                    => 'Single',
                    'In a relationship'         => 'In a relationship',
                    'Engaged'                   => 'Engaged',
                    'Married'                   => 'Married',
                    'It\'s complicated'         => 'It\'s complicated',
                    'In an open relationship'   => 'In an open relationship',
                    'Widowed'                   => 'Widowed',
                    'Separated'                 => 'Separated',
                    'Divorced'                  => 'Divorced',
                    'In a civil union'          => 'In a civil union',
                    'In a domestic partnership' => 'In a domestic partnership'
                )
            ),
            'relation_partner_uid'    => 'input',
            'relation_partner_name'   => 'input',
            'religion'                => 'input',
            'web_sites'               => 'text',
            'is_account_connected'    => array('type' => 'checkbox', 'label' => 'Да/Нет'),
        ),

        'extra'     => array(
            'interests'   => array(
                'table' => 'fb_account_interes',
                'title' => 'Интересы',
                'field' => 'fb_account_id'
            ),

            'books'       => array(
                'table' => 'fb_account_book',
                'title' => 'Любимые книги',
                'field' => 'fb_account_id'
            ),

            'movies'      => array(
                'table' => 'fb_account_movie',
                'title' => 'Любимое видео',
                'field' => 'fb_account_id'
            ),

            'musics'      => array(
                'table' => 'fb_account_music',
                'title' => 'Любимая музыка',
                'field' => 'fb_account_id'
            ),

            'televisions' => array(
                'table' => 'fb_account_television',
                'title' => 'Любимые ТВ',
                'field' => 'fb_account_id'
            ),

            'languages'   => array(
                'table' => 'fb_account_language',
                'title' => 'Иностранные языки',
                'field' => 'fb_account_id'
            ),

            'educations'  => array(
                'table' => 'fb_account_education',
                'title' => 'Образование',
                'field' => 'fb_account_id'
            ),

            'likes'       => array(
                'table' => 'fb_account_like',
                'title' => '"Мне нравиться"',
                'field' => 'fb_account_id'
            ),

            'works'       => array(
                'table' => 'fb_account_work',
                'title' => 'Места работы',
                'field' => 'fb_account_id'
            ),

            'friends'     => array(
                'table' => 'fb_account_friend',
                'title' => 'Друзья',
                'field' => 'fb_account_id'
            ),

            'groups'      => array(
                'table' => 'fb_account_group',
                'title' => 'Группы',
                'field' => 'fb_account_id'
            ),
        ),

        'generator' => array(
            'fields' => array(
                'id'                      => 'ID',
                'account_id'              => 'Аккаунт',
                'uid'                     => 'Идентификатор',
                'first_name'              => 'Имя',
                'last_name'               => 'Фамилия',
                'middle_name'             => 'Отчество',
                'user_name'               => 'Имя на сайте',
                'profile_url'             => 'Ссылка на профиль',
                'bio'                     => 'О себе',
                'quotes'                  => 'Любимые цитаты',
                'hometown'                => 'Родной город',
                'political_view'          => 'Политические взгляды',
                'is_interested_in_male'   => 'Предпочтения мужчин',
                'is_interested_in_female' => 'Предпочтения женщин',
                'relationship_status'     => 'Семейное положение',
                'relation_partner_uid'    => 'Идентификатор партнера',
                'relation_partner_name'   => 'Имя партнера',
                'religion'                => 'Религиозные взгляды',
                'web_sites'               => 'Сайты',
                'is_account_connected'    => 'Присоединен к аккаунту в системе',
            ),
            'list'   => array(
                'fields'  => array(
                    'account_id',
                    'uid',
                    'last_name',
                    'first_name',
                    'profile_url',
                    'is_account_connected'
                ),
                'title'   => 'Список аккаунтов из Facebook.com',
                'sort_by' => array(
                    array(
                        'field' => 'last_name',
                        'desc'  => 'ASC'
                    ),
                )
            ),
            'edit'   => array(
                'fields'  => array(
                    'Данные социального аккаунта' => array(
                        'account_id',
                        'uid',
                        'first_name',
                        'last_name',
                        'middle_name',
                        'user_name',
                        'profile_url',
                        'bio',
                        'quotes',
                        'hometown',
                        'political_view',
                        'is_interested_in_male',
                        'is_interested_in_female',
                        'relationship_status',
                        'relation_partner_uid',
                        'relation_partner_name',
                        'religion',
                        'web_sites',
                        'is_account_connected'
                    ),
                ),
                'tooltip' => '<p>Для того, чтобы <b>изменения вступили в силу</b>, необходимо выйти и войти в систему.</p>',
                'title'   => 'Редактирование',
                'submit'  => 'Сохранить',
            ),
            'add'    => array(
                'fields'  => array(
                    'Данные социального аккаунта' => array(
                        'account_id',
                        'uid',
                        'first_name',
                        'last_name',
                        'middle_name',
                        'user_name',
                        'profile_url',
                        'bio',
                        'quotes',
                        'hometown',
                        'political_view',
                        'is_interested_in_male',
                        'is_interested_in_female',
                        'relationship_status',
                        'relation_partner_uid',
                        'relation_partner_name',
                        'religion',
                        'web_sites',
                        'is_account_connected'
                    ),
                ),
                'tooltip' => '<p>Для того, чтобы <b>изменения вступили в силу</b>, необходимо выйти и войти в систему.</p>',
                'title'   => 'Добавить',
                'submit'  => 'Добавить',
            ),
        ),
    );

    CmsGeneratorConfigRegister::add('fb_account', $cms_fb_account);