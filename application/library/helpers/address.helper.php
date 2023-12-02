<?php

class AddressHelper
{
    protected static $street_type_variations = [
        'аллея' => [
            'аллея',
            'ал.',
        ],
        'бульвар' => [
            'бульвар',
            'б-р',
            'бул.',
        ],
        'город' => [
            'г.',
            'город',
        ],
        'городок' => [
            'городок',
        ],
        'Горы' => [
            'Горы'
        ],
        'деревня' => [
            'деревня',
            'дер.',
            'д.',
        ],
        'квартал' => [
            'квартал',
        ],
        'линия' => [
            'линия',
        ],
        'микрорайон' => [
            'микрорайон',
        ],
        'мост' => [
            'мост',
        ],
        'набережная' => [
            'набережная'
        ],
        'парк' => [
            'парк',
        ],
        'переулок' => [
            'переулок',
            'пер.',
        ],
        'площадь' => [
            'площадь',
            'пл.',
        ],
        'пойма' => [
            'пойма',
        ],
        'посёлок' => [
            'посёлок',
            'поселок',
            'пос.',
            'п.г.т.',
            'пос.ж.д.ст.',
        ],
        'проезд' => [
            'проезд',
            'пр-д',
        ],
        'просека' => [
            'просека',
        ],
        'проспект' => [
            'проспект',
            'пр-кт',
            'пр-т',
            'пр.'
        ],
        'тупик' => [
            'тупик',
        ],
        'улица' => [
            'улица',
            'ул.',
            'уд.',
        ],
        'шоссе' => [
            'шоссе',
            'ш.',
        ],
        'эстакада' => [
            'эстакада',
        ]
    ];

    private static $ordinal_inflections = [
        'm' => ['й', 'ый', 'ой', 'ий'],
        'f' => ['я', 'ая'],
        'n' => ['е', 'ое'],
    ];

    /**
     * Парсер названия улицы
     * @param string $street
     * @return array
     */
    public static function parseStreet($street)
    {
        $name = $street;
        $type = 'улица';
        foreach (static::$street_type_variations as $canonical_type => $type_variations) {
            foreach ($type_variations as $type_variation) {
                if (preg_match('/(?:^| |,|;|\.)('.$type_variation.'(?:$| |,|;|\.))/iu', $name, $matches)) {
                    $name = trim(preg_replace('/\s\s+/', ' ', str_replace($matches[1], '', $name)));
                    $name = str_replace(',', '', $name);
                    $type = $canonical_type;
                    break 2;
                }
            }
        }
        $name_variations = [$name];
        foreach (static::$ordinal_inflections as $inflections) {
            foreach ($inflections as $inflection) {
                if (preg_match("/(\d+-)$inflection/", $name, $matches)) {
                    $ordinal = $matches[0];
                    $name_wo_ordinal = trim(preg_replace('/\s\s+/', ' ', str_replace($ordinal, '', $name)));
                    $name_wo_ordinal = str_replace(',', '', $name_wo_ordinal);
                    foreach ($inflections as $i) {
                        $ordinal_variations[] = $matches[1] . $i;
                    }
                    foreach ($ordinal_variations as $ordinal) {
                        $name_variations[] = "$name_wo_ordinal $ordinal";
                        $name_variations[] = "$ordinal $name_wo_ordinal";
                    }
                    break 2;
                }
            }
        }

        return [$type, $name_variations];
    }
}
