<?php

    class SpecializationHelper
    {
        public static function getSpecializationWordForm($count)
        {
            if($count % 100 > 1 || $count % 10 > 1)
            {
                $result = 'специализациям';
            }
            else
            {
                $result = 'специализации';
            }

            return $result;
        }
    }