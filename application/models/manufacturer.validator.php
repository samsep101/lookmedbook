<?php
	class ManufacturerValidator extends ModelValidator
	{
		public function validate(DynamicModel $model)
		{
			/**
			 * @var ManufacturerModel $model
			 */
			if(!$model->name)
			{
				return false;
			}

			return true;
		}

	}