<?php
	class SpecializationValidator extends ModelValidator
	{
		public function validate(SpecializationModel $specialization)
		{
			/*
			if($specialization)
			{
				if ($specialization->main_specialty_id)
				{
					$specialty_manager = new SpecialtyManager();
					$specialty = $specialty_manager->getOneById($specialization->main_specialty_id);

					if (	(count($specialty->specializations) >= 2) ||
							((count($specialty->specializations) == 1) &&
								($specialty->specializations[0]->getId()
									!= $specialization->getId()))
						)
					{
						$this->error_messages[] = 'Специализация, указанная основной, уже привязана к другой области '.MEDICYNY.' и не может быть главной';
						return false;
					}
				}
			}*/

			return true;
		}
	}