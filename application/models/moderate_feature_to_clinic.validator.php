<?php
	class ModerateFeatureToClinicValidator extends ModelValidator
	{
		public function validate(ModerateFeatureToClinicModel $moderate_feature_to_clinic)
		{
			$feature = ModelManagerFactory::getByName('feature')->getOneById($moderate_feature_to_clinic->feature_id);

			if(!$feature)
			{
				return false;
			}

			return true;
		}
	}