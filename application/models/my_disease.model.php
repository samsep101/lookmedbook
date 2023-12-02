<?php

	/**
	 * @property int $id
	 * @property int $account_id
	 * @property AccountModel $account
	 * @property int $disease_id
	 * @property DiseaseModel $disease
	 * @property datetime $dt
	 * @property int $is_archive
	 *
	 * @property DiseaseModel  $current_disease
	 * @property DiseaseTagModel[] $disease_tags
	 */
    class MyDiseaseModel extends DynamicModel {

		protected function _field_current_disease()
		{
			$this->current_disease = $this->disease;
			return $this->current_disease;
		}

		protected function _field_disease_tags()
		{
			$disease_tag_manager = new DiseaseTagManager();
			$this->disease_tags = $disease_tag_manager->getListByDiseaseId($this->disease_id);
			return $this->disease_tags;
		}

	}
