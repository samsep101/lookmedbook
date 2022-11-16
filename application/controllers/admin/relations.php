<?php

class relationsAdminController extends AdminBaseController {
    /** @author Playmore 2017 (playmoredevelop@gmail.com) */

    public function index() {

        $action = $this->request('action', false);

        if($action AND method_exists($this, $action)){

            return call_user_func([$this, $action]);
        }
    }

    public function service_to_clinic() {

        /*array (
            'action' => 'clinic',
            'linkto' => '1',
            'selected' => '385',
          )*/
        $linkto = $this->request('linkto', false);
        $selected = $this->request('selected', false);
        
        if($linkto AND $selected){
            
            $iid = $this->relations()->addServiceToClinic($linkto, $selected);

            if($iid) {

                return $this->json(['do' => 'reload']);
            }

            return $this->json(['error' => 'Связь уже существует']);
        }

        return $this->json(['error' => 'Не все параметры переданы']);
    }

    public function service_to_clinic_delete() {

        $linkto = $this->request('linkto', false);
        $clinic_id = $this->request('clinicId');

        if($linkto AND $clinic_id){

            $this->relations()->removeServiceToClinic($linkto, $clinic_id);
        }

        return $this->json(['success' => 'Связь удалена!', 'do' => 'reload']);
    }

    public function service_to_clinic_auto_relations() {

        $linkto = $this->request('linkto', false);
        $specID = $this->request('specID', false);

        $count = 0;

        if($linkto AND $specID){

            $clinics = $this->relations()->getClinicsBySpecialization($specID);

            if(!empty($clinics)){

                $exists = $this->relations()->getServicesRelationsClinics($linkto);

                foreach($clinics as $clinicID){

                    if( ! in_array($clinicID, $exists)){

                        $this->relations()->addServiceToClinic($linkto, $clinicID, false);

                        $count++;
                    }
                }

                return $this->json(['success' => 'Установлено связей: '.$count, 'do' => 'reload']);
            }
            return $this->json(['error' => 'Клиники не найдены']);
        }
        
        return $this->json(['error' => 'Не все параметры переданы']);
    }

    public function services_categories_clear() {

        $linkto = $this->request('linkto', false);

        $this->relations()->clearServicesRelations($linkto);

        return $this->json(['do' => 'reload']);
    }

    public function services_to_doctor() {

        $linkto = $this->request('linkto', false);
        $selected = $this->request('selected', false);
        $unlinkAll = boolval($this->request('unlinkAll', false));

        if($linkto AND !empty($selected) AND is_array($selected)){

            $this->relations()->addServiceToDoctor($linkto, $selected);

            return $this->json(['do' => 'reload']);
        }

        if($linkto AND $unlinkAll === true AND empty($selected)){

            $this->relations()->unlinkAllDoctors($linkto);

            return $this->json(['do' => 'reload']);
        }

        return $this->json(['error' => 'Не все параметры переданы']);
    }

    public function services_to_doctor_delete() {

        $linkto = $this->request('linkto', false);
        $doctor_id = $this->request('doctorId');

        if($linkto AND $doctor_id){

            $this->relations()->removeServiceToDoctor($linkto, $doctor_id);
        }

        return $this->json(['success' => 'Связь удалена!', 'do' => 'reload']);
    }

    public function beforeAction() {
        return true;
    }

    /**
     * @staticvar RelationsSimpleModel $model
     * @return RelationsSimpleModel
     */
    protected function relations() {
        
        static $model = null;
        
        if(is_null($model)){
            
            require_once ABS_ROOT.'/application/models/relations.simplemodel.php';
            $model = new RelationsSimpleModel();
        }

        return $model;
    }

    protected function json(array $data) {

        header('Content-Type: application/json');
        exit(json_encode($data));
    }
}

/* END CLASS: relationsAdminController extends AdminBaseController */