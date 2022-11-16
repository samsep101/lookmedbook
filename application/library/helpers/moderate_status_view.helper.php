<?php
    class ModerateStatusViewHelper {

        public static function view($moderate_status_id)
        {
            $result = '';

            switch($moderate_status_id)
            {
                case ModerateStatusModel::EDIT:
                    $result = 'Статус: Редактируется, не отправлено в '.SITE_NAME;
                    break;
                case ModerateStatusModel::MODERATE:
                    $result = 'Статус: Отправлено в '.SITE_NAME;
                    break;
                case ModerateStatusModel::PUBLISHED:
                    $result = 'Статус: Опубликован на '.SITE_NAME;
                    break;
                case ModerateStatusModel::SENT_BACK:
                    $result = 'Статус: Нужна доработка';
                    break;
            }

            return $result;
        }
    }