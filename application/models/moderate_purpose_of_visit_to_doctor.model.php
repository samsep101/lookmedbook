<?php

	/**
	 * @property int $id
	 * @property int $revision_number
	 * @property int $doctor_id
	 * @property DoctorModel $doctor
	 * @property int $clinic_id
	 * @property ClinicModel $clinic
	 * @property int $specialty_id
	 * @property SpecialtyModel $specialty
	 * @property int $purpose_of_visit_id
	 * @property PurposeOfVisitModel $purpose_of_visit
	 * @property int $visit_price
	 * @property int $is_auto
	 *
	 * @property  $clinic_price_to_purpose
	 */
    class ModeratePurposeOfVisitToDoctorModel extends ModerateModel {

		public function _field_clinic_price_to_purpose()
		{
			if(!isset($this->clinic_price_to_purpose))
			{
				$manager = new ModeratePurposeOfVisitToClinicManager();
				$moderate_purpose_of_visit_to_clinic = $manager->getOneByPurposeOfVisitIdAndClinicIdAndSpecialtyId($this->id, $this->clinic_id, $this->specialty_id);
				$this->clinic_price_to_purpose = $moderate_purpose_of_visit_to_clinic ? $moderate_purpose_of_visit_to_clinic->visit_price : '';
			}

			return $this->clinic_price_to_purpose;
		}
	}