<?php
    /**
     * Class ClinicStatusModel
     *
     * @property int $id
     * @property string $name
     */
    class ClinicStatusModel extends DynamicModel
    {
        const PUBLISHED = 1;
        const RAW = 2;
        const PROBLEM = 3;
    }