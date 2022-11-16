<?php

	/**
	 * @property int $id
	 * @property int $clinic_id
	 * @property ClinicModel $clinic
	 * @property int $revision_number
	 * @property int $specialty_id
	 * @property SpecialtyModel $specialty
	 * @property int $purpose_of_visit_id
	 * @property PurposeOfVisitModel $purpose_of_visit
	 * @property int $visit_price
	 */
    class ModeratePurposeOfVisitToClinicModel extends ModerateModel {

		public function _field_visit_price()
		{
			if(!isset($this->visit_price))
			{
				if($this->is_selected || $this->is_main)
				{
					$manager = new ModeratePurposeOfVisitToClinicManager();
					$price = $manager->getVisitPriceByPurposeOfVisitIdAndClinicIdAndSpecialtyId($this->id, $this->clinic_id, $this->specialty_id);
					$this->visit_price = $price;
				}
				else
				{
					return '';
				}
			}

			return $this->visit_price;
		}
	}