<?php
    $bing_keys = array(
        'table'     => DB_PREFIX . 'bing_keys',
        'title'     => 'Ключи Bing',
        'fields'    => array(
            'id'        => 'index',
            'key'      => array(
                'type' => 'input',
                'style' => 'min-width: 350px;'
            ),
            'transactions_count'      => 'just_text',
        ),
        'generator' => array(
            'fields'   => array(
                'id'        => 'ID',
                'key' => 'Ключ Bing',
                'transactions_count'      => 'Количество транзакций',
            ),
            'list'     => array(
                'fields'  => array('key', 'transactions_count'),
                'title'   => 'Список ключей Bing',
                'sort_by' => array(
                    array(
                        'field' => 'transactions_count',
                        'desc'  => 'ASC'
                    ),
                )
            ),
            'edit'     => array(
                'fields' => array(
                    'Данные' => array(
                        'id', 'key', 'transactions_count'
                    ),
                ),
                'title'  => 'Редактирование ключей',
                'submit' => 'Сохранить',
            ),
            'add'     => array(
                'fields' => array(
                    'Данные' => array(
                        'id', 'key'
                    ),
                ),
                'title'  => 'Добавление ключей',
                'submit' => 'Добавить',
            ),
        )
    );

    CmsGeneratorConfigRegister::add('bing_keys', $bing_keys);