<?php
    class CategoriesIdsHelper
    {
        public static function getCategoriesIds($product_categories)
        {
            $product_categories_ids = array();

            foreach($product_categories as $item)
            {
                $product_categories_ids[] = $item->getId();
            }

            return $product_categories_ids;
        }
    }