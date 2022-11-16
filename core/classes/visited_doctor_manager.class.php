<?php
    class VisitedDoctorManager extends CustomModelManager
    {
        protected $model_name = 'VisitedDoctorModel';

        public function getListByPastVisitSearchParams(PastVisitSearchParams $params)
        {
            $sql = 'SELECT  DISTINCT d.*
                    FROM doctor d
                    INNER JOIN visit v ON v.doctor_id = d.id
                    INNER JOIN schedule s ON v.schedule_id = s.id
                    ';

            if ($params->specialty_id)
            {
                $sql .= 'INNER JOIN doctor_to_clinic d2c ON d2c.doctor_id = d.id ';
            }


            $sql .= ' WHERE v.account_id = '.(int)$params->account_id;

            if ($params->specialty_id)
            {
                $sql .= '   AND d2c.clinic_id = s.clinic_id
                            AND d2c.specialty_id = '.(int)$params->specialty_id;
            }

            if ($params->purpose_of_visit_id)
            {
                $sql .= ' AND v.purpose_of_visit_id = '.(int)$params->purpose_of_visit_id;
            }

            if ($params->clinic_id)
            {
                $sql .= ' AND v.clinic_id = '.(int)$params->clinic_id;
            }

            $sql .= ' AND v.status_id = '.VisitModel::VISITED;
            $sql .= ' AND v.visit_start_time < NOW() ';
            $sql .= ' ORDER BY v.visit_start_time DESC ';

            if ($params->limit)
                $sql .= ' LIMIT '.(int)$params->offset.', '.$params->limit;

            $data = Register::get('db')->query($sql);
            //echo $sql;
            return $this->initList($data);
        }
    }