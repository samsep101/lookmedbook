<?php

class ExampleController extends BaseController
{
  public $layout = 'home';

  public function index()
  {
    $this->view->page_title = 'Узнать больше - «' . SITE_NAME . '»';
    $this->render('example/show_cards');
  }

}