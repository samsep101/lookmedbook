<?php
    class ImageFindStatusManager extends StaticDataModelManager
    {
        protected $model_name = 'ImageFindStatusModel';

        protected $model_data = array(
            ImageFindStatusModel::UPLOADED => array(
                'id' => ImageFindStatusModel::UPLOADED,
                'name' => 'Загружено'
            ),
            ImageFindStatusModel::IN_QUEUE => array(
                'id' => ImageFindStatusModel::IN_QUEUE,
                'name' => 'В очереди'
            ),
            ImageFindStatusModel::NOT_FOUND => array(
                'id' => ImageFindStatusModel::NOT_FOUND,
                'name' => 'Не найдено'
            ),
            ImageFindStatusModel::OK => array(
                'id' => ImageFindStatusModel::OK,
                'name' => 'Готово'
            ),
            ImageFindStatusModel::ERROR => array(
                'id' => ImageFindStatusModel::ERROR,
                'name' => 'Ошибка'
            ),
			ImageFindStatusModel::FIND_IN_VIDAL => array(
				'id' => ImageFindStatusModel::FIND_IN_VIDAL,
				'name' => 'Найдено в базе Vidal'
			),
			ImageFindStatusModel::AUTO => array(
				'id' => ImageFindStatusModel::AUTO,
				'name' => 'Найдено автоматически'
			),
        );
    }