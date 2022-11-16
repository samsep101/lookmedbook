<?php

    class ModerateDoctorInformationManager extends EntityModerateModelManager
    {
        protected $table_name = 'moderate_doctor_information';
        protected $model_name = 'ModerateDoctorInformationModel';

        protected $moderated_entity_name = 'doctor';
        protected $fields = array(
            'first_name',
            'second_name',
            'last_name',
            'sex_id',
            'is_leave_the_house',
            'not_work',
            'is_active',
            'redirect_list',
            'is_adult',
            'is_children',
            'is_pregnant',
            'is_handicapped',
            'about_internal',
            'about',
            'rate',
            'work_experience',
            'clinic_id',
            'education',
            'course',
            'certificate',
            'academic_title',
            'top_phone',
        );

        public function beforeSave(DynamicModel $model)
        {
            /**
             * @var ModerateDoctorInformationModel $model
             */

            if(!$model->{$this->moderated_entity_name . '_id'})
            {
                $entity_id        = $this->createNewEntity($model);
                $model->doctor_id = $entity_id;
            }

            if($model->clinic_id && $model->doctor_id)
            {
                $doctor_to_clinic_manager = new DoctorToClinicManager();
                $doctor_to_clinic         = $doctor_to_clinic_manager->getOneByClinicIdAndDoctorId($model->clinic_id, $model->entry_id);

                if(!$doctor_to_clinic)
                {
                    $doctor_to_clinic            = new DoctorToClinicModel();
                    $doctor_to_clinic->clinic_id = $model->clinic_id;
                    $doctor_to_clinic->doctor_id = $model->doctor_id;
                    $doctor_to_clinic->save();
                }
            }

        }

        public function createNewEntity(ModerateModel $model)
        {
            /**
             * @var ModerateDoctorInformationModel $model
             */

            $doctor              = new DoctorModel();
            $doctor->first_name  = $model->first_name;
            $doctor->second_name = $model->second_name;
            $doctor->last_name   = $model->last_name;

            $doctor->save();

            return $doctor->getId();
        }

        public function createModel()
        {
            $doctor            = new DoctorModel();
            $doctor->is_active = 0;
            $doctor->save();

            $model            = new ModerateDoctorInformationModel();
            $model->doctor_id = $doctor->getId();

            return $model;
        }


        public function beforePublish($doctor)
        {
            /**
             * @var DoctorModel $doctor
             */
            $doctor->unsetSaveProcessFlag();
        }
    }
