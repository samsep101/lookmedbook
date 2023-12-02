<?php
    class StringGeneratorHelper
    {
        public static function   generate($lenght) {
            $chars = 'abdefhiknrstyzABDEFGHKNQRSTYZ23456789'; // набор символов
            $count_chars = strlen ($chars);  // длина строки символов
            $string = '';
            for ($i=0; $i<$lenght; $i++) { 
                $rand = rand (0,$count_chars-1); // генерируем случайное число от 1 до числа равному длине строки набора символов            
                $string .= substr ($chars, $rand, 1); //возвращаем строку длиной 1 символ
            }  
            
            return $string;
        }

        public static function   generateNumbers($lenght) {
            $chars = '123456789'; // набор символов
            $count_chars = strlen ($chars);  // длина строки символов
            $string = '';
            for ($i=0; $i<$lenght; $i++) {
                $rand = rand (0,$count_chars-1); // генерируем случайное число от 1 до числа равному длине строки набора символов
                $string .= substr ($chars, $rand, 1); //возвращаем строку длиной 1 символ
            }

            return $string;
        }
    }