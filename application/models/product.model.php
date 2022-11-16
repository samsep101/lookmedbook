<?php
	/**
	 * @property int $id
	 * @property string $article
	 * @property int $manufacturer_id
	 * @property ManufacturerModel $manufacturer
	 * @property string $ru_name
	 * @property string $en_name
     * @property string $alias
	 * @property int $product_category_id
	 * @property ProductCategoryModel $product_category
	 * @property string $service_code
	 * @property int $product_dosage_form_id
	 * @property int $price
	 * @property ProductDosageFormModel $product_dosage_form
	 * @property string $dosage_form_size
	 * @property string $size_of_a_unit
     * @property int $image_id
	 * @property ProductAvailabilityModel $availability
     * @property string $full_name
	 * @property $clean_name
     * @property string $pharma_effects
     * @property string $zip_info
     * @property string $composition
     * @property string $dosage
     * @property string $side_effects
     * @property string $overdosage
     * @property string $storage_condition
     * @property string $pharmacokinetics
     * @property string $indications
     * @property string $contra_indications
	 * @property string $search_name
	 *
	 * @property bool $is_has_full_information
	 * @property int $quantity
	 * @property bool $is_vital
	 * @property bool $dt_actual
	 * @property int $vat
     * @property ImageModel $image
	 * @property ProductInformationModel $information
	 * @property ProductExtendInformationModel $extend_information
	 *
	 * @property int $fill_information_status_id
	 * @property FillInformationStatusModel $fill_information_status
     * @property int $image_find_status_id
     * @property ImageFindStatusModel $image_find_status
     * @property int $uploaded_image_id
	 * @property bool $is_image_confirmed
	 * @property bool $prev_fill_information_status_id
	 * @property bool $prev_is_image_confirmed
	 * @property int $is_active
	 * @property int $is_leader
	 * @property string $image_alias
	 *
	 * @property string $interaction
	 * @property string $lactation
	 * @property string $special_information
	 * @property string $pharm_delivery
	 *
	 */
	class ProductModel extends DynamicModel
	{

		public function _field_availability()
		{
			if(!isset($this->availability))
			{
				$this->availability = new ProductAvailabilityModel();
				$this->availability->product_id = $this->getId();
				$this->availability->quantity = 0;
			}

			return $this->availability;
		}

        public function _field_full_name()
        {
            return $this->clean_name;
        }


        public function _field_pharma_effects()
        {
            return $this->getInformation('pharma_effects');
        }

        public function _field_zip_info()
        {
            return $this->getInformation('zip_info');
        }

        public function _field_side_effects()
        {
            return $this->getInformation('side_effects');
        }

        public function _field_overdosage()
        {
            return $this->getInformation('overdosage');
        }

        public function _field_storage_condition()
        {
            return $this->getInformation('storage_condition');
        }

        public function _field_pharmacokinetics()
        {
            return $this->getInformation('pharmacokinetics');
        }

        public function _field_indications()
        {
            return $this->getInformation('indications');
        }

        public function _field_contra_indications()
        {
            return $this->getInformation('contra_indications');
        }

        public function _field_composition()
        {
            return $this->getInformation('composition');
        }

        public function _field_dosage()
        {
            return $this->getInformation('dosage');
        }

		public function _field_interaction()
		{
			return $this->extend_information->interaction;
		}

		public function _field_lactation()
		{
			return $this->extend_information->lactation;
		}

		public function _field_special_information()
		{
			return $this->extend_information->special_information;
		}

		public function _field_pharm_delivery()
		{
			return $this->extend_information->pharm_delivery;
		}


		public function _field_information()
		{
			if(!isset($this->information))
			{
				/**
				 * @var ProductInformationManager $product_information_manager
				 */
				$product_information_manager = ModelManagerFactory::getByName('product_information');
				$product_information = $product_information_manager->getOneByProductId($this->getId());

				if(!$product_information)
				{
					$product_information = new ProductInformationModel();
					$product_information->product_id = $this->getId();
					$product_information->save();
				}
				$this->information = $product_information;

			}

			return $this->information;
		}

		public function _field_uploaded_image_id()
		{
			return $this->image_id;
		}

		public function _field_extend_information()
		{
			if(!isset($this->extend_information))
			{
				/**
				 * @var ProductExtendInformationManager $product_information_manager
				 */
				$product_information_manager = ModelManagerFactory::getByName('product_extend_information');
				$product_information = $product_information_manager->getOneByProductId($this->getId());

				if(!$product_information)
				{
					$product_information = new ProductExtendInformationModel();
					$product_information->product_id = $this->getId();
					$product_information->save();
				}
				$this->extend_information = $product_information;

			}

			return $this->extend_information;
		}

        private function getInformation($field_name)
        {
            return $this->information->{$field_name};
        }

		protected function _field_search_field()
		{
			return $this->ru_name ? $this->ru_name : $this->clean_name;
		}

        public function _field_image()
        {
            /**
             * @var ImageManager $image_manager
             */
            $image_manager = ModelManagerFactory::getByName('image');
            $image = $image_manager->getOneById($this->image_id);

            return $image;
        }


		protected function _field_prev_fill_information_status_id()
		{
			return (isset($this->params['fill_information_status_id'])) ? $this->params['fill_information_status_id'] : null;
		}

        protected function _field_image_alias()
        {
            return $this->clean_name;
        }

		public function isFillInformationStatusChanged()
		{
			return $this->fill_information_status_id != $this->prev_fill_information_status_id;
		}

		protected function _field_prev_is_image_confirmed()
		{
			return (isset($this->params['is_image_confirmed'])) ? $this->params['is_image_confirmed'] : null;
		}

		public function isImageConfirmedStatusChanged()
		{
			return $this->is_image_confirmed != $this->prev_is_image_confirmed;
		}

		public function isImageUploaded()
		{
			return $this->uploaded_image_id && ($this->params['image_id'] != $this->uploaded_image_id);
		}
	}