<?php
	/**
	 * @property int $id
	 * @property int $purpose_of_visit_id
	 * @property PurposeOfVisitModel $purpose_of_visit
	 * @property int $specialty_id
	 * @property SpecialtyModel $specialty
	 * @property int $doctor_id
	 * @property DoctorModel $doctor
	 * @property int $clinic_id
	 * @property ClinicModel $clinic
	 * @property int $visit_price
	 * @property int $is_auto
	 * @property int $is_to_delete
	 * @property datetime $delete_date
	 *
	 */
	class PurposeOfVisitToDoctorModel extends DynamicModel
	{
        protected function _field_price()
        {
            if ($this->visit_price) {
                return $this->visit_price;
            } else {
                /**
                 * @var PurposeOfVisitToClinicManager $purpose_of_visit_to_clinic_manager
                 * @var PurposeOfVisitToClinicModel $purpose_of_visit
                 */
                $purpose_of_visit_to_clinic_manager = new PurposeOfVisitToClinicManager();
                $purpose_of_visit = $purpose_of_visit_to_clinic_manager->getOneByClinicIdAndSpecialtyIdAndPurposeOfVisitId($this->clinic_id, $this->specialty_id, $this->purpose_of_visit_id);
                return $purpose_of_visit->visit_price;
            }
        }
	}