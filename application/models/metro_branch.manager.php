<?php
	class MetroBranchManager extends ModelManager
	{
		protected $table_name = 'metro_branch';
		protected $model_name = 'MetroBranchModel';


        /**
         * @var int $metro_id
		 * @return MetroBranchModel[]
		 */
		public function getListByMetroId($metro_id)
		{
			$data = $this->orm_model->select()->where('metro_id = ?', $metro_id)->fetchAll();
			return $this->initList($data);
		}

        /**
         * @var string $name
		 * @return MetroBranchModel
		 */
		public function getOneByName($name)
		{
			$data = $this->orm_model->select()->where('name = ?', $name)->fetchOne();

			return $data ? $this->initOne($data) : false;
		}

	}