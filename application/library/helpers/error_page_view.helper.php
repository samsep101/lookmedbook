<?php
class ErrorPageViewHelper {

    public static function page404()
    {
		PhpHeaderHelper::status404();

        $controller = new Controller();
        $controller->layout = 'home';
        $controller->view = new View();
        //$controller->view->city =  SeoLinksHelper::getCityByPageLink();
        $current_account = ModelManagerFactory::getByName('account')->getOneById(Acc::accountId());
        $controller->view->current_account = $current_account;
		$controller->view->city = SeoLinksHelper::getCityByPageLink();
        $controller->view->product_basket = ProductBasketFactory::getInstance();

        $controller->render('404');
        exit();
    }

    public static function pageCsrfError()
    {
        PhpHeaderHelper::status423();

        $controller = new Controller();
        $controller->layout = 'home';
        $controller->view = new View();
        $controller->view->current_account = ModelManagerFactory::getByName('account')->getOneById(Acc::accountId());
        $controller->view->city = SeoLinksHelper::getCityByPageLink();

        $controller->render('csrf_error');
        exit();
    }

}