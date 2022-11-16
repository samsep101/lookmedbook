<?php
    class ApiLogger
    {
        public static function log()
        {
            $request = serialize($_REQUEST);

            $message = date('Y-m-d H:i:s').": \t".(empty($_REQUEST['REMOTE_ADDR'])?'no-IP':$_REQUEST['REMOTE_ADDR']).':  '.(empty($_REQUEST['REQUEST_URI'])?'no-URI':$_SERVER['REQUEST_URI']);
            $message .= "\t ".$request."\r\n\r\n-------------------\r\n\r\n";

            $f = fopen(ABS_ROOT . '/media/logs/api/'.date('Y-m-d').'.txt', 'a+');
            fwrite($f, $message);

            fclose($f);
        }
    }