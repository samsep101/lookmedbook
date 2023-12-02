<?php

/**TODO
 * add data description
 * Class ActionManager
 */
class ActionManager extends AliasManager
{
    protected $table_name = 'action';
    protected $model_name = 'ActionModel';

    public function getListForDisease($disease_id)
    {
        $q = "select action.*
                from specialty_to_disease
                left join specialty_to_specialization on (specialty_to_specialization.specialty_id = specialty_to_disease.specialty_id)
                left join action_to_specialization on (action_to_specialization.specialization_id = specialty_to_specialization.specialization_id)
                left join `action` on (`action`.id = action_to_specialization.action_id)
                where specialty_to_disease.disease_id = '$disease_id' and not action.id is null
                group by action.id";

        $db = Register::get('db');
        $data = $db->query($q);

        return (count($data)) ? $this->initList($data) : array();
    }

    public function getListForClinic($clinic_id)
    {
        $db = Register::get('db');
        
        $q = "select action.*
              from action
              where clinic_id='$clinic_id' ";
			  
        $data = $db->query($q);

        return (count($data)) ? $this->initList($data) : array();
    }

    public function getActionsByDoctorId($doctor_id){
        $sql = '  select a.*
                    from doctor d
                   inner join doctor_to_clinic dc on dc.doctor_id=d.id
                   inner join specialty_to_doctor sd on (sd.doctor_id=d.id and sd.clinic_id=dc.clinic_id)
                   inner join specialty_to_specialization ss on (ss.specialty_id=sd.specialty_id)
                   inner join specialization s on (s.id=ss.specialization_id)
                   inner join `action` a on (a.clinic_id=dc.clinic_id and now() between a.date_from and a.date_to)
                   inner join action_to_specialization asp on (asp.action_id=a.id and asp.specialization_id=s.id)
                   where d.id='.(int)$doctor_id;
        $data = $this->db->query($sql);
        return (isset($data)) ? $this->initList($data) : array();
    }

}