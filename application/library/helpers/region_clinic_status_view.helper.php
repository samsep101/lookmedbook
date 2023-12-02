<?php
    class RegionClinicStatusViewHelper {

        public static function getView(ClinicStatusModel $model = null)
        {
            $html = '<span class="region-img ';

            if (!$model)
            {
                $html .= 'region-new';
            } else {
                switch($model->getId())
                {
                    case ClinicStatusModel::PROBLEM:
                        $html .= 'region-problem';
                        break;
                    case ClinicStatusModel::PUBLISHED:
                        $html .= 'region-public';
                }
            }
            $html .= '"></span> ';
            $html .= ($model) ?  $model->name : 'Новая';

            return $html;
        }
    }