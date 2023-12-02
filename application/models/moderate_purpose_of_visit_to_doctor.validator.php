<?php
	class ModeratePurposeOfVisitToDoctorValidator extends ModelValidator
	{
		public function validate(ModeratePurposeOfVisitToDoctorModel $moderate_purpose_of_visit_to_doctor)
		{
			$purpose_of_visit = ModelManagerFactory::getByName('purpose_of_visit_to_specialty')->getOneByPurposeOfVisitIdAndSpecialtyId($moderate_purpose_of_visit_to_doctor->purpose_of_visit_id, $moderate_purpose_of_visit_to_doctor->specialty_id);

			if(!$purpose_of_visit)
			{
				return false;
			}

			return true;
		}
	}