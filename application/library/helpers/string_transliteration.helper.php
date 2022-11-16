<?php
	class StringTransliterationHelper
	{
		public static function translit($str)
		{
			$replace=array(
				"'"=>"-",
				"`"=>"-",
				' '=>'-',
				"а"=>"a","А"=>"a",
				"б"=>"b","Б"=>"b",
				"в"=>"v","В"=>"v",
				"г"=>"g","Г"=>"g",
				"д"=>"d","Д"=>"d",
				"е"=>"e","Е"=>"e",
				'ё'=>'e','Ё'=>'e',
				"ж"=>"zh","Ж"=>"zh",
				"з"=>"z","З"=>"z",
				"и"=>"i","И"=>"i",
				"й"=>"y","Й"=>"y",
				"к"=>"k","К"=>"k",
				"л"=>"l","Л"=>"l",
				"м"=>"m","М"=>"m",
				"н"=>"n","Н"=>"n",
				"о"=>"o","О"=>"o",
				"п"=>"p","П"=>"p",
				"р"=>"r","Р"=>"r",
				"с"=>"s","С"=>"s",
				"т"=>"t","Т"=>"t",
				"у"=>"u","У"=>"u",
				"ф"=>"f","Ф"=>"f",
				"х"=>"h","Х"=>"h",
				"ц"=>"c","Ц"=>"c",
				"ч"=>"ch","Ч"=>"ch",
				"ш"=>"sh","Ш"=>"sh",
				"щ"=>"sch","Щ"=>"sch",
				"ъ"=>"","Ъ"=>"",
				"ы"=>"y","Ы"=>"y",
				"ь"=>"","Ь"=>"",
				"э"=>"e","Э"=>"e",
				"ю"=>"yu","Ю"=>"yu",
				"я"=>"ya","Я"=>"ya",
				"і"=>"i","І"=>"i",
				"ї"=>"yi","Ї"=>"yi",
				"є"=>"e","Є"=>"e"
			);

			$str=iconv("UTF-8","UTF-8//IGNORE",strtr($str,$replace));

			$str = preg_replace('/[^A-Za-z0-9\-]/', '', $str);
			$str = preg_replace('/[\-]{2,}/', '-', $str);
			return $str;
		}

        public static function formatAlias($str)
        {
            return strtolower(self::translit(str_replace('_', '-', $str)));
        }
    }
