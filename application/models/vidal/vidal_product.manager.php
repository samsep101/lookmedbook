<?php
	class VidalProductManager extends VidalModelManager
	{
		protected $table_name = "product";
		protected $model_name = "VidalProductModel";

		protected $id_field_name = 'ProductID';


		/**
		 * @var int $ProductTypeCode
		 * @return VidalProductModel[]
		 */
		public function getListByProductTypeCode($ProductTypeCode)
		{
			$data = $this->orm_model->select()->where('ProductTypeCode = ?', $ProductTypeCode)->fetchAll();
			return $this->initList($data);
		}


		/**
		 * @param $rus_name
		 * @return VidalDocumentModel[]
		 */
		public function getListByRusName($rus_name)
		{
			$data = $this->orm_model->select()->where('RusName = ?', $rus_name)->fetchAll();
			return $this->initList($data);
		}

		/**
		 * @param $rus_name
		 * @return VidalDocumentModel[]
		 */
		public function getListByRusNameClean($rus_name)
		{
			$data = $this->orm_model->select()->where('rus_name_clean = ?', $rus_name)->fetchAll();
			return $this->initList($data);
		}

		/**
		 * @param $rus_name
		 * @return VidalDocumentModel[]
		 */
		public function getListByRusNameCleanPart($rus_name)
		{
			$sql = 'SELECT *
					FROM '.$this->table_name.'
					WHERE rus_name_clean LIKE "'.$rus_name.'%"';

			$data = $this->db->query($sql);

			return $this->initList($data);
		}

		/**
		 * @param $rus_name
		 * @param $dosage_form_list
		 *
		 * @return VidalDocumentModel[]
		 */
		public function getListByRusNameCleanAndDosageFormList($rus_name, array $dosage_form_list)
		{
			$str = '';

			foreach($dosage_form_list as $v)
			{
				$str .= '"'.$this->db->escape($v).'",';
			}
			$str = trim($str,',');

			$sql = 'SELECT *
					FROM '.$this->table_name.'
					WHERE rus_name_clean = "'.$this->db->escape($rus_name).'"
						AND dosage_form IN ('.$str.')';

			$data = $this->db->query($sql);

			return $this->initList($data);
		}

		/**
		 * @param $rus_name
		 * @param $dosage_form_list
		 * @param $dosage_form_size
		 *
		 * @return VidalDocumentModel[]
		 */
		public function getListByRusNameCleanAndDosageFormListAndDosageFormSize($rus_name, array $dosage_form_list, $dosage_form_size)
		{
			$str = '';

			foreach($dosage_form_list as $v)
			{
				$str .= '"'.$this->db->escape($v).'",';
			}
			$str = trim($str,',');

			$sql = 'SELECT *
					FROM '.$this->table_name.'
					WHERE rus_name_clean = "'.$this->db->escape($rus_name).'"
						AND dosage_form IN ('.$str.')
						AND dosage_form_size="'.$this->db->escape($dosage_form_size).'"';

			$data = $this->db->query($sql);

			return $this->initList($data);
		}


		/**
		 * @param $rus_name
		 * @param $unit_size
		 *
		 * @return VidalDocumentModel[]
		 */
		public function getListByRusNameCleanAndUnitSize($rus_name, $unit_size)
		{
			$sql = 'SELECT *
					FROM '.$this->table_name.'
					WHERE rus_name_clean = "'.$this->db->escape($rus_name).'"
						AND unit_size="'.$this->db->escape($unit_size).'"';

			$data = $this->db->query($sql);

			return $this->initList($data);
		}


		public function getOneByRusNameAndDosageForm($ru_name, $dosage_form)
		{
			$data = $this->orm_model->select()->where('RusName = ? AND dosage_form = ?', $ru_name, $dosage_form)->fetchOne();
			return $this->initOne($data);
		}

		public function getListByModelSearchCriteria(ModelSearchCriteria $criteria)
		{
			/**
			 * @var VidalProductSearchCriteria $criteria
			 */
			$search_params = $criteria->getSearchParams();

			if (!$search_params) {
				$search_params = new SearchParams();
			}

			if($criteria->by_page)
			{
				$limit = $criteria->by_page + 1;
				$offset = ($criteria->page - 1) * $criteria->by_page;

				$search_params->setOffsetAndLimit($offset, $limit);
			}

			if($criteria->sort_by && is_array($criteria->sort_by) && count($criteria->sort_by) == 2)
			{
				$search_params->addSortParam($criteria->sort_by['field'], $criteria->sort_by['type']);
			}

			if($criteria->rus_name)
			{
				$search_params->addParam('rus_name_clean LIKE', str_replace('%', '\%', $criteria->rus_name).'%');
			}

			return $this->getListBySearchParams($search_params);
		}

	}