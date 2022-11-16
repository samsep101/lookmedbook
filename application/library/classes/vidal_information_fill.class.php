<?php
	class VidalInformationFill
	{
		/**
		 * @var ProductModel
		 */
		private $product;

		/**
		 * @var VidalProductModel
		 */
		private $vidal_product;

		public function fill(ProductModel $product, VidalProductModel $vidal_product)
		{
			$this->product = $product;
			$this->vidal_product = $vidal_product;

			$this->fillImage();

			$product->en_name = $vidal_product->EngName;
			$product->extend_information->is_non_presciption = $vidal_product->NonPrescriptionDrug;
			//$product->information->date_registration = date('Y-m-d H:i:s', $vidal_product->RegistrationDate);
			//$product->information->date_of_close_registration = date('Y-m-d H:i:s', $vidal_product->DateOfCloseRegistration);
			$product->information->composition = str_replace('◊ ', '', $vidal_product->Composition);
			$product->information->composition = str_replace('[PRING]', '', $product->information->composition);
			$product->information->zip_info = str_replace('◊ ', '', $vidal_product->ZipInfo);
			$product->information->zip_info = str_replace('[PRING]', '', $product->information->zip_info);

			if($vidal_product->document)
			{
				$product->information->pharma_effects = $vidal_product->document->PhInfluence;
				$product->information->pharmacokinetics = $vidal_product->document->PhKinetics;
				$product->information->dosage = $vidal_product->document->Dosage;
				$product->information->overdosage = $vidal_product->document->OverDosage;
				$product->extend_information->interaction = $vidal_product->document->Interaction;
				$product->extend_information->lactation = $vidal_product->document->Lactation;
				$product->information->side_effects = $vidal_product->document->SideEffects;
				$product->information->storage_condition = $vidal_product->document->StorageCondition;
				$product->information->indications = $vidal_product->document->Indication;
				$product->information->contra_indications = $vidal_product->document->ContraIndication;
				$product->extend_information->year_edition = $vidal_product->document->YearEdition;
				$product->extend_information->article_type_id = $vidal_product->document->ArticleID;
				$product->extend_information->special_information = $vidal_product->document->SpecialInstruction;
				$product->extend_information->pharm_delivery = $vidal_product->document->PharmDelivery;
			} else {
				$product->information->pharma_effects = null;
				$product->information->pharmacokinetics = null;
				$product->information->dosage = null;
				$product->information->overdosage = null;
				$product->extend_information->interaction = null;
				$product->extend_information->lactation = null;
				$product->information->side_effects = null;
				$product->information->storage_condition = null;
				$product->information->indications = null;
				$product->information->contra_indications = null;
				$product->extend_information->year_edition = null;
				$product->extend_information->article_type_id = null;
				$product->extend_information->special_information = null;
				$product->extend_information->pharm_delivery = null;
			}

			$product->information->save();
			$product->extend_information->save();
			$product->save();
		}

		private function fillImage()
		{
			if(($this->product->image_find_status_id == ImageFindStatusModel::OK) || ($this->product->image_find_status_id == ImageFindStatusModel::FIND_IN_VIDAL))
			{
				return;
			}

			if(!$this->vidal_product->picture)
			{
				return;
			}

			$path = ABS_ROOT . '/media/vidal/' . str_replace('\\', '/', $this->vidal_product->picture->Path);

			if(!file_exists($path))
			{
				echo $path . ' не найден' . "<br />";

				return;
			}


			$image_id = ImageUploader::loadImage($path, 'product/');

			$this->product->image_id = $image_id;
			$this->product->image_find_status_id = ImageFindStatusModel::FIND_IN_VIDAL;
		}
	}