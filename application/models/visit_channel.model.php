<?php

    /**
     * @property int $id
     * @property string $name
     */
    class VisitChannelModel extends DynamicModel
    {
        const SITE = 1;
        const MOBILE = 2;
        const WIDGET = 3;
        const YANDEX = 4;
        const APPEAL = 5;
        const LANDING = 6;
    }