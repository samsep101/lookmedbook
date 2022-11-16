<?php
    class ModerateDoctorToClinicManager extends ListModerateModelManager {
        protected $table_name = 'moderate_doctor_to_clinic';
        protected $model_name = 'ModerateDoctorToClinicModel';

        protected $moderated_list_name = 'doctor_to_clinic';
        protected $moderated_entity_name = 'doctor';
        protected $fields = array(
            'clinic_id',
        );

        protected $revision_conditions = array(
            'doctor_id'
        );

        protected function beforePublishRevision()
        {
            $sql = 'DELETE
                    FROM moderate_doctor_to_clinic
                    WHERE clinic_id IS NULL';

            $this->db->query($sql);
        }

        protected function afterPublishRevision($revision_info)
        {
            $entity = explode('=', $revision_info->entity_id);

            ModelManagerFactory::getByName('doctor_specialty_to_clinic')->deleteUnActualDoctorToClinic($entity[1]);

            $sql = 'DELETE
                    FROM moderate_doctor_to_clinic
                    WHERE clinic_id IS NULL';

            $this->db->query($sql);
        }
    }