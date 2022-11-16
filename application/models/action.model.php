<?php

/**
 * @property int $id
 * @property ClinicModel $clinic
 * @property string $name
 * @property string $date_from
 * @property string $date_to
 * @property int $image_id
 * @property ImageModel $image
 * @property int $image_full_width_id
 * @property ImageModel $image_full_width
 * @property string $info
 * @property int $clinic_id
 *
 */
class ActionModel extends DynamicModel {
    private $specializations = [];

    public function get_image_full_width()
    {
        if (!$this->image_full_width_id)
            return false;

        return (new ImageManager())->getOneById($this->image_full_width_id);
    }

    public function getLink()
    {
        return '/action/'.$this->alias.'';
    }

    private function loadSpecializations(){
        if (!$this->getId())
            return false;

        $q = "select specialization.* 
              from action_to_specialization 
              left join specialization on (specialization.id = action_to_specialization.specialization_id)
              where action_to_specialization.action_id='".$this->getId()."' ";

        $specializations = (new SpecializationManager())->getListByQuery($q);
        $this->specializations = [];
        foreach ($specializations as $e){
            $this->specializations[$e->getId()] = $e;
        }

    }

    public function hasSpecialization($specialization_id){
        $this->specializations ? '' : $this->loadSpecializations();
        return isset($this->specializations[$specialization_id]);
    }
    
}