<?php
	/**
	 * @property int $ProductID
	 * @property string $RusName
	 * @property string $EngName
	 * @property int $NonPrescriptionDrug
	 * @property string $RegistrationDate
	 * @property string $DateOfCloseRegistration
	 * @property string $RegistrationNumber
	 * @property int $PPR
	 * @property string $Composition
	 * @property string $ZipInfo
	 * @property string $ProductTypeCode
	 *
	 * @property VidalDocumentModel $document
	 * @property VidalPictureModel $picture
	 * @property string $unit_size
	 */
	class VidalProductModel extends DynamicModel
	{

		public function _field_document()
		{
			if(!isset($this->document))
			{
				/**
				 * @var VidalDocumentManager $vidal_document_manager
				 */
				$vidal_document_manager = ModelManagerFactory::getByName('vidal_document');
				$this->document = $vidal_document_manager->getOneByProductId($this->getId());
			}

			return $this->document;
		}

		/**
		 * @return VidalPictureModel
		 */
		public function _field_picture()
		{
			if(!isset($this->picture))
			{
				/**
				 * @var VidalPictureManager $vidal_picture_manager
				 */
				$vidal_picture_manager = ModelManagerFactory::getByName('vidal_picture');
				$this->picture = $vidal_picture_manager->getOneByProductId($this->getId());
			}

			return $this->picture;
		}
	}