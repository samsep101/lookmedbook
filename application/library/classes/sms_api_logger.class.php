<?php
    class SmsApiLogger implements SmsApi
    {
        public function sendMessage($number, $message)
        {
            $f = fopen(ABS_ROOT . '/media/logs/sms/'.date('Y-m-d').'.txt', 'a+');

            fwrite($f, $number.' '.$message);
            fclose($f);
        }

    }