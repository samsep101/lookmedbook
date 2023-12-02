<?php
	class SpecialtyToSpecializationValidator extends ModelValidator
	{
		public function validate(SpecialtyToSpecializationModel $specialty_to_specialization)
		{

			if(!$specialty_to_specialization->specialty || !$specialty_to_specialization->specialization)
			{
				$this->error_messages[] = 'Обязательно должны быть указаны как специализация, так и область '.MEDICYNY;
				return false;
			}

			$specialty = $specialty_to_specialization->specialty;
			$specialization = $specialty_to_specialization->specialization;

			if($specialty_to_specialization->is_main)
			{
				$main_specialty = $specialty_to_specialization->specialization->main_specialty;

				// если пытаемся добавить вторую основную специализацию  к области медицины
				if($main_specialty && ($main_specialty->getId() != $specialty_to_specialization->specialty_id))
				{
					$this->error_messages[] = 'У данной области '.MEDICYNY.' уже имеется основная специализация: ' . $main_specialty->name;
					return false;
				}

				// если пытаемя сделать специализацию главной, а она относится к нескольким областям медицины
				/*
				$specializations_count = count($specialty_to_specialization->specialty->specializations);

				if (!$specialty_to_specialization->getId())
					$specializations_count++;

				if($specializations_count > 1)
				{
					$this->error_messages[] = 'Основной специализацией может быть только в том случае, если она относится к одной области медицины
						(специализация "'.$specialty_to_specialization->specialty->name.'" относится к '.$specializations_count.' областям медицины)';
					return FALSE;
				}*/
			}
			else
			{
				// Проверяем, что мы не добавляем область специализации, которая является основной
				/*
				$specialty_to_specialization_manager = new SpecialtyToSpecializationManager();
				$main_relation = $specialty_to_specialization_manager->getOneMainBySpecialtyId($specialty_to_specialization->specialty_id);

				if ($main_relation && ($main_relation->getId() != $specialty_to_specialization->getId()))
				{
					$this->error_messages[] = 'Нельзя добавить специализацию к области медицины, если она является основной для другой области медицины'.
						' (специализация "'.$specialty->name.'" является основной для области медицины "'.
						$specialization->name.'").';
					return FALSE;
				}
				*/
			}

			return true;
		}
	}