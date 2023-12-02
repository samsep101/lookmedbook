<?php

namespace app\library\helpers;

class Morpher
{
    private static $exceptionWords = [
        'врач-онколог (ОМС)' => [
            'im mn' => 'врачи-онкологи (ОМС)',
        ],
    ];

    public static function inflect($words, $case)
    {
        if (!empty(static::$exceptionWords[$words][$case])) {
            return static::$exceptionWords[$words][$case];
        }
        $result = morpher_inflect($words, $case);
        if (strpos($result, '#ERROR: ') !== false) {
            return $words;
        }
        return $result;
    }
}
