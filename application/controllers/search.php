<?php

class SearchController extends BaseController
{
    public function __construct()
    {
        parent::__construct();
    }

    public function ajaxResults()
    {
        $query = $this->request('query');
        $query = trim($query);
        if (!$query) {
            JsonResponse::error(2);
        }
        $this->layout = 'ajax';
        $this->view->cityId = $this->city->getId();

        $diseases = $this->searchDiseases($query);
        $clinics = $this->searchClinics($query);
        $doctors = $this->searchDoctors($query);
        $services = $this->searchServices($query);

        if (!$diseases && !$clinics && !$doctors && !$services) {
            JsonResponse::error(2);
        }

        $html = $this->renderInString('search/ajax-results');

        JsonResponse::result($html);
    }

    public function results()
    {
        $query = $this->request('query');
        $query = trim($query);
        if (!$query) {
            $this->redirect('searchResults', 'disease');
        }

        $diseases = $this->searchDiseases($query);
        if ($diseases) {
            $this->redirectUrl(DiseasePageLinkViewHelper::getLink($diseases[0]));
        }
        $clinics = $this->searchClinics($query);
        if ($clinics) {
            $this->redirectUrl(ClinicPageLinkViewHelper::getLink($clinics[0]));
        }
        $doctors = $this->searchDoctors($query);
        if ($doctors) {
            $this->redirectUrl(DoctorPageLinkViewHelper::getLink($doctors[0]));
        }
        $services = $this->searchServices($query);
        if ($services) {
            $this->redirectUrl(ServicePageLinkViewHelper::getLink($services[0], $this->city->getId()));
        }

        $this->redirect('searchResults', 'disease');
    }

    /**
     * @param string $query
     *
     * @return DiseaseModel[]
     * @throws Exception
     */
    protected function searchDiseases($query)
    {
        $diseaseManager = new DiseaseManager();
        $diseases = $diseaseManager->getActiveListByTitleOrAltName($query, 5, 1);

        $this->view->diseases = $diseases;

        return $diseases;
    }

    /**
     * @param string $query
     *
     * @return ServiceCategoryModel[]
     * @throws Exception
     */
    protected function searchServices($query)
    {
        $serviceCategoryManager = new ServiceCategoryManager();
        $services = $serviceCategoryManager->getActiveListByTitleOrAltName($query, 5);

        $this->view->services = $services;

        return $services;
    }

    /**
     * @param string $query
     *
     * @return ClinicModel[]
     * @throws Exception
     */
    protected function searchClinics($query)
    {
        $params = ClinicSearchHelper::initClinicSearchParams($this->request, $this->city->id);
        $params->clinic_name = $query;
        $params->by_page = 5;
        $clinicSearchAlgorithm = new ClinicSearchAlgorithm();
        $clinicSearchAlgorithm->setIsSearchNearestAllowed(false);
        $clinics = $clinicSearchAlgorithm->search($params);

        if ($clinicSearchAlgorithm->getGoodSearchFlag() === false) {
            $clinics = [];
        }

        $this->view->clinics = $clinics;

        return $clinics;
    }

    /**
     * @param string $query
     *
     * @return DoctorModel[]
     * @throws Exception
     */
    protected function searchDoctors($query)
    {
        $params = DoctorSearchHelper::initDoctorSearchParams($this->request, $this->city->id);
        $params->doctor_name = $query;
        $params->by_page = 5;
        $doctorSearchAlgorithm = new DoctorSearchAlgorithm();
        $doctorSearchAlgorithm->setIsSearchNearestAllowed(false);
        $doctors = $doctorSearchAlgorithm->search($params);

        $this->view->doctors = $doctors;

        return $doctors;
    }
}