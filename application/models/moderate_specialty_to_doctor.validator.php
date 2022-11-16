<?php
	class ModerateSpecialtyToDoctorValidator extends ModelValidator
	{
		public function validate(ModerateSpecialtyToDoctorModel $moderate_specialty_to_doctor)
		{
			$specialty = ModelManagerFactory::getByName('specialty')->getOneById($moderate_specialty_to_doctor->specialty_id);

			if(!$specialty)
			{
				return false;
			}

			return true;
		}
	}