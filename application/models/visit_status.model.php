<?php
    /**
     * Class VisitStatusModel
     *
     * @property int $id
     * @property string $name
     */
    class VisitStatusModel extends DynamicModel
    {
        const CHECKING = 1;
        const CANCELLED = 2;
        const CONFIRMED = 3;
        const REJECTED = 4;
        const CALL_TO_CLINIC = 5;
        const FEEDBACK = 6;
        const VISITED = 7;
        const NOT_VISITED = 8;
        const TO_FILL = 9;
    }