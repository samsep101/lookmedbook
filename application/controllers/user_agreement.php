<?php

class UserAgreementController extends BaseController
{
    public function index()
    {
        $this->view->page_title = 'Пользовательское соглашение';
        $this->view->page_description = 'Пользовательское соглашение';
        $this->render('user_agreement/index');
    }

    public function privacyPolicy()
    {
        $this->view->page_title = 'Согласие на обработку персональных данных';
        $this->view->page_description = 'Согласие на обработку персональных данных';
        $this->render('user_agreement/privacy_policy');
    }
}
