<?php

class RelationsSimpleModel extends SimpleModel {
    /** @author Playmore 2017 (playmoredevelop@gmail.com) */

    public function addServiceToClinic($service_id, $clinic_id, $check_exists = true) {

        if($check_exists){

            $exists = $this->getServicesRelationsClinics($service_id);

            if(!in_array($clinic_id, $exists)) {
                $result = $this->insert('service_to_clinic', [
                    'service_id' => (int)$service_id,
                    'clinic_id' => (int)$clinic_id,
                    'price' => 0,
                ]);
                ElasticaTask::indexClinic($clinic_id);
                return $result;
            }

            return false;
        }

        $result = $this->insert('service_to_clinic', [
            'service_id' => (int)$service_id,
            'clinic_id' => (int)$clinic_id,
            'price' => 0,
        ]);
        ElasticaTask::indexClinic($clinic_id);
        return $result;
    }

    public function getServicesRelationsClinics($services_ids) {

        is_array($services_ids) AND $services_ids = implode(', ', array_map('intval', $services_ids));

        // SELECT * FROM `service_to_clinic` s2c INNER JOIN services_categories sc ON s2c.services_categories_id = sc.id WHERE sc.id = 1
        $q = str_replace(['{ids}'], [
            $services_ids
        ], 'SELECT clinic_id
            FROM `service_to_clinic` s2c
            INNER JOIN service_category sc ON s2c.service_id = sc.id
            WHERE sc.id IN ({ids})');

        return $this->fetch_column($q, 'clinic_id') ?: [];
        
    }

    public function getSelect($table, $fkeys, $fvalues) {

        $data = $this->get_orderby($table, [$fkeys, $fvalues], $fvalues.' ASC');

        $assoc = [];
        foreach($data as $row){
            $assoc[$row[$fkeys]] = $row[$fvalues];
        }

        return $assoc;
    }

    public function removeServiceToClinic($service_id, $clinic_id) {

        $q = sprintf('DELETE FROM service_to_clinic WHERE service_id = %d AND clinic_id = %d', (int)$service_id, (int)$clinic_id);
        $result = $this->db->post($q);
        ElasticaTask::indexClinic($clinic_id);
        return $result;
    }

    public function clearServicesRelations($service_id) {

        $q = sprintf('DELETE FROM service_to_clinic WHERE service_id = %d', (int)$service_id);
        $q2 = sprintf('DELETE FROM services_to_doctor WHERE service_id = %d', (int)$service_id);

        $this->db->post($q);
        $this->db->post($q2);

        return true;
    }

    public function addServiceToDoctor($service_id, $doctor_id) {

        $exists = $this->getServicesRelationsDoctors($service_id);

        if(is_array($doctor_id)){

            foreach($doctor_id as $one){

                if( ! in_array($one, $exists)){

                    $this->insert('services_to_doctor', [
                        'services_categories_id' => (int)$service_id,
                        'doctor_id' => (int)$one
                    ]);
                }
            }

            foreach($exists as $one){

                if( ! in_array($one, $doctor_id)){

                    $this->removeServiceToDoctor($service_id, $one);
                }
            }

        } else {

            if( ! in_array($doctor_id, $exists)){

                $this->insert('services_to_doctor', [
                    'services_categories_id' => (int)$service_id,
                    'doctor_id' => (int)$doctor_id
                ]);
            }
        }

    }

    public function unlinkAllDoctors($services_ids) {

        is_array($services_ids) AND $services_ids = implode(', ', array_map('intval', $services_ids));

        $q = sprintf('DELETE FROM `services_to_doctor` WHERE `services_categories_id` IN (%s)', $services_ids);

        return $this->db->post($q);
    }

    public function getServicesRelationsDoctors($services_ids) {

        is_array($services_ids) AND $services_ids = implode(', ', array_map('intval', $services_ids));

        // SELECT * FROM `service_to_clinic` s2c INNER JOIN services_categories sc ON s2c.services_categories_id = sc.id WHERE sc.id = 1
        $q = str_replace(['{ids}'], [
            $services_ids
        ], 'SELECT doctor_id
            FROM `services_to_doctor` s2d
            INNER JOIN services_categories sc ON s2d.services_categories_id = sc.id
            WHERE sc.id IN ({ids})');

        return $this->fetch_column($q, 'doctor_id');
    }

    public function removeServiceToDoctor($service_id, $doctor_id) {

        $q = sprintf('DELETE FROM services_to_doctor WHERE services_categories_id = %d AND doctor_id = %d', (int)$service_id, (int)$doctor_id);

        return $this->db->post($q);
    }

    public function getClinicsBySpecialization($specID) {

        $q = sprintf('SELECT DISTINCT(sp2c.clinic_id) as clinics
                FROM `specialization` sp
                    INNER JOIN specialization_to_clinic sp2c ON sp.id = sp2c.specialization_id
                WHERE sp.id = %d', (int)$specID);

        return $this->fetch_column($q, 'clinics');

    }
}

/* END CLASS: RelationsSimpleModel extends SimpleModel */