<?php

namespace StringHelpers;

mb_internal_encoding('UTF-8');

function slug($text, $separator = '-') {

		$text = preg_replace('#[^a-zA-Zа-яА-Я0-9\s]+#ui', '', $text);
		$text = mb_strtolower(trim($text));

		$text = strtr($text, array(
			"а" => "a", "б" => "b", "в" => "v", "г" => "g", "д" => "d",
			"е" => "e", "ё" => "e", "ж" => "j", "з" => "z", "и" => "i",
			"й" => "y", "к" => "k", "л" => "l", "м" => "m", "н" => "n",
			"о" => "o", "п" => "p", "р" => "r", "с" => "s", "т" => "t",
			"у" => "u", "ф" => "f", "х" => "h", "ц" => "c", "ч" => "ch",
			"ш" => "sh", "щ" => "sch", "ъ" => "y", "ы" => "i", "ь" => "",
			"э" => "e", "ю" => "ju", "я" => "ja",
		));
		$text = preg_replace('#\s+#ui', $separator, $text);

		return $text;
	}

    function free_morpher($string) {

        $url = 'https://ws3.morpher.ru/russian/declension';

        $response = file_get_contents($url.'?'.http_build_query([
            's' => $string,
            'format' => 'json'
        ]));

        if($response AND $response = json_decode($response, true)){
            return $response;
        }

        return false;
    }

    /**
	 * ->plural($N, ['булочка','булочки','булочек']) // 1,2,5
	 * @param int $n
	 * @param array $forms
	 * @return string
	 */
	function plural($n, array $forms) {
		return $n % 10 == 1 && $n % 100 != 11 ? $forms[0] : ($n % 10 >= 2 && $n % 10 <= 4 && ($n % 100 < 10 || $n % 100 >= 20) ? $forms[1] : $forms[2]);
	}

    function format_phone($phone) {
        $phone = preg_replace('#[^\d]+#', '', $phone);
        if(mb_strlen($phone) == 11){
            return vsprintf('%d (%d%d%d) %d%d%d-%d%d-%d%d', str_split($phone));
        }
        return $phone;
    }