<?php
	class EmailDistributionListManager extends ModelManager
	{
		protected $table_name = 'email_distribution_list';
		protected $model_name = 'EmailDistributionListModel';


		public function getOneByDiseaseId($disease_id)
		{
			$data = $this->orm_model->select()->where('disease_id = ?', $disease_id)->fetchOne();
			return $data ? $this->initOne($data) : false;
		}
	}