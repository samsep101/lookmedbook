<?php
    interface SmsApi {

        public function sendMessage($number, $message);
    }