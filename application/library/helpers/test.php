<?php


DEFINE("ABS_ROOT", "/var/www/");

require("email_sender.helper.php");

  EmailSenderHelper::sendVisitConfirmMessage("relay.samsep101@gmail.com");
//new_mail(["relay.samsep101@gmail.com"], 'Заявка на посещение врача номер 1001231230', 'пукпу');
