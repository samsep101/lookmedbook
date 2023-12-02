<?php
    $cms_vk_account = array(
        'table'     => DB_PREFIX . 'vk_account',
        'title'     => 'ВКонтакте аккаунты',
        'fields'    => array(
            'id'                          => 'index',
            'account_id'                  => array(
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
            'uid'                         => 'input',
            'first_name'                  => 'input',
            'last_name'                   => 'input',
            'user_name'                   => 'input',
            'profile_url'                 => 'input',
            'home_phone'                  => 'input',
            'activity'                    => 'text',
            'count_groups'                => 'input',
            'count_friends'               => 'input',
            'relation_type'               => array(
                'type'   => 'listvalue',
                'values' => array(
                    '0' => 'не установлено',
                    '1' => 'не женат/не замужем',
                    '2' => 'есть друг/есть подруга',
                    '3' => 'помолвлен/помолвлена',
                    '4' => 'женат/замужем',
                    '5' => 'всё сложно',
                    '6' => 'в активном поиске',
                    '7' => 'влюблён/влюблена',
                )
            ),
            'relation_partner_uid'        => array('type' => 'checkbox', 'label' => 'Да/Нет'),
            'relation_partner_first_name' => 'input',
            'relation_partner_uid'        => 'input',
            'relation_partner_last_name'  => 'input',
            'interests'                   => 'text',
            'movies'                      => 'text',
            'tv'                          => 'text',
            'books'                       => 'text',
            'games'                       => 'text',
            'about'                       => 'text',
            'is_account_connected'        => array('type' => 'checkbox', 'label' => 'Да/Нет'),
        ),

        'extra'     => array(
            'school'      => array(
                'table' => 'vk_account_school',
                'title' => 'Школы/ССУЗ',
                'field' => 'vk_account_id'
            ),

            'universitys' => array(
                'table' => 'vk_account_university',
                'title' => 'ВУЗ',
                'field' => 'vk_account_id'
            ),

            'relative'    => array(
                'table' => 'vk_account_relative',
                'title' => 'Родственники',
                'field' => 'vk_account_id'
            ),

            'friends'     => array(
                'table' => 'vk_account_friend',
                'title' => 'Друзья',
                'field' => 'vk_account_id'
            ),

            'groups'      => array(
                'table' => 'vk_account_group',
                'title' => 'Группы',
                'field' => 'vk_account_id'
            ),
        ),

        'generator' => array(
            'fields' => array(
                'id'                          => 'ID',
                'account_id'                  => 'Аккаунт',
                'uid'                         => 'Идентификатор',
                'first_name'                  => 'Имя',
                'last_name'                   => 'Фамилия',
                'user_name'                   => 'Имя на сайте',
                'profile_url'                 => 'Ссылка на профиль',
                'home_phone'                  => 'Домашний телефон',
                'activity'                    => 'Статус',
                'count_groups'                => 'Количество групп',
                'count_friends'               => 'Количество друзей',
                'relation_type'               => 'Семейное положение',
                'relation_partner_uid'        => 'Идентификатор партнера',
                'relation_partner_first_name' => 'Имя партнера',
                'relation_partner_last_name'  => 'Фамилия партнера',
                'interests'                   => 'Интересы',
                'movies'                      => 'Любимые фильмы',
                'tv'                          => 'Любимые телешоу',
                'books'                       => 'Любимые книги',
                'games'                       => 'Любимые игры',
                'about'                       => 'О себе',
                'is_account_connected'        => 'Присоединен к аккаунту в системе',
            ),
            'list'   => array(
                'fields'  => array(
                    'account_id',
                    'uid',
                    'last_name',
                    'first_name',
                    'profile_url',
                    'count_groups',
                    'count_friends',
                    'is_account_connected'
                ),
                'title'   => 'Список аккаунтов из vk.com',
                'sort_by' => array(
                    array(
                        'field' => 'last_name',
                        'desc'  => 'ASC'
                    ),
                )

            ),
            'edit'   => array(
                'fields'  => array(
                    'Данные' => array(
                        'account_id',
                        'uid',
                        'first_name',
                        'last_name',
                        'user_name',
                        'profile_url',
                        'home_phone',
                        'activity',
                        'count_groups',
                        'count_friends',
                        'relation_type',
                        'relation_partner_uid',
                        'relation_partner_first_name',
                        'relation_partner_uid',
                        'relation_partner_last_name',
                        'interests',
                        'movies',
                        'tv',
                        'books',
                        'games',
                        'about',
                        'is_account_connected'
                    ),
                ),
                'tooltip' => '<p>Для того, чтобы <b>изменения вступили в силу</b>, необходимо выйти и войти в систему.</p>',
                'title'   => 'Редактирование',
                'submit'  => 'Сохранить',
            ),
            'add'    => array(
                'fields'  => array(
                    'Данные' => array(
                        'account_id',
                        'uid',
                        'first_name',
                        'last_name',
                        'user_name',
                        'profile_url',
                        'home_phone',
                        'activity',
                        'count_groups',
                        'count_friends',
                        'relation_type',
                        'relation_partner_uid',
                        'relation_partner_first_name',
                        'relation_partner_uid',
                        'relation_partner_last_name',
                        'interests',
                        'movies',
                        'tv',
                        'books',
                        'games',
                        'about',
                        'is_account_connected'
                    ),
                ),
                'tooltip' => '<p>Для того, чтобы <b>изменения вступили в силу</b>, необходимо выйти и войти в систему.</p>',
                'title'   => 'Добавить',
                'submit'  => 'Добавить',
            ),
        ),
    );

    CmsGeneratorConfigRegister::add('vk_account', $cms_vk_account);