<?php

    class VisitChannelManager extends StaticDataModelManager
    {
        protected $model_data = array(
            VisitChannelModel::SITE => array(
                'id' => VisitChannelModel::SITE,
                'name' => 'Сайт'
            ),
            VisitChannelModel::MOBILE => array(
                'id' => VisitChannelModel::MOBILE,
                'name' => 'Мобильное приложение'
            ),
            VisitChannelModel::WIDGET => array(
                'id' => VisitChannelModel::WIDGET,
                'name' => 'Виджет'
            ),
            VisitChannelModel::YANDEX => array(
                'id' => VisitChannelModel::YANDEX,
                'name' => 'Яндекс'
            ),
            VisitChannelModel::APPEAL => array(
                'id' => VisitChannelModel::APPEAL,
                'name' => 'Обращение'
            )
        );

        protected $model_name = 'VisitChannelModel';
    }