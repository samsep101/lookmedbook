<?php
	class ManufacturerManager extends ModelManager
	{
		protected $table_name = "manufacturer";
		protected $model_name = "ManufacturerModel";

		/**
		 * @var int $name
		 * @return ManufacturerModel[]
		 */
		public function getOneByName($name)
		{
			$data = $this->orm_model->select()->where('name = ?', $name)->fetchOne();
			return $this->initOne($data);
		}

		public function getListByModelSearchCriteria(ModelSearchCriteria $criteria)
		{
			/**
			 * @var ManufacturerSearchCriteria $criteria
			 */

			$search_params = $criteria->getSearchParams();


			if (!$search_params) {
				$search_params = new SearchParams();
			}

			if($criteria->name)
			{
				$search_params->addParam('name LIKE', '%'.$criteria->name.'%');
			}

			if($criteria->by_page)
			{
				$limit = $criteria->by_page + 1;
				$offset = ($criteria->page - 1) * $criteria->by_page;

				$search_params->setOffsetAndLimit($offset, $limit);
			}

			switch($criteria->sort_by)
			{
				case 'name':
					$search_params->addSortParam('name', 'ASC');
					break;
			}

			return $this->getListBySearchParams($search_params);
		}
	}