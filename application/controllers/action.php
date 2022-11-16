<?php

class ActionController extends BaseController
{
    public function __construct() {

        //RedirectManager::redirect301('http://cashback.lookmedbook.ru/');
    }

    function get()
    {
        $id = $this->request('id');
        $action = (new ActionManager())->getOneByIdOrAlias($id);
        if (!$action) {
            ErrorPageViewHelper::page404('404');
        }
        if (strtolower($id) !== $id) {
            RedirectManager::redirect301(AliasLinkViewHelper::getLink('action', $action));
        }
        $this->view->action = $action;
        $this->view->page_title = $action->name;
        $this->view->page_description = "$action->name - акция на " . SITE_NAME . "!";

    }

    public function index()
    {
        $actions = (new ActionManager())->getList();
        $actions=array_reverse($actions);
        $this->view->actions = $actions;
        $this->view->page_title = 'Акции ' . SITE_NAME;
        $this->view->page_description = SITE_NAME . " – список акций.";
    }
}
