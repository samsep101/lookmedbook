<?php
	class ProductCategoryManager extends AliasManager
	{
		protected $table_name = "product_category";
		protected $model_name = "ProductCategoryModel";

		public function afterSave(DynamicModel $product_category)
		{
			/**
			 * @var ProductCategoryModel $product_category
			 */
			if($product_category->description)
			{
				$product_category_description = $product_category->product_category_description;
				if(!$product_category_description)
				{
					$product_category_description = new ProductCategoryDescriptionModel();
					$product_category_description->product_category_id = $product_category->getId();
				}
				$product_category_description->text = $product_category->description;

				$product_category_description->save();
			}
		}

		/**
		 * @var int $parent_id
		 * @return ProductCategoryModel[]
		 */
		public function getListByParentId($parent_id)
		{
			$data = $this->orm_model->select()->where('parent_id = ?', (int)$parent_id)->order('name ASC')->fetchAll();
			return $this->initList($data);
		}

		/**
		 * @var int $parent_id
		 * @return ProductCategoryModel[]
		 */
		public function getActiveListByParentId($parent_id)
		{
			$data = $this->orm_model->select()->where('parent_id = ? AND is_active = 1', (int)$parent_id)->order('name ASC')->fetchAll();
			return $this->initList($data);
		}

		/**
		 * @var int $parent_id
		 * @return ProductCategoryModel
		 */
		public function getOneByServiceId($service_id)
		{
			$data = $this->orm_model->select()->where('service_id = ?', $service_id)->fetchOne();
			return $this->initOne($data);
		}

		/**
		 * @return ProductCategoryModel[]
		 */
		public function getRootList()
		{
			$data = $this->orm_model->select()->where('parent_id IS NULL')->fetchAll();
			return $this->initList($data);
		}

		/**
		 * @param $name
		 * @return ProductCategoryModel|null
		 */
		public function getOneByName($name)
		{
			$data = $this->orm_model->select()->where('name = ?', $name)->fetchOne();
			return $this->initOne($data);
		}

		/**
		 * @param ModelSearchCriteria $criteria
		 *
		 * @return ProductCategoryModel[]
		 */
		public function getListByModelSearchCriteria(ModelSearchCriteria $criteria)
		{
			/**
			 * @var ProductCategorySearchCriteria $criteria
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

			if($criteria->is_active)
			{
				$search_params->addParam('is_active', 1);
			}

			if($criteria->name)
			{
				$search_params->addParam('name LIKE', '%'.$criteria->name.'%' );
			}

			switch($criteria->sort_by)
			{
				case 'name':
					$search_params->addSortParam('name', 'ASC');
					break;
			}

			return $this->getListBySearchParams($search_params);
		}

		public function updateActiveStatus()
		{
			$sql = 'UPDATE '.$this->table_name.' c
					SET products_count = (
						SELECT COUNT(*)
						FROM product p
						WHERE p.product_category_id =c.id
							AND p.is_active = 1)';

			$this->db->query($sql);



			$sql = 'UPDATE '.$this->table_name.' c
					LEFT OUTER JOIN '.$this->table_name.' c1 ON c1.parent_id = c.id
					SET c.is_active = IF(
						(c.products_count > 0)
						OR (c1.products_count > 0),
						1,
						0)';
			$this->db->query($sql);


            $sql = 'UPDATE '.$this->table_name.' c
					SET products_total_count = (
						SELECT COUNT(*)
						FROM product p
						WHERE p.product_category_id =c.id)';

            $this->db->query($sql);
		}

        public function updateActiveStatusByProductCategoryId($product_category_id)
        {
            $sql = 'UPDATE '.$this->table_name.' c
					SET products_count = (
						SELECT COUNT(*)
						FROM product p
						WHERE p.product_category_id =c.id
							AND p.is_active = 1
							AND p.fill_information_status_id = '.FillInformationStatusModel::OK.'
							AND p.is_image_confirmed = 1)
					WHERE id = ' .(int)$product_category_id;

            $this->db->query($sql);

            $sql = 'UPDATE '.$this->table_name.'
					SET is_active = (IF(products_count > 0, 1, 0))';
            $this->db->query($sql);
        }
	}
