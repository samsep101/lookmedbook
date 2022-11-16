<?php

	/**
	 * @property int $id
	 * @property int $specialization_id
	 * @property SpecializationModel $specialization
	 * @property int $clinic_id
	 * @property ClinicModel $clinic
	 *
	 */
    class SpecializationToClinicModel extends DynamicModel
    {
        protected function _field_clinic()
        {
            /**
             * @var ClinicManager $clinic_manager
             * @var ClinicModel $clinic
             */

            $clinic_manager = ModelManagerFactory::getByName('clinic');
            $clinic = $clinic_manager->getOneById($this->clinic_id);

            return ($clinic) ? $clinic : null;
        }

        protected function _field_clinic_name()
        {
            /**
             * @var ClinicManager $clinic_manager
             * @var ClinicModel $clinic
             */

            $clinic_manager = ModelManagerFactory::getByName('clinic');
            $clinic = $clinic_manager->getOneById($this->clinic_id);

            return ($clinic) ? $clinic->name : null;
        }
	}