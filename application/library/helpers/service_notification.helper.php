<?php

class ServiceNotificationHelper
{

	public static function emailNotification($text, $subject, $purpose = false)
	{
        $headers = "Content-type: text/html; charset=utf-8 \r\n";
        $headers .= 'From: '.SITE_NAME.' <no-reply@lookmedbook.ru>'."\r\n";
        $headers .= 'Reply-To: no-reply@lookmedbook.ru' . "\r\n" ;
        $headers .= 'X-Mailer: PHP/' . phpversion();

        $text = html_entity_decode($text,ENT_COMPAT,'UTF-8');

        if ($purpose)
            $email_list = ModelManagerFactory::getByName('service_email')->getListByPurpose($purpose);
        else
            $email_list = ModelManagerFactory::getByName('service_email')->getList();

        if ($email_list) {
            foreach ($email_list as $email) {
                mail($email->email, $subject, $text, $headers, '-fno-reply@lookmedbook.ru');
            }
        }
	}

	public static  function phoneNotification($text, $purpose)
	{
		$api = SmsApiFactory::getSmsApi();

		$phone_list = ModelManagerFactory::getByName('service_phone')->getListByPurpose($purpose);
		if ($phone_list) {
			foreach ($phone_list as $phone) {
				$number = str_replace('+', '', $phone->phone);
				$api->sendMessage($number, $text);
			}
		}
	}
}