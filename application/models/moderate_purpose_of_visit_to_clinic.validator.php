<?php
	class ModeratePurposeOfVisitToClinicValidator extends ModelValidator
	{
		public function validate(ModeratePurposeOfVisitToClinicModel $moderate_purpose_of_visit_to_clinic)
		{
			$purpose_of_visit = ModelManagerFactory::getByName('purpose_of_visit_to_specialty')->getOneByPurposeOfVisitIdAndSpecialtyId($moderate_purpose_of_visit_to_clinic->purpose_of_visit_id, $moderate_purpose_of_visit_to_clinic->specialty_id);

			if(!$purpose_of_visit)
			{
				return false;
			}

			return true;
		}
	}