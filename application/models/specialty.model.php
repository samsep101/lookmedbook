<?php

	/**
	 * @property int $id
	 * @property string $name
	 * @property string $alias
	 * @property int $parent_id
	 * @property string $service_name
	 * @property string $genitive_name
	 * @property string $dative_name
	 * @property string $plural_name
	 * @property int $for_whom
     * @property int $docdoc_id
	 *
	 * @property PurposeOfVisitModel[] $purposes_of_visit
	 * @property SpecializationModel[] $specializations
	 * @property int $specializations_count
	 * @property int $purposes_of_visit_count
	 * @property PurposeOfVisitModel[] $main_purposes_of_visit
	 * @property string $view_specializations
     * @property SpecialtyModel[] $childs
     * @property string $genitive_name_plural
	 */

    class SpecialtyModel extends DynamicModel {

		const GYNECOLOGIST = 34;
		const OBSTETRICIAN_GYNECOLOGIST = 14;
		const OPHTHALMOLOGIST = 15;
		const ALLERGIST_IMMUNOLOGIST = 2;
		const NEUROLOGIST = 13;
		const OTOLARYNGOLOGIST = 6;

		public function __construct()
		{
			$this->setDefaultValue('is_can_be_children', 1);
		}

		protected function _field_childs()
		{
			$this->childs = ModelManagerFactory::getByName('specialty')->getListByParentId($this->id);
			return $this->childs;
		}

		protected function _field_purposes_of_visit()
		{
			if(!isset($this->purposes_of_visit))
			{
				$purpose_of_visit_manager = new PurposeOfVisitManager();

				$this->purposes_of_visit = $purpose_of_visit_manager->getListBySpecialtyId($this->getId());
			}

			return $this->purposes_of_visit;
		}

		protected function _field_specializations()
		{
			if(!isset($this->specializations))
			{
				$specialization_manager = new SpecializationManager();
				$this->specializations = $specialization_manager->getListBySpecialtyId($this->getId());
			}

			return $this->specializations;
		}

		protected function _field_specializations_count()
		{
			return count($this->specializations);
		}

		protected function _field_purposes_of_visit_count()
		{
			return count($this->purposes_of_visit);
		}

		protected function _field_main_purposes_of_visit()
		{
			if(!isset($this->main_purposes_of_visit))
			{
				$purpose_of_visit_manager = new PurposeOfVisitManager();
				$this->main_purposes_of_visit = $purpose_of_visit_manager->getMainListBySpecialtyId($this->getId());
			}

			return $this->main_purposes_of_visit;
		}

		protected function _field_view_specializations()
		{
			if(!isset($this->view_specializations))
			{
				$str = '';
				$specialty_to_specialization_manager = new SpecialtyToSpecializationManager();

				$relations = $specialty_to_specialization_manager->getListBySpecialtyId($this->getId());
				if($relations)
				{
					foreach($relations as $relation)
					{
						if(!$relation->specialization)
						{
							continue;
						}

						$str1 = $relation->specialization->name;

						if($relation->is_main)
						{
							$str1 = '<b>' . $str1 . '</b>';
						}

						$str .= $str1 . ', ';
					}
				}

				$this->view_specializations = trim($str, ', ');
			}

			return $this->view_specializations;
		}
		
    	protected function _field_specialty_page_descr()
		{
			if(!isset($this->specialty_page_descr)) {
				$specialty_descr_manager = new SpecialtyDescrManager();
				$this->specialty_page_descr = $specialty_descr_manager->getSpecialtyPageDescrBySpecialtyId($this->getId());
			}
			return $this->specialty_page_descr;
		}
		
    	protected function _field_clinic_page_descr()
		{
			if(!isset($this->clinic_page_descr)) {
				$specialty_descr_manager = new SpecialtyDescrManager();
				$this->specialty_page_descr = $specialty_descr_manager->getClinicPageDescrBySpecialtyId($this->getId());
			}
			return $this->clinic_page_descr;
		}
	}
