<?php


    class Logger
    {

        private $code = '';
        private $template;
        private $sourceEncoding = 'utf-8';
        private $assignedValues;
        private $mailTo;
        private $flags;

        public function  __construct($flags, $code, $to, $assignedValues = NULL)
        {
            $this->flags = $flags;
            $this->code = $code;
            $this->mailTo = $to;
            $this->assignedValues = $assignedValues;
        }

        public function assign($assignedValues)
        {
            $this->assignedValues = $assignedValues;
        }

        public function send()
        {
            if (count($this->template)) {
                $this->parseTemplate();

                if (!empty($this->template['logTpl'])) {
                    $db = Register::get('db');

                    $sql = 'INSERT INTO ' . DB_PREFIX . 'log SET user_id =' . (int)Acl::userId() . ', dt="' . date("y-m-d H:i:s") . '", `log`="' . htmlspecialchars(strip_tags($this->template['logTpl'])) . '"';
                    $db->query($sql);
                }

                //Если пользователь создал не для себя, известить
                if ($this->mailTo != Acl::userLogin()) {

                    if (Acl::userSystemMessaging()) {
                        if (!empty($this->template['emailTitle']) && !empty($this->template['emailTpl'])) {
                            $headers = $this->getHeaders();
                            $to = $this->mailTo;
                            $subject = $this->template['emailTitle'];
                            $message = $this->template['emailTpl'];

                            $mail = new Phpmailer();
                            $from = 'no-reply@' . $_SERVER['SERVER_NAME'];
                            $fromname = 'GoraDel.com';
                            $mail->From = $from;
                            $mail->FromName = $fromname;
                            $mail->Subject = $subject;
                            $mail->MsgHTML($message);
                            $mail->AddAddress($to);
                            $mail->Send();
                            $mail->ClearAddresses();
                        }
                    }

                    if (Acl::userSMSMessaging()) {
                        if (!empty($this->template['smsTpl'])) {
                            $user = UserModel::getUserByLogin($this->mailTo);
                            if (count($user)) {
                                $user = $user[0];
                                $phone = $user['phone'];
                                $phone = preg_replace('/\D/', '', $phone);
                                if (strlen($phone) == 12) {
                                    send_sms('+' . $phone, $this->template['smsTpl'], 0, 0, 0, 0, 'GoraDel');
                                }
                            }
                        }
                    }
                }

            }
        }

        private function parseTemplate()
        {
            $assing = array();

            $role = RoleManager::getUserRole(Acl::userId());
            $role = $role[0];
            $assing['{user}'] = Acl::userLastName() . ' ' . Acl::userName() . ' ' . Acl::userMiddleName() . ' (' . $role['name'] . ')';
            $assing['{host}'] = $_SERVER['SERVER_NAME'];
            foreach ($this->assignedValues as $key=> $value) {
                $assing['{' . $key . '}'] = $value;
            }
            foreach ($this->template as $key=> $value) {
                $this->template[$key] = str_replace(array_keys($assing), $assing, html_entity_decode($value));

            }

        }

        private function getHeaders()
        {
            $headers = '';
            //if ($this->template['format'] == 'html'){
            $headers .= 'MIME-Version: 1.0' . "\r\n";
            $headers .= 'Content-type: text/html; charset="' . $this->template['encoding'] . "\"\r\n";
            //} else {
            //	$headers .= 'Content-type: text/plain; charset="'. $this->template['encoding'] . "\"\r\n";
            //}

            // Additional headers

            $headers .= 'To: ' . $this->mailTo . "\r\n";
            $headers .= "From: no-reply@" . $_SERVER['HTTP_HOST'] . "\r\n";
            return $headers;
        }

        private function changeTemplateEncoding()
        {
            foreach ($this->template as $key=> $value)
                $this->template[$key] = iconv($this->sourceEncoding, $this->template['encoding'], $value);
        }


    }