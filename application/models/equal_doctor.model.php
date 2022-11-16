<?php
    /**
     * @property int $id
     * @property int $doctor_id
     * @property int $equal_doctor_id
     *
     * @property DoctorModel $doctor
     *
     */
    class EqualDoctorModel extends DynamicModel
    {
        protected function _field_doctor()
        {
            /**
             * @var DoctorManager $doctor_manager
             * @var DoctorModel $doctor
             */

            $doctor_manager = ModelManagerFactory::getByName('doctor');
            return $doctor_manager->getOneById($this->equal_doctor_id);
        }
    }
