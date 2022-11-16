<?php
	/*
	 * Контроллер служит для установки основной страницы регистратуры
	 */
    class IndexRegistryController extends Controller
    {
        public function index()
        {
			RedirectManager::redirect('/registry/clinic/information');
        }
    }