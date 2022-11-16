<?php

class ExampleRegistryController extends BaseController {

    public $layout = 'home';

    public function showDoctorPage()
    {
        $doctor_id = $this->request('id');

        $doctor_manager = new DoctorManager();
        $doctor = $doctor_manager->getOneByIdOrAlias($doctor_id);

        if (!$doctor){
            ErrorPageViewHelper::page404('404');
            exit();
        }

        $this->view->doctor = $doctor;

        $doctor_review_manager = new DoctorReviewManager();
        $reviews = $doctor_review_manager->getConfirmedListByDoctorIdWithPagging($doctor->getId(), 0, 4);
        $this->view->reviews = $reviews;

        $all_reviews = $doctor_review_manager->getConfirmedListByDoctorId($doctor_id);
        $this->view->all_reviews = count($all_reviews);

        $account = ModelManagerFactory::getByName('account')->getOneById(Acc::accountId());
        $this->view->account = $account;

        $account_phone = ModelManagerFactory::getByName('account_phone')->getConfirmedOneByAccountId(Acc::accountId());
        $this->view->account_phone = $account_phone;

        $relations = ModelManagerFactory::getByName('family_relation_status')->getList();
        $this->view->relations = $relations;

        $this->view->page_title = 'Врач ' . mb_strtolower($doctor->specialties_names, 'utf-8') . ', ' . $doctor->full_name;

        $doctor_schedule_manager = new DoctorScheduleManager();
        $doctor_schedules = array();

        foreach($doctor->clinics as $clinic){
            foreach($doctor->specialties as $specialty){
                $doctor_schedules[] = $doctor_schedule_manager->getOneCurrentByDoctorIdAndClinicIdAndSpecialtyId($doctor->getId(), $clinic->id, $specialty->id);
            }
        }

        $this->view->example_page = true;

        $this->view->doctor_schedules = $doctor_schedules;
    }

    public function showClinicPage()
    {
        $clinic_id = $this->request('id');

        $clinic_manager = new ClinicManager();
        $clinic = $clinic_manager->getOneByIdOrAliasAndIsActive($clinic_id);

        if (!$clinic) {
            ErrorPageViewHelper::page404('404');
            exit();
        }

        $this->view->clinic = $clinic;

        $specialty_manager = new SpecialtyManager();
        $this->view->specialties = $specialty_manager->getRootListByClinicId($clinic->getId());

        $clinic_review_manager = new ClinicReviewManager();
        $clinic_rewies = $clinic_review_manager->getConfirmedListByClinicIdWithPagging($clinic->getId(), 0, 4);
        $this->view->clinic_reviews = $clinic_rewies;

        $all_reviews = $clinic_review_manager->getConfirmedListByClinicId($clinic->getId());
        $this->view->all_reviews = count($all_reviews);

        $this->view->page_title = $clinic->name.', '.$clinic->city->name.', '.$clinic->address;

        $this->view->example_page = true;
    }
}