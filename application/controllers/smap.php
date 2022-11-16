<?php

class smapController extends BaseController
{
    
    public function index() {
        RedirectManager::redirect301('/sitemap');
    }
}