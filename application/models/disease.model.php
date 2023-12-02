<?php

	/**
	 * @property int $id
	 * @property string $title
	 * @property string $alias
	 * @property string $content
	 * @property string $extended_content
	 * @property string $sources
	 * @property string $content_markup
	 * @property int $is_active
     * @property datetime $created_at
     * @property datetime $date_update
     * @property datetime $date_yandex_send
	 * @property string $genitive_name
	 * @property string $prepositional_name
	 *
	 * @property DiseaseModel[]  $my_disease
	 * @property DiseaseAltNameModel[] $alt_names
	 * @property string $alt_names_string
	 * @property MedicineModel $medicine
	 * @property SpecialtyModel $main_specialty
	 * @property int $votes_count
	 * @property float $procent_understand
	 * @property int $content_project_id
     * @property string $page_ad
     * @property string $page_ad_after_diagnostics
	 *
	 * @property string[] $tags
	 */
    class DiseaseModel extends DynamicModel {

        protected $raw_fields = ['page_ad', 'page_ad_after_diagnostics'];

		protected function _field_my_disease()
		{
			/**
			 * @var MyDiseaseManager $my_disease_manager
			 */
			$my_disease_manager = ModelManagerFactory::getByName('my_disease');
			$this->my_disease = $my_disease_manager->checkExistsByDiseaseIdAndAccountId($this->id, Acc::accountId());
			return $this->my_disease;
		}

		protected function _field_alt_names()
		{
			if(!isset($this->alt_names))
			{
				$disease_alt_name_manager = new DiseaseAltNameManager();
				$this->alt_names = $disease_alt_name_manager->getListByDiseaseId($this->getId());
			}

			return $this->alt_names;

		}

		protected function _field_alt_names_string()
		{
			$str = '';

			foreach($this->alt_names as $alt_name)
			{
				$str .= str_replace('.', '', $alt_name->alt_name) . ', ';
			}

			return trim($str, ', ');
		}

		protected function _field_medicine()
		{
			/**
			 * @var MedicineToDiseaseManager $medicine_to_disease_manager
			 */
			$medicine_to_disease_manager = ModelManagerFactory::getByName('medicine_to_disease');
			$medicine_to_disease = $medicine_to_disease_manager->getOneByDiseaseId($this->id);
			if($medicine_to_disease)
			{
				return $medicine_to_disease->medicine;
			} else {
				return null;
			}
		}

		protected function _field_main_specialty()
		{
			/**
			 * @var SpecialtyToDiseaseManager $specialty_to_disease_manager
			 */
			$specialty_to_disease_manager = ModelManagerFactory::getByName('specialty_to_disease');
			$this->main_specialty = $specialty_to_disease_manager->getOneByDiseaseIdAndMainFlag($this->id);
			return $this->main_specialty;
		}

		protected function _field_votes_count()
		{
			/**
			 * @var DiseaseUnderstandManager $disease_understand_manager
			 */
			$disease_understand_manager  = ModelManagerFactory::getByName('disease_understand');
			$this->votes_count = $disease_understand_manager->getCountByDiseaseId($this->id);
			return $this->votes_count;
		}

		protected function _field_procent_understand()
		{
			/**
			 * @var DiseaseUnderstandManager $disease_understand_manager
			 */
			$disease_understand_manager = ModelManagerFactory::getByName('disease_understand');
			$votes_count = $disease_understand_manager->getCountByDiseaseId($this->id);
			$understand_count = $disease_understand_manager->getCountByDiseaseIdAndUnderstandFlag($this->id, 1);
			if($understand_count != 0)
			{
				$this->procent_understand = (int)($understand_count / $votes_count * 100) . '%';
			}
			else
			{
				$this->procent_understand = '0%';
			}

			return $this->procent_understand;
		}

		protected function _field_tags()
		{
			/**
			 * @var DiseaseTagManager $disease_tag_manager
			 */
			$disease_tag_manager = ModelManagerFactory::getByName('disease_tag');

			$tags = $disease_tag_manager->getListByDiseaseId($this->getId());

			$result = array();

			foreach($tags as $tag)
			{
				$result[] = $tag->tag;
			}

			return $result;
		}

		public function getRFCDateUpdated()
        {
            if (!$this->date_update) {
                return null;
            }
            return (new DateTime($this->date_update))->format(DATE_RFC3339);
        }

		public function getRFCCreatedAt()
        {
            if (!$this->created_at) {
                return null;
            }
            return (new DateTime($this->created_at))->format(DATE_RFC3339);
        }
	}
