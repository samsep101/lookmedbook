<?php

class ClinicGroupController extends BaseController
{
    protected static $settings = [
        'common' => [
            'clinicsSearchErrorMessage' => 'По Вашему запросу ничего не найдено',
        ],
        'federal-medical-centers' => [
            'page_title' => 'Ведущие федеральные медицинские центры',
            'page_description' => 'Ведущие федеральные медицинские центры',
            'topPhoneText' => 'Запишитесь в ведущее медучреждение страны.<br />Узнайте стоимость лечения по
телефону ',
            'phone' => '+7 (495) 215-09-27',
            'topPhoneFooter' => 'Госпитализация в день обращения. Звоните!',
            'hidePhone' => true,
        ],
    ];

    public function index($group = 'federal-medical-centers')
    {
        $groupSettings = static::$settings[$group];

        $searchParams = new ClinicSearchParams();
        $searchParams->top_phone = $groupSettings['phone'];
        $searchAlgorithm = new ClinicSearchAlgorithm();
        $clinics = $searchAlgorithm->search($searchParams);

        $this->view->page_title = $groupSettings['page_title'];
        $this->view->page_description = $groupSettings['page_description'];
        $this->view->topPhoneText = $groupSettings['topPhoneText'];
        $this->view->topPhone = $groupSettings['phone'];
        $this->view->hidePhone = $groupSettings['hidePhone'];
        $this->view->topPhoneFooter = $groupSettings['topPhoneFooter'];
        $this->view->clinics = $clinics;
        $this->view->clinicsSearchErrorMessage = static::$settings['common']['clinicsSearchErrorMessage'];

        $this->render('clinic_group/index');
    }
}
