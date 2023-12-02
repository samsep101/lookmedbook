<?php
class RegionStatusViewHelper
{
    public static function getStatusImage($clinic_status_id)
    {
        $status_image = '';
        if ($clinic_status_id)
            switch ($clinic_status_id)
            {
                case ClinicStatusModel::PUBLISHED:
                    $status_image = '<span class="region-img region-public"></span>';
                    break;
                case ClinicStatusModel::PROBLEM:
                    $status_image = '<span class="region-img region-problem"></span>';
                    break;
                case ClinicStatusModel::RAW:
                    $status_image = '<span class="region-img region-new"></span>';
                    break;
                default:
                    return false;
                    break;
            }

        return $status_image;
    }
}