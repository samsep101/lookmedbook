<?php

class oneClickSubscribeController extends BaseController
{
    public $layout = 'home';
    public function get()
    {
        $landing = $this->request('landing');
        $recording = $this->request('recording');
        die('ddd');
        $this->view->landing_page = $landing;
        $this->view->recording = $recording;
    }
    
    public function index() {
        $this->view->landing_page = $landing;

        $specialty_manager = ModelManagerFactory::getByName('specialty');
        $s=$this->city->getId();
        $this->view->specialties = $specialty_manager->getHavingDoctorsListByCityId($s);
        $this->view->page_title = 'Запись к врачу '.SITE_NAME;
        $this->view->page_description = 'Подберем лучшего профессионала дерматовенеролога, гинеколога, уролога по цене  '.SITE_NAME;
        $this->render('oneclicksubscribe/index');
    }
    
    public function showDoctors() {

        $this->view->landing_page = $landing;

        $this->view->page_title = 'Карта сайта '.SITE_NAME;
        $this->view->page_description = 'Посмотрите подробную карту сайта  '.SITE_NAME;
        $location=$this->request('location');
        if ($location)
            $this->view->location=$location;
        $specialty=$this->request('specialty');
        if ($specialty) {
            $this->view->specialty=$specialty;
        }
 
        $doctor_search_params = new DoctorSearchParams();  
        $doctor_search_algorithm = new DoctorSearchAlgorithm();
        $doctor_search_params->city_id=$this->city->getId();
        $p=$this->request('p');
        if ($p=='na-dom') {
            $doctor_search_params->visit_type='home';
            $this->view->visit_type='home';
        }
        if ($p=='detskie') {
            $doctor_search_params->doctor_type='children';
            $this->view->doctor_type='children';
        }
        $this->view->p=$p;
            
        //получаем округи и районы
        $district = new DistrictManager();
        $districts=$district->getListByCityId($this->city->getId());
        if ($districts) {
            $region = new RegionManager();
            foreach ($districts as $d) {
                if ($d->alias==$location) {
                    $doctor_search_params->district_id=$district->getOneByAlias($location)->getId();
                    $this->view->location_id=$d->id;
                    $this->view->location_name=$d->name;
                    $this->view->location_type='district';
                }
               $regions = $region->getListByDistrictId($d->getId());
               if ($regions) {
                   foreach($regions as $r) {
                       $regionsList[]=array('name'=>$r->name,'alias'=>$r->alias);
                       if ($r->alias==$location) {
                         $doctor_search_params->region_id=$region->getOneByAlias($location)->getId();
                         $this->view->location_id=$r->id;
                         $this->view->location_name='район '.$r->name;
                         $this->view->location_type='region';
                       }
                   }
               }
               $districtsList[]=array('name'=>$d->name,'alias'=>$d->alias,'regions'=>$regionsList);
            }    
            asort($regionsList);
            asort($districtsList);
        }
        
        //метро
        $metro_stations = new MetroStationManager();
        $stations = $metro_stations->getHavingDoctorsListByCityId($this->city->getId());
        if ($stations) {
            foreach($stations as $s) {
                $metroStationsList[]=array('name'=>$s->name,'alias'=>$s->alias);
                if ($s->alias==$location) {
                    $doctor_search_params->metro_station_id=$metro_stations->getOneByAlias($location)->getId();
                    $this->view->location_id=$s->id;
                    $this->view->location_name='метро '.$s->name;
                    $this->view->location_type='metro';
                }
            }
            asort($metroStationsList);
        }

        //улицы
        $streetManager = new StreetManager();
        $streets = $streetManager->getHavingDoctorsListByCityId($this->city->getId());
        if ($streets) {
            foreach($streets as $s) {
                $streetsList[]=array('name'=>$s->prefix.' '.$s->name,'alias'=>$s->alias);
                if ($s->alias==$location) {
                    $doctor_search_params->street_id=$streetManager->getOneByAlias($location)->getId();
                    $this->view->location_id=$s->id;
                    $this->view->location_name=$s->prefix.' '.$s->name;
                    $this->view->location_type='street';
                }
            }
            //asort($streetsList);
        }
        
        //специальности
        $specialtyManager = new SpecialtyManager();
        if ($this->view->location_type=='district')
            $specialities = $specialtyManager->getHavingDoctorsListByDistrictId($this->view->location_id);
        else if ($this->view->location_type=='region')
            $specialities = $specialtyManager->getHavingDoctorsListByRegionId($this->view->location_id);
        else if ($this->view->location_type=='metro')
            $specialities = $specialtyManager->getHavingDoctorsListByMetroStationId($this->view->location_id);
        else if ($this->view->location_type=='street')
            $specialities = $specialtyManager->getHavingDoctorsListByStreetId($this->view->location_id);
        else
             $specialities = $specialtyManager->getHavingDoctorsListByCityId($this->city->getId());
               
        if ($specialities) {
            foreach($specialities as $s) {
                $doctor_search_params->specialty_id=$s->id;
                if ($specialty==$s->alias) {
                    $this->view->specialty_id=$s->id;
                    $this->view->specialty_name=$s->name;
                }
                $doctors = $doctor_search_algorithm->search($doctor_search_params);
                $doctorsList=array();
                foreach($doctors as $doc) {
                    if ($doc->last_name!='' && $doc->first_name!='' && $doc->second_name!='') {
                    $doctorsList[]=array('name'=>$doc->last_name.' '.$doc->first_name.' '.$doc->second_name,'alias'=>$doc->alias);
                    }
                }
                asort($doctorsList);
                //if (count($doctorsList)>0)
                $specialitiesList[$s->id]=array('name'=>$s->name,'alias'=>$s->alias,'doctors'=>$doctorsList);
            }
            //asort($streetsList);
        }
        $this->view->streetsList = $streetsList;
        $this->view->regionsList = $regionsList;
        $this->view->districtsList = $districtsList;
        $this->view->metroStationsList = $metroStationsList;
        $this->view->specialitiesList = $specialitiesList;
        $this->render('sitemap/doctors');    
        
    }
    
}