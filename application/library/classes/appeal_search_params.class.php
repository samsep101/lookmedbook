<?php
class AppealSearchParams extends ModelSearchCriteria
{
    public $dt_create_from;
    public $dt_create_to;

    public function getDirectIgnoredKeys()
    {
        return ['dt_create_from', 'dt_create_to'];
    }
}