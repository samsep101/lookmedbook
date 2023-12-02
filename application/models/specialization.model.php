<?php

use app\library\helpers\Morpher;

/**
	 * @property int $id
	 * @property string $name
	 * @property string $adjective_name
	 * @property int $is_alternative
	 * @property int $for_whom
	 *
	 * @property  $main_specialty
     * @property  $main_clinic_specialty
	 * @property  $main_specialty_id
	 * @property  $specialties
	 * @property  $specialties_count
	 * @property  $view_specialties
	 * @property  $synonyms
     * @property  $plural_name
	 */
	class SpecializationModel extends DynamicModel
	{
		public function __construct()
		{
			$this->setDefaultValue('is_can_be_children', 1);
		}

        // added
        public function getMainSpecialty($clinic_id)
        {
            if(!isset($this->main_specialty))
            {
                $specialty_manager = new SpecialtyManager();
                $this->main_clinic_specialty = $specialty_manager->getMainOneBySpecializationIdAndClinicId($this->getId(), $clinic_id);
            }

            return $this->main_clinic_specialty;
        }

		public function _field_main_specialty()
		{
			if(!isset($this->main_specialty))
			{
				$specialty_manager = new SpecialtyManager();
				$this->main_specialty = $specialty_manager->getMainOneBySpecializationId($this->getId());
			}

			return $this->main_specialty;
		}

		public function _field_main_specialty_id()
		{
			return ($this->main_specialty) ? $this->main_specialty->getId() : false;
		}

		public function _field_specialties()
		{
			if(!isset($this->specialties))
			{
				$specialty_manager = new SpecialtyManager();
				$this->specialties = $specialty_manager->getListBySpecializationId($this->getId());
			}

			return $this->specialties;
		}

		public function _field_specialties_count()
		{
			return count($this->specialties);
		}

		public function _field_view_specialties()
		{
			if(!isset($this->view_specialties))
			{
				$str = '';
				$specialty_to_specialization_manager = new SpecialtyToSpecializationManager();

				$relations = $specialty_to_specialization_manager->getListBySpecializationId($this->getId());
				if($relations)
				{
					foreach($relations as $relation)
					{
						$str1 = $relation->specialty->name;

						if($relation->is_main)
						{
							$str1 = '<b>' . $str1 . '</b>';
						}

						$str .= $str1 . ', ';
					}
				}

				$this->view_specialties = trim($str, ', ');
			}

			return $this->view_specialties;
		}

		public function _field_synonyms()
		{
			if(!isset($this->synonyms))
			{
				$specialization_synonym_manager = new SpecializationSynonymManager();
				$this->synonyms = $specialization_synonym_manager->getListBySpecializationId($this->getId());
			}

			return $this->synonyms;
		}

		public function _field_plural_name()
        {
            if (extension_loaded('morpher')) {
                return Morpher::inflect($this->name, "mn");
            }

            return WordDeclination::getInstance()->toPlural($this->name);
        }

	}
