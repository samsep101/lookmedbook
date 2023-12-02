<?php
    /**
     * @property int $id
     * @property int $clinic_id
     * @property int $equal_clinic_id
     *
     * @property ClinicModel $clinic
     */
    class EqualClinicModel extends DynamicModel
    {
        protected function _field_clinic()
        {
            /**
             * @var ClinicManager $clinic_manager
             * @var ClinicModel $clinic
             */

            $clinic_manager = ModelManagerFactory::getByName('clinic');
            $clinic = $clinic_manager->getOneById($this->equal_clinic_id);

            return ($clinic) ? $clinic : null;
        }
    }