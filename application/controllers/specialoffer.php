<?php

class specialofferController extends BaseController
{
    public $layout = 'layouts/landing';
    
    public function index() {
        $this->view->page_title = 'Запись к врачу '.SITE_NAME;
        $this->view->page_description = 'Подберем лучшего профессионала дерматовенеролога, гинеколога, уролога по цене  '.SITE_NAME;
        $this->view->formInfoContent = '';
        if ($this->request->isPost()) {
            $specialty = $this->request('specialty');
            $phone = $this->request('phone');
            if ($specialty && $phone) {
                $specialty = htmlentities($specialty);
                $phone = htmlentities($phone);

                require_once(ABS_ROOT . "/application/library/classes/phpmailer.class.php");
                $ml = new PHPMailer();
                $message = "Телефон: $phone<br />";
                $message .= "Врач: $specialty<br />";
                $message .= "Время заявки: " . date('d.m.Y H:i:s');
                $ml->From = 'no-reply@' . SITE_DOMAIN;
                $ml->FromName = SITE_NAME;
                $ml->Subject = 'Заявка со страницы SpecialOffer';
                $ml->MsgHTML($message);
                $ml->AddAddress('carelookmed@yandex.ru');
                $ml->Send();
                $ml->ClearAddresses();

                $this->redirect( 'index','specialoffer', 'success=true');
            }
        }
        if ($this->request('success')) {
            $this->view->formInfoContent = '<div class="success">Заявка успешно отправлена</div>';
        }
        $this->render('specialoffer/index');
    }
}