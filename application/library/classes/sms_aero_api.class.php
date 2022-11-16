<?php
    class SmsAeroApi implements SmsApi
    {
        private $login;
        private $password;
        private $from;

        private $url = 'http://gate.smsaero.ru';

        public function __construct($login, $password, $from)
        {
            $this->login = $login;
            $this->password = $password;
            $this->from = $from;
        }

        public function sendMessage($number, $message)
        {
            $number = preg_replace('/[^0-9]/', '', $number);

            $url = $this->url . '/send/?user=' . $this->login .
                '&password=' . md5($this->password) .
                '&to=' . $number .
                '&text=' . urlencode($message) .
                '&from=' . urlencode($this->from);

            $request_result = CurlRequestSender::get($url);

            return $request_result;
        }

        public function getBalance()
        {
            $url = $this->url .
                '/balance/?user=' . $this->login .
                '&password=' . md5($this->password);

            $request_result = CurlRequestSender::get($url);

            return $request_result;
        }
    }