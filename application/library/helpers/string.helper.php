<?php

    class StringHelper
    {
        public static function trim($str, $length = 21, $finish_text = FALSE, $finish_text_link = false)
        {
            if (mb_strlen($str, 'utf-8') > $length) {
                $str = mb_substr($str, 0, $length - 3, 'utf-8');
                if ($finish_text && $finish_text_link) {
                    $str = $str . '...<a class="finish_text_link" href="'.$finish_text_link.'">' . $finish_text. '</a>';
                } else {
                    $str = $str . '...';
                }
                return $str;
            } else if (mb_strlen($str, 'utf-8') <= $length && $finish_text && $finish_text_link){
                $str = $str . '...<a class="finish_text_link" href="'.$finish_text_link.'">' . $finish_text. '</a>';
                return $str;
            } else {
                return $str;
            }
        }

        public static function trimText($str,$length = 20)
        {
            if (mb_strlen($str, 'utf-8') > $length) {
                $str = mb_substr($str, 0, $length - 3, 'utf-8').'...';
                return $str;
            } else {
                return $str;
            }
        }

        public static  function showLetters($url)
        {
            $letters = array('А', 'Б', 'В', 'Г', 'Д', 'Е', 'Ж', 'З', 'И', 'К', 'Л', 'М', 'Н', 'О', 'П', 'Р', 'С', 'Т', 'У', 'Ф', 'Х', 'Ц', 'Ч', 'Ш', 'Щ', 'Э', 'Ю', 'Я');

            $str = '';
            foreach ($letters as $letter) {
                $str .= '<li class="letter-' . $letter . '"><a href="' . $url . '?letter=' . $letter . '">' . $letter . '</a><i></i></li>';
            }
            $str .= '<li class="all-letters"><a href="' . $url . '">все</a><i></i></li>';

            return $str;

        }

		public static function toLower($str)
		{
			return mb_strtolower($str, 'utf-8');
		}

		public static function startProposalWord($str)
		{

			$str = mb_ereg_replace('^[\ ]+', '', $str);
			$str = mb_strtoupper(mb_substr($str, 0, 1, 'utf-8'), 'utf-8').
				mb_substr($str, 1, mb_strlen($str), 'utf-8');
			return $str;

		}


        public static function getCorrectSuffixForReview($num)
        {
            $val = $num % 100;

            if ($val > 10 && $val < 20) return $num . ' отзывов';
            else {
                $val = $num % 10;
                if ($val == 1) return $num . ' отзыв';
                elseif ($val > 1 && $val < 5) return $num . ' отзыва'; else return $num . ' отзывов';
            }
        }

		public static function toCamelCase($str)
		{
			$str = str_replace('_', ' ', $str);
			$str = ucwords($str);
			$str = str_replace(' ', '', $str);

			return $str;
		}

        public static function getPurposeOfVisitNameByPurposeOfVisitId($purpose_of_visit_id)
        {
            $purpose_of_visit_manager = new PurposeOfVisitManager();
            $purpose_of_visit =  $purpose_of_visit_manager->getOneById($purpose_of_visit_id);
            return ($purpose_of_visit) ? $purpose_of_visit->name : '';
        }

		public static function isPhoneNumber($str)
		{
			$str = StringHelper::leaveOnlyTheNumber($str);
			$str = trim($str);
			if(preg_match('/^(7|8)[0-9]{10}$/ims', $str))
			{
				return true;
			} else {
				return false;
			}
		}

		public static function leaveOnlyTheNumber($str)
		{
			return preg_replace('/[^0-9]/', '', $str);
		}

		public static function isEmail($str)
		{

		}

        public static function upperCaseFirstSymbol($name) {
            $encoding = 'utf-8';
            $result = '';
            if($name) {
                $result = mb_strtoupper(mb_substr($name, 0, 1, $encoding), $encoding);
                $result .= mb_substr($name, 1, mb_strlen($name, $encoding) - 1, $encoding);
            }

            return $result;
        }

    }