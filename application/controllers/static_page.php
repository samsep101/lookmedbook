<?php

class StaticPageController extends BaseController
{
    public function medicina4prc()
    {
        $this->render('static_page/medicina4prc');
    }

    public function contacts()
    {
        $this->view->page_title = 'Контакты';
        $this->render('static_page/contacts');
    }

    public function kartoteka()
    {
        $this->view->page_title = 'Картотека';
        $this->render('static_page/kartoteka');
    }
}
