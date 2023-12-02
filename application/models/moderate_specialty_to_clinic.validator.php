<?php
	class ModerateSpecialtyToClinicValidator extends ModelValidator
	{
		public function validate(ModerateSpecialtyToClinicModel $moderate_specialty_to_clinic)
		{
			$specialty = ModelManagerFactory::getByName('specialty')->getOneById($moderate_specialty_to_clinic->specialty_id);

			if(!$specialty)
			{
				return false;
			}

			return true;
		}
	}