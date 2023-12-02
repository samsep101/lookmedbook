<?php
    class ProductsAmountHelper
    {
        public static function wordForm($amount, $word)
        {
            $end = '';

            if($amount % 100 > 9  && $amount % 100 < 21 || $amount % 10 == 0 || $amount % 10 > 4)
            {
                $end = 'ов';
            }
            else
            {
                if($amount % 10 > 1 && $amount % 10 < 5 )
                {
                    $end = 'а';
                }
            }

            return $word .$end;
        }
    }