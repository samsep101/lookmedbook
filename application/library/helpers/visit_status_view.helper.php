<?php

class VisitStatusViewHelper {

    public function view($status_id)
    {
        $result = '';

        switch($status_id)
        {
            case VisitModel::CHECKING:
                $result = 'Новая заявка';
                break;
            case VisitModel::CANCELLED:
                $result = 'Отменено';
                break;
            case VisitModel::CONFIRMED:
                $result = 'Подтверждено';
                break;
            case VisitModel::REJECTED:
                $result = 'Отклонено';
                break;
            case VisitModel::CALL_TO_CLINIC:
                $result = 'Звонок в клинику';
                break;
            case VisitModel::FEDDBACK:
                $result = 'Подтверждено';
                break;
            case VisitModel::VISITED:
                $result = 'Был у врача';
                break;
            case VisitModel::NOT_VISITED:
                $result = 'Не был у врача';
                break;
        }
        return $result;
    }
}