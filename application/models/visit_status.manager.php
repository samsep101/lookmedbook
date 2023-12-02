<?php
    class VisitStatusManager extends StaticDataModelManager
    {
        protected $model_data = array(
            VisitStatusModel::CHECKING => array(
                'id' => VisitStatusModel::CHECKING,
                'name' => 'Новая заявка'
            ),
            VisitStatusModel::CANCELLED => array(
                'id' => VisitStatusModel::CANCELLED,
                'name' => 'Отменено'
            ),
            VisitStatusModel::CONFIRMED => array(
                'id' => VisitStatusModel::CONFIRMED,
                'name' => 'Пациент записан',
            ),
            /*
            self::REJECTED => array(
                'id' => self::REJECTED,
                'name' => 'Отклонено'
            ),
            */
            VisitStatusModel::CALL_TO_CLINIC => array(
                'id' => VisitStatusModel::CALL_TO_CLINIC,
                'name' => 'Звонок в клинику',
            ),
            VisitStatusModel::FEEDBACK => array(
                'id' => VisitStatusModel::FEEDBACK,
                'name' => 'Обратная связь'
            ),
            VisitStatusModel::VISITED => array(
                'id' => VisitStatusModel::VISITED,
                'name' => 'Был у врача',
            ),
            VisitStatusModel::NOT_VISITED => array(
                'id' => VisitStatusModel::NOT_VISITED,
                'name' => 'Не был у врача'
            )
        );

        protected $model_name = 'VisitStatusModel';
    }
