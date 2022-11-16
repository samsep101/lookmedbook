<?php
	/**
	 * @property int $id
	 * @property int $specialty_id
	 * @property SpecialtyModel $specialty
	 * @property int $revision_number
	 * @property int $clinic_id
	 * @property ClinicModel $clinic
	 *
	 * @property PurposeOfVisitModel[] $purposes_of_visit
	 */
    class ModerateSpecialtyToClinicModel extends ModerateModel
	{

		public function _field_purposes_of_visit()
		{
			if(!isset($this->purposes_of_visit))
			{
				$manager = new ModeratePurposeOfVisitToClinicManager();
				$this->purposes_of_visit = $manager->getListByClinicIdAndSpecialtyId($this->clinic_id, $this->id);
			}

			return $this->purposes_of_visit;
		}
	}