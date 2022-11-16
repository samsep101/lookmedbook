<?php
	class VidalFinder
	{
		const STATUS_OK = 1;
		const STATUS_NOT_FIND = 2;
		const STATUS_FIND_BY_NAME = 3;
		const STATUS_FIND_BY_NAME_PART = 4;

		/**
		 * @var VidalProductManager
		 */
		private $manager;

		/**
		 * @var PiluliDosageFormLookupManager
		 */
		private $dosage_form_lookup_manager;

		private $find_status_id = 0;

		public function __construct()
		{
			$this->manager = ModelManagerFactory::getByName('vidal_product');
			$this->dosage_form_lookup_manager = ModelManagerFactory::getByName('piluli_dosage_form_lookup');
		}

		/**
		 * @param $name
		 * @param $dosage_form_name
		 *
		 * @return VidalProductModel
		 */
		public function findByNameAndDosageFormNameAndDosageFormSize($name, $dosage_form_name, $dosage_form_size = '')
		{
			$this->manager->clearRegister();
			if($dosage_form_name)
			{
				$dosage_form_lookup = $this->dosage_form_lookup_manager->getVidalNameListByPiluliName($dosage_form_name);

				$dosage_forms = array($dosage_form_name);
				$dosage_forms = array_merge($dosage_forms, $dosage_form_lookup);
			}


			if($dosage_form_size && $dosage_form_name)
			{
				$lookup = $this->manager->getListByRusNameCleanAndDosageFormListAndDosageFormSize($name, $dosage_forms, $dosage_form_size);
				if($lookup)
				{
					$this->find_status_id = FillInformationStatusModel::OK;
					return $lookup[0];
				}
			}
			elseif ($dosage_form_size)
			{
				$lookup = $this->manager->getListByRusNameCleanAndUnitSize($name, $dosage_form_size);
				if($lookup)
				{
					$this->find_status_id = FillInformationStatusModel::BY_NAME_AND_UNIT_SIZE;
					return $lookup[0];
				}
			}

			if($dosage_form_name)
			{
				$dosage_form_lookup = $this->dosage_form_lookup_manager->getVidalNameListByPiluliName($dosage_form_name);

				$dosage_forms = array($dosage_form_name);
				$dosage_forms = array_merge($dosage_forms, $dosage_form_lookup);

				$lookup = $this->manager->getListByRusNameCleanAndDosageFormList($name, $dosage_forms);

				if($lookup)
				{
					$this->find_status_id = FillInformationStatusModel::BY_NAME_AND_DOSAGE_FORM;
					return $lookup[0];
				}
			}

			$lookup = $this->manager->getListByRusNameClean($name);

			if($lookup)
			{
				$this->find_status_id = FillInformationStatusModel::BY_NAME;
				return $lookup[0];
			}

			$lookup = $this->manager->getListByRusNameCleanPart($name);

			if($lookup)
			{
				$this->find_status_id = FillInformationStatusModel::BY_NAME_PART;
				return $lookup[0];
			}

			$this->find_status_id = FillInformationStatusModel::NOT_FOUND;


			return null;
		}

		public function getFindStatusId()
		{
			return $this->find_status_id;
		}

	}