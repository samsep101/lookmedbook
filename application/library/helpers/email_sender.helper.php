<?php

class EmailSenderHelper
{
  public static function sendMessage($email, $subject, $message)
  {
    $message = html_entity_decode($message, ENT_COMPAT, 'UTF-8');

    $headers = "Content-type: text/html; charset=utf-8 \r\n";
    $headers .= "From: ".SITE_NAME." <no-reply@lookmedbook.ru>\r\n";
    $headers .= 'Reply-To: no-reply@lookmedbook.ru' . "\r\n";
    $headers .= 'X-Mailer: PHP/' . phpversion();

    mail($email, $subject, $message, $headers, '-fno-reply@lookmedbook.ru');
  }

  public function sendConfirmEmailMessage($mail, $passwordSequence)
  {
    require_once(ABS_ROOT . "/application/library/classes/phpmailer.class.php");
    $ml = new PHPMailer();

    $site_url = SITE_URL;
    $msgContent = <<<EOD
		<p>Для того, чтобы подтвердить свой E-mail, пройдите по ссылке приведенной ниже:
			<br />
			<a href="$site_url/account/confirmEmail?mail={$mail}&hash={$passwordSequence}&rg=1">$site_url/account/confirmEmail?mail={$mail}&hash={$passwordSequence}&rg=1</a>.
		 </p>

EOD;
    //$srvmail = 'info@lookmedbook.com';
    $to = $mail;

    $ml->From = 'no-reply';
    $ml->FromName = SITE_NAME;
    $ml->Subject = "Регистрация на ".SITE_DOMAIN;
    $ml->MsgHTML($msgContent);
    $ml->AddAddress($to);
    $ml->Send();

    $ml->ClearAddresses();
    return TRUE;
  }

  public function sendLandingConfirmEmailMessage($mail, $hash)
  {
    require_once(ABS_ROOT . "/application/library/classes/phpmailer.class.php");
    $ml = new PHPMailer();

    $site_url = SITE_URL;
    $msgContent = <<<EOD
		<p>Для того, чтобы подтвердить свой E-mail, пройдите по ссылке приведенной ниже:
			<br />
			<a href="$site_url/account/landing?hash={$hash}">$site_url/account/landing?hash={$hash}</a>.
		</p>

EOD;
    //$srvmail = 'info@lookmedbook.com';
    $to = $mail;
    $ml->From = 'no-reply';
    $ml->FromName = SITE_NAME;
    $ml->Subject = "Регистрация на ".SITE_DOMAIN;
    $ml->MsgHTML($msgContent);
    $ml->AddAddress($to);
    $ml->Send();

    $ml->ClearAddresses();
    return TRUE;
  }

  public function sendChangeEmailMessage($mail, $new_mail, $confirm_code)
  {
    require_once(ABS_ROOT . "/application/library/classes/phpmailer.class.php");
    $ml = new PHPMailer();

    $site_url = SITE_URL;
    $msgContent = <<<EOD
		<p>Отправлен запрос на изменение email c данного адреса на $new_mail<br />
            Для подтверждения смены адреса перейдите по ссылке: <a href="$site_url/account/changeEmail?from=$mail&to=$new_mail&confirm_code=$confirm_code">$site_url/account/changeEmail?from=$mail&to=$new_mail&confirm_code=$confirm_code</a>"
		 </p>

EOD;
    $ml->From = 'no-reply';
    $ml->FromName = SITE_NAME;
    $ml->Subject = "Изменение почтового адреса";
    $ml->MsgHTML($msgContent);
    $ml->AddAddress($mail);
    $ml->Send();
    $ml->ClearAddresses();
  }

  public function sendRecoveryPasswordEmail($mail, $hash)
  {
    require_once(ABS_ROOT . "/application/library/classes/phpmailer.class.php");
    $ml = new PHPMailer();

    $site_url = SITE_URL;
    $msgContent = <<<EOD
		    <p>Для того, чтобы восстановить пароль пройдите по ссылке приведенной ниже:
				<br />
				<a href="$site_url/passwordNew?mail=$mail&hash=$hash">$site_url/passwordNew?mail=$mail&hash=$hash</a>
			</p>

EOD;
    $ml->From = 'no-reply';
    $ml->FromName = SITE_NAME;
    $ml->Subject = "Восстановление пароля на ".SITE_DOMAIN;
    $ml->MsgHTML($msgContent);
    $ml->AddAddress($mail);
    $ml->Send();
    $ml->ClearAddresses();
  }

  public function sendInviteMessage($mail)
  {
    $to = $mail;
    $subject = "Вы получили приглашение на lookmedbook.ru!";
    $message = 'Вы получили приглашение на lookmedbook.ru. Теперь Вы можете авторизоваться';
    $headers = "Content-type: text/html; charset=utf-8 \r\n";
    $headers .= "From: lookmedbook.ru <no-reply@lookmedbook.ru>\r\n";

    mail($to, $subject, $message, $headers);
  }

  public function sendVisitMessage($email, $visit = null)
  {
    $to = $email;
    $subject = "Вы успешно записались на lookmedbook.ru!";
    $message = 'Вы успешно записаны на прием! Скоро мы свяжемся с Вами!';
    $headers = "Content-type: text/html; charset=utf-8 \r\n";
    $headers .= "From: lookmedbook.ru <no-reply@lookmedbook.ru>\r\n";

    mail($to, $subject, $message, $headers);
  }


    public function  sendRecordInformation($info){
        $city = SeoLinksHelper::getCityByPageLink();
        $to = 'request@lookmedbook.ru,Yudin@medcore.ru';
        $subject = 'Заявка на посещение врача №'.$info['visit_id'];


        $message = 'Пациент '.$info['full_name'].': '.$info['phone']."\r\n\r\n";

        if ($info['schedule_date'])
            $message .= 'Запись на: '.$info['schedule_date'].' '.($info['after_work'] ? 'после работы' : '').PHP_EOL;

        if ($info['doctor'])
            $message .= 'Доктор: '.$info['doctor']->full_lower_name.PHP_EOL;

        if ($info['clinic'])
            $message .= 'Клиника: '.$info['clinic']->name.PHP_EOL;

        if ($info['disease'])
            $message .= 'Заболевание: '.$info['disease']->title.PHP_EOL;

        if ($info['account'])
            $message .= 'Оператор: '.$info['account']->full_name.PHP_EOL;

        if ($city)
            $message .= 'Город: '.$city->name.PHP_EOL;



        $headers = "Content-type: text/html; charset=utf-8 \r\n";
        $headers .= "From: lookmedbook.ru <no-reply@lookmedbook.ru>\r\n";


        mail($to, $subject, $message, $headers);
	    mail('request@lookmedbook.ru', $subject, $message, $headers);
    }

  public function  sendAppealInformation($info){
        /*$city = SeoLinksHelper::getCityByPageLink();
        $to = 'karaseva1175@mail.ru,reeker14@mail.ru,myakovleva@lookmedbook.ru,Yudin@medcore.ru,kkornakova@lookmedbook.ru,lookmedbook@lookmedbook.ru';
        $subject = 'Обращение №'.$info['appeal_id'];
        $city = SeoLinksHelper::getCityByPageLink();


        $message = 'Пациент '.$info['full_name'].': '.$info['phone']."\r\n\r\n";

          if ($info['account'])
              $message .= 'Оператор: '.$info['account']->full_name.PHP_EOL;

          if ($city)
              $message .= 'Город: '.$city->name.PHP_EOL;


        $headers = "Content-type: text/html; charset=utf-8 \r\n";
        $headers .= "From: lookmedbook.ru <no-reply@lookmedbook.ru>\r\n";


        mail($to, $subject, $message, $headers);

	mail('reeker14@mail.ru', $subject, $message, $headers);
        mail('myakovleva@lookmedbook.ru', $subject, $message, $headers);
//        mail('Yudin@medcore.ru', $subject, $message, $headers);
//        mail('kkornakova@lookmedbook.ru', $subject, $message, $headers);
        mail('karaseva1175@mail.ru', $subject, $message, $headers);


        mail('hghsasha@gmail.com', $subject, $message, $headers);*/
    }

  public function sendVisitCreatedMessage($info=[])
  {
    $to = 'request@lookmedbook.ru,Yudin@medcore.ru';
    $subject = $info['id']['title'].' No:'.$info['id']['value'];
    if(isset($info['fio'])) {
      if ($info['fio']['value'] == 'Запрос на скидку')
        $subject .= ' '.$info['fio']['value'].'';
      else
        $subject .= ', пациент '.$info['fio']['value'].'';
    }
    $subject .= "\n";

    $message = $info['id']['title'].':'."\r\n\r\n";
    foreach($info as $value) {
      $message .= $value['title'].($value['title'] ? ': ' : '').$value['value']."\r\n\r\n";
    }
    $message .= "\n\n";

    $headers = "Content-type: text/html; charset=utf-8 \r\n";
    $headers .= "From: ".SITE_DOMAIN." <no-reply@".SITE_DOMAIN.">\r\n";


    mail($to, $subject, $message, $headers);
  }


    /**
     * @param VisitModel $visit
     */
    public static function sendVisitConfirmMessage($visit)
    {
        $to = 'request@lookmedbook.ru,Yudin@medcore.ru';
        $subject = 'Заявка №'.$visit->id.' подтверждена';

        $message = 'Заявка №'.$visit->id.' подтверждена'.PHP_EOL.PHP_EOL;
        $message .= 'Пациент:'.$visit->full_name.PHP_EOL;
        $message .= 'Телефон:'.$visit->phone.PHP_EOL;

        if ($visit->city)
            $message .= 'Город:'.$visit->city->name.PHP_EOL;

        if ($visit->operator)
            $message .= 'Оператор:'.$visit->operator->full_name.'('.$visit->operator->email.')'.PHP_EOL;

        if ($visit->clinic)
            $message .= 'Клиника:'.$visit->clinic->full_name.PHP_EOL;



        $message .= "\n\n";

        $headers = "Content-type: text/html; charset=utf-8 \r\n";
        $headers .= "From: ".SITE_DOMAIN." <no-reply@".SITE_DOMAIN.">\r\n";


        $emails = explode(',',$to);
        foreach ($emails as $to)
            mail($to, $subject, $message, $headers);
    }




}
