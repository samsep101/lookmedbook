<?php
    $cms_vk_account_group = array(
        'table'     => DB_PREFIX . 'vk_account_group',
        'title'     => 'ВКонтакте аккаунты - группы',
        'fields'    => array(
            'id'            => 'index',
            'vk_account_id' => array(
                'type'        => 'category',
                'cross_name'  => 'last_name',
                'cross_index' => 'id',
                'cross_table' => DB_PREFIX . 'vk_account',
                'first'       => array(
                    '0' => '',
                ),
                'filter'      => 'true',
                'sort_by'     => 'last_name',
            ),
            'gid'           => 'input',
            'name'          => 'input',
            'group_url'     => 'input',
            'description'   => 'text',
            'type'          => 'input',
            'members_count' => 'input',
            'is_closed'     => array('type' => 'checkbox', 'label' => 'Да/Нет'),
            'is_member'     => array('type' => 'checkbox', 'label' => 'Да/Нет'),
            'is_admin'      => array('type' => 'checkbox', 'label' => 'Да/Нет'),
            'is_can_post'   => array('type' => 'checkbox', 'label' => 'Да/Нет'),
        ),

        'generator' => array(
            'fields' => array(
                'id'            => 'ID',
                'vk_account_id' => 'Аккаунт из ВКонтакте',
                'gid'           => 'Идентификатор в ВКонтакте',
                'name'          => 'Название',
                'group_url'     => 'Ссылка на группу',
                'description'   => 'Орисание',
                'type'          => 'Тип группы',
                'members_count' => 'Количество участников',
                'is_closed'     => 'Закрытая группа',
                'is_member'     => 'Пользователь является участником группы',
                'is_admin'      => 'Пользователь является администратором группы',
                'is_can_post'   => 'Пользователь может написать на стене группы',
            ),
            'list'   => array(
                'fields'  => array(
                    'group_url',
                    'name',
                    'type',
                    'members_count',
                    'is_closed',
                    'is_member',
                    'is_admin',
                    'is_can_post'
                ),
                'title'   => 'Список групп аккаунтов из ВКонтакте',
                'sort_by' => array(
                    array(
                        'field' => 'name',
                        'desc'  => 'ASC'
                    ),
                )

            ),
            'edit'   => array(
                'fields'  => array(
                    'Данные' => array(
                        'vk_account_id',
                        'gid',
                        'group_url',
                        'name',
                        'type',
                        'members_count',
                        'is_closed',
                        'is_member',
                        'is_admin',
                        'is_can_post'
                    ),
                ),
                'tooltip' => '<p>Для того, чтобы <b>изменения вступили в силу</b>, необходимо выйти и войти в систему.</p>',
                'title'   => 'Редактирование',
                'submit'  => 'Сохранить',
            ),
            'add'    => array(
                'fields'  => array(
                    'Данные' => array(
                        'vk_account_id',
                        'gid',
                        'group_url',
                        'name',
                        'type',
                        'members_count',
                        'is_closed',
                        'is_member',
                        'is_admin',
                        'is_can_post'
                    ),
                ),
                'tooltip' => '<p>Для того, чтобы <b>изменения вступили в силу</b>, необходимо выйти и войти в систему.</p>',
                'title'   => 'Добавить',
                'submit'  => 'Добавить',
            ),
        ),
    );

    CmsGeneratorConfigRegister::add('vk_account_group', $cms_vk_account_group);