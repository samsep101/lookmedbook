<?php
	/**
	 * @property int $id
	 * @property int $clinic_id
	 * @property ClinicModel $clinic
	 * @property int $specialization_id
	 * @property SpecializationModel $specialization
	 * @property int $revision_number
	 */
	class ModerateSpecializationToClinicModel extends ModerateModel
	{
		public function _field_childs()
		{
			if(!isset($this->childs))
			{
				$manager = new ModerateSpecialtyToClinicManager();

                $revision_condition = array(
                    'clinic_id' => $this->clinic_id,
                );

				$revision = $manager->getCurrentRevision($revision_condition);
				$revision_number = $revision->revision_info->revision_number;
				$this->childs = $manager->getListByClinicIdAndSpecializationIdAndRevisionNumber($this->clinic_id, $this->id, $revision_number);
			}

			return $this->childs;
		}
	}