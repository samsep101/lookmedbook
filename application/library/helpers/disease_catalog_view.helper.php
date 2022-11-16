<?php
    class DiseaseCatalogViewHelper
    {
        public static function divideByLetters(array $diseases)
        {
            $tmp = array();

            $letters = array('А', 'Б', 'В', 'Г', 'Д', 'Е', 'Ё', 'Ж', 'З', 'И', 'Й', 'К', 'Л', 'М', 'Н', 'О', 'П', 'Р', 'С', 'Т', 'У', 'Ф', 'Х', 'Ц', 'Ч', 'Ш', 'Щ', 'Э', 'Ю', 'Я', 'A-Z');
            $letters_eng = array('A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z');

            foreach ($letters as $letter) {
                $tmp[$letter] = array(
                    'letter' => $letter,
                    'result' => array()
                );
            }

            if ($diseases) {
                foreach ($diseases as $disease) {
                    $letter = mb_substr($disease->title, 0, 1, 'UTF-8');
                    if (isset($tmp[$letter]))
                        $tmp[$letter]['result'][] = $disease;
                    else if (in_array($letter, $letters_eng))
                        $tmp['A-Z']['result'][] = $disease;
                }
            }

            $result = array();

            foreach ($tmp as $v) {
                if ($v['result'])
                    $result[] = $v;
            }

            return $result;
        }
    }