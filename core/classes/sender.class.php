<?php


    class sender
    {

        private $sourceEncoding = 'utf-8';
        private $fromName = SITE_NAME;


        public function  __construct()
        {

        }


        public function mail($to, $subject, $message)
        {
            $mail = new Phpmailer();
            $from = 'no-reply@' . $_SERVER['SERVER_NAME'];
            $mail->From = $from;
            $mail->FromName = $this->fromName;
            $mail->Subject = $subject;
            $mail->MsgHTML($message);
            $mail->AddAddress($to);
            $mail->Send();
            $mail->ClearAddresses();
        }

        public function message($to_account_id, $name, $text)
        {
            $message_manager = new MessageManager();
            $message = new MessageModel();
            $message->name = $name;
            $message->text = $text;
            $message->to_account_id = $to_account_id;
            $message->is_readed = 0;
            $message->dt = date('Y-m-d H:i:s');
            $message_manager->save($message);
        }

//    private function getHeaders()
//    {
//        $headers = '';
//        //if ($this->template['format'] == 'html'){
//        $headers .= 'MIME-Version: 1.0' . "\r\n";
//        $headers .= 'Content-type: text/html; charset="' . $this->sourceEncoding . "\"\r\n";
//        //} else {
//        //	$headers .= 'Content-type: text/plain; charset="'. $this->template['encoding'] . "\"\r\n";
//        //}
//
//        // Additional headers
//
//        $headers .= 'To: ' . $this->mailTo . "\r\n";
//        $headers .= "From: no-reply@" . $_SERVER['HTTP_HOST'] . "\r\n";
//        return $headers;
//    }


    }