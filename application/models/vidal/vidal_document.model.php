<?php
	/**
	 * @property int $DocumentID
	 * @property string $RusName
	 * @property string $EngName
	 * @property string $Elaboration
	 * @property string $CompaniesDescription
	 * @property int $ArticleID
	 * @property string $CompiledComposition
	 * @property string $ClPhGrDescription
	 * @property string $PhInfluence
	 * @property string $PhKinetics
	 * @property string $Dosage
	 * @property string $OverDosage
	 * @property string $Interaction
	 * @property string $Lactation
	 * @property string $SideEffects
	 * @property string $StorageCondition
	 * @property string $Indication
	 * @property string $ContraIndication
	 * @property string $SpecialInstruction
	 * @property int $ShowGenericsOnlyInGNList
	 * @property int $NewForCurrentEdition
	 * @property string $YearEdition
	 * @property string $PregnancyUsing
	 * @property string $NursingUsing
	 * @property string $RenalInsuf
	 * @property string $RenalInsufUsing
	 * @property string $HepatoInsuf
	 * @property string $HepatoInsufUsing
	 * @property string $PharmDelivery
	 * @property string $dosage_form
	 * @property string $rus_name_clean
	 *
	 * @property VidalInfopageModel $manufacturer
	 */
	class VidalDocumentModel extends DynamicModel
	{
		public function _field_manufacturer()
		{
			if(!isset($this->manufacturer))
			{
				/**
				 * @var VidalInfopageManager $vidal_infopage_manage
				 */
				$vidal_infopage_manage = ModelManagerFactory::getByName('vidal_infopage');
				$this->manufacturer = $vidal_infopage_manage->getOneByDocumentId($this->getId());
			}

			return $this->manufacturer;
		}
	}