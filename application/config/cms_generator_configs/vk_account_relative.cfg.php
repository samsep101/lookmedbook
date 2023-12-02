<?php
    $cms_vk_account_relative = array(
        'table'     => DB_PREFIX . 'vk_account_relative',
        'title'     => 'ВКонтакте аккаунт - родственники',
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
            'relative_uid'  => 'input',
            'relative_type' => 'input',
        ),

        'generator' => array(
            'fields' => array(
                'id'            => 'ID',
                'vk_account_id' => 'Аккаунт из ВКонтакте',
                'relative_uid'  => 'Идентификатор родственника в ВКонтакте',
                'relative_type' => 'Тип связи',
            ),
            'list'   => array(
                'fields'  => array(
                    'relative_uid',
                    'relative_type',
                ),
                'title'   => 'Список родственников аккаунтов из ВКонтакте',
                'sort_by' => array(
                    array(
                        'field' => 'vk_account_id',
                        'desc'  => 'ASC'
                    ),
                )

            ),
            'edit'   => array(
                'fields'  => array(
                    'Данные' => array(
                        'vk_account_id',
                        'relative_uid',
                        'relative_type',
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
                        'relative_uid',
                        'relative_type',
                    ),
                ),
                'tooltip' => '<p>Для того, чтобы <b>изменения вступили в силу</b>, необходимо выйти и войти в систему.</p>',
                'title'   => 'Добавить',
                'submit'  => 'Добавить',
            ),
        ),
    );

    CmsGeneratorConfigRegister::add('vk_account_relative', $cms_vk_account_relative);