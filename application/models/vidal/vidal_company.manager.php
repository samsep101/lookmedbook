<?php
	class VidalCompanyManager extends VidalModelManager
	{
		protected $table_name = "company";
		protected $model_name = "VidalCompanyModel";


		/**
		 * @var int $CountryCode
		 * @return VidalCompanyModel[]
		 */
		public function getListByCountryCode($CountryCode)
		{
			$data = $this->orm_model->select()->where('CountryCode = ?', $CountryCode)->fetchAll();
			return $this->initList($data);
		}

	}