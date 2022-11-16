<?php
	class ProductElasticSearchFormatter implements IElasticSearchFormatter
	{
		/**
		 * @param DynamicModel $model
		 *
		 * @return array
		 */
		public function toElasticSearchView(DynamicModel $model)
		{
			/**
			 * @var ProductModel $model
			 */
			$result = array();

			$result['id'] = $model->getId();
			$result['full_name'] = $model->clean_name;
			$result['full_name_sort'] = $model->clean_name;
			$result['product_category'] = $model->product_category_id;
			$result['is_active'] = (bool)$model->is_active;
			$result['is_leader'] = (bool)$model->is_leader;
			$result['is_leader'] = (bool)$model->is_leader;
			$result['manufacturer'] = $model->manufacturer_id;
			$result['image_find_status'] = $model->image_find_status_id;
			$result['fill_information_status'] = $model->fill_information_status_id;

			return new \Elastica\Document($model->getId(), $result);
		}

	}