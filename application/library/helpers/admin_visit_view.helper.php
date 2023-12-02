<?php
    class AdminVisitViewHelper
    {

        public static function getVisitStatusView($status_id)
        {
            $result = '<script>';

            switch ($status_id) {
                case VisitModel::CHECKING:
                    $result .= '$(\'span[name="status_id_' . VisitModel::CALL_TO_CLINIC . '"]\').css("text-decoration","underline");';
                    $result .= '</script>';
                    break;
                case VisitModel::CALL_TO_CLINIC:
                    $result .= '$(\'span[name="status_id_' . VisitModel::CANCELLED . '"]\').css("text-decoration","underline");';
                    $result .= '$(\'span[name="status_id_' . VisitModel::CONFIRMED . '"]\').css("text-decoration","underline");';
                    $result .= '</script>';
                    break;
                case VisitModel::FEDDBACK:
                    $result .= '$(\'span[name="status_id_' . VisitModel::VISITED . '"]\').css("text-decoration","underline");';
                    $result .= '$(\'span[name="status_id_' . VisitModel::NOT_VISITED . '"]\').css("text-decoration","underline");';
                    $result .= '</script>';
                    break;
                default:
                    $result .= '</script>';
                    break;
            };

            return $result;
        }
    }