<?php
	class MoleculeManager extends ModelManager
	{
		protected $table_name = "molecule";
		protected $model_name = "MoleculeModel";


		/**
		 * @var int $name
		 * @return MoleculeModel[]
		 */
		public function getListByName($name)
		{
			$data = $this->orm_model->select()->where('name = ?', $name)->fetchAll();
			return $this->initList($data);
		}

		/**
		 * @var int $service_id
		 * @return MoleculeModel
		 */
		public function getOneByServiceId($service_id)
		{
			$data = $this->orm_model->select()->where('service_id = ?', $service_id)->fetchOne();
			return $this->initOne($data);
		}

	}