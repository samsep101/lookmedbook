<?php
    class ProductsPageTitleGeneratorHelper
    {
        public static function productCategoryTitle(ProductCategoryModel $product_category, ProductCategoryModel $parent_product_category = null)
        {
            $title = 'Лекарства. ';

            if($parent_product_category)
            {
                $title .= $parent_product_category->name .'. ';
            }

            $title .= $product_category->name;

            return $title;
        }

        public static function productTitle(ProductModel $product)
        {
            $title = $product->ru_name;

            if($product->product_dosage_form_id)
            {
                $title .= ', '.$product->product_dosage_form->name;
            }

            if($product->size_of_a_unit)
            {
                $title .= ', '.$product->size_of_a_unit;
            }

            return $title;
        }
    }
