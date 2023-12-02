<?php
	/**
	 * @property int $id
	 * @property string $name
	 * @property string $alias
	 * @property int $parent_id
	 * @property ProductCategoryModel $parent
	 * @property ProductCategoryModel[] $children
	 * @property int $base_id
	 * @property ProductCategoryModel $base
	 * @property bool $is_active
	 * @property int $image_id
	 * @property ImageModel $image
	 * @property string $description
	 * @property int $products_count
	 *
	 * @property int $children_count
     * @property ProductCategoryDescriptionManager $product_category_description_manager
     * @property ProductCategoryDescriptionModel $product_category_description
     * @property ProductCategoryModel[] $all_children
     * @property int $products_total_count
	 */
	class ProductCategoryModel extends DynamicModel
	{

		protected function  _field_children()
		{
			if(!isset($this->children))
			{
				/**
				 * @var ProductCategoryManager $manager
				 */
				$manager = $this->getManager();
				$this->children = $manager->getActiveListByParentId($this->getId());
			}

			return $this->children;
		}

		protected function _field_base()
		{
			return $this->getManager()->getOneById($this->base_id);
		}

		protected function _field_parent()
		{
			return $this->getManager()->getOneById($this->parent_id);
		}

		protected function _field_product_category_description()
		{
			/**
			 * @var ProductCategoryDescriptionManager $product_category_description_manager
			 */
			$product_category_description_manager = ModelManagerFactory::getByName('product_category_description');
			$product_category_description = $product_category_description_manager->getOneByProductCategoryId($this->getId());

			return $product_category_description;
		}

		protected function _field_description()
		{
			if(!isset($this->description))
			{
				/**
				 * @var ProductCategoryDescriptionManager $product_category_description_manager
				 */
				$product_category_description_manager = ModelManagerFactory::getByName('product_category_description');
				$product_category_description = $product_category_description_manager->getOneByProductCategoryId($this->getId());
				$this->description = '';

				if($product_category_description)
				{
					$this->description = $product_category_description->text;
				} else {
					$product_category_description = new ProductCategoryDescriptionModel();
					$product_category_description->product_category_id = $this->getId();
					$product_category_description->save();
				}
			}

			return $this->description;
		}

        protected function _field_all_children()
        {
            if(!isset($this->all_children))
            {
                /**
                 * @var ProductCategoryManager $manager
                 */
                $manager = $this->getManager();
                $this->all_children = $manager->getListByParentId($this->getId());
            }

            return $this->all_children;
        }
	}