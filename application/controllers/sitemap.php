<?php

class sitemapController extends BaseController
{
    public $layout = 'home';
    
    public function index() {
        $this->view->page_title = 'Карта сайта '.SITE_NAME;
        $this->view->page_description = 'Посмотрите подробную карту сайта  '.SITE_NAME;
        $specialtyManager = new SpecialtyManager();
        $this->view->doctorSpecialties = $specialtyManager->getHavingDoctorsListByCityId($this->city->id);;

        $specializationManager = new SpecializationManager();


        $this->view->clinicSpecializations = $specializationManager->getSpecializationForCityIDInWhichHaveDoctors($this->city->id);
        $this->render('sitemap/index');
    }

    public function doctorsByArea()
    {
        $specialtyId = $this->request('specialty_id');
        $specialtyManager = new SpecialtyManager();
        $specialty = $specialtyManager->getOneById($specialtyId);
        if (!$specialty) {
            ErrorPageViewHelper::page404();
        }

        $this->view->specialty = $specialty;
        $this->view->page_title = 'Карта сайта '.SITE_NAME;
        $this->view->page_description = 'Посмотрите подробную карту сайта  '.SITE_NAME;

        $this->view->specialty=$specialty;
        $doctor_search_params = new DoctorSearchParams();
        $doctor_search_params->city_id=$this->city->getId();

        $districtManager = new DistrictManager();
        $districts = $districtManager->getHavingDoctorsListBySpecialtyIdAndCityId($specialtyId, $this->city->id);

        $regionManager = new RegionManager();

        $regionsWithDistricts = [];
        foreach ($districts as $district) {
            $regions = $regionManager->getHavingDoctorsListBySpecialtyIdAndDistrictId($specialtyId, $district->id);
            $regionsWithDistricts[] = [
                'regions' => $regions,
                'district' => $district
            ];
        }

        $this->view->regionsWithDistricts = $regionsWithDistricts;

        $metroManager = new MetroStationManager();
        $this->view->metroStations = $metroManager->getHavingDoctorsListBySpecialtyIdAndCityId($specialtyId, $this->city->getId());
        $this->render('sitemap/doctor_area');
    }


    public function clinicsByArea()
    {
        $specializationId = $this->request('specialization_id');
        $specializationManager = new SpecializationManager();
        $specialization = $specializationManager->getOneById($specializationId);
        if (!$specialization) {
            ErrorPageViewHelper::page404();
        }

        $this->view->specialization = $specialization;
        $this->view->page_title = 'Карта сайта '.SITE_NAME;
        $this->view->page_description = 'Посмотрите подробную карту сайта  '.SITE_NAME;

        $this->view->specialty = $specialization;
        $doctor_search_params = new DoctorSearchParams();
        $doctor_search_params->city_id = $this->city->getId();

        $districtManager = new DistrictManager();
        $districts = $districtManager->getHavingClinicListBySpecializationIdAndCityId($specializationId, $this->city->id);

        $regionManager = new RegionManager();

        $regionsWithDistricts = [];
        foreach ($districts as $district) {
            $regions = $regionManager->getHavingClinicListBySpecializationIdAndDistrictId($specializationId, $district->id);
            $regionsWithDistricts[] = [
                'regions' => $regions,
                'district' => $district
            ];
        }

        $this->view->regionsWithDistricts = $regionsWithDistricts;

        $metroManager = new MetroStationManager();
        $this->view->metroStations = $metroManager->getHavingClinicListBySpecializationIdAndCityId($specializationId, $this->city->getId());

        $this->render('sitemap/clinic_area');
    }
}