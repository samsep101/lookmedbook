<?php
    class ChildSpecialtyHelper
    {
        public static function changeFormat($specialty, $doctor)
        {
            $str = '';

            switch($specialty->for_whom)
            {
                // 1 - для всех, 2 - для взрослых, 3 - для детей
                case '1':
                    if($doctor->is_children == '1')
                    {
                        if($doctor->is_adult == '1')
                        {
                            $str .= mb_strtolower($specialty->name, 'utf-8') .', '
                                .'детский ' .mb_strtolower($specialty->name, 'utf-8');
                        }
                        else
                        {
                            $str .= 'детский ' .mb_strtolower($specialty->name, 'utf-8');
                        }
                    }
                    else
                    {
                        $str .= mb_strtolower($specialty->name, 'utf-8');
                    }
                    break;

                case '2':
                    if($doctor->is_adult == '1')
                    {
                        $str .= mb_strtolower($specialty->name, 'utf-8');
                    }
                    break;

                case '3':
                    if($doctor->is_children == '1')
                    {
                        $str .= mb_strtolower($specialty->name, 'utf-8');
                    }
                    break;
            }

            return $str;
        }
    }