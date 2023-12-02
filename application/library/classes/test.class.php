<?php
    class Test {
        
        public static function dump($var, $exit = true)
        {
            echo '<pre>';
            print_r($var);
            echo '</pre>';
            
            if ($exit) exit();
        }
    }