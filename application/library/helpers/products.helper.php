<?php
    class ProductsHelper{

        public static function setProductAction($product_info)
        {
            $result = FALSE;
            if($action = ActionModel::getByProduct($product_info['id']))
            {
                $result = $action;
            }
            elseif($action = ActionModel::getByBrand($product_info['fk_producer']))
            {
                $result = $action;
            }
            elseif($action = ActionModel::getByCategory($product_info['fk']))
            {
                $result = $action;
            }
            elseif($action = ActionModel::getForAll())
            {
                $result = $action;
            }

            return $result;
        }

        public static function setProductNovelty($product_info)
        {
            $result = FALSE;
            if($novelty = NoveltyModel::getByProduct($product_info['id']))
            {
           	    $result['novelty'] = $novelty['id'];
                $result['novelty_type'] =  $novelty['novelty_type'];
            }
            elseif($novelty = NoveltyModel::getByBrand($product_info['fk_producer']))
            {
                $result = $novelty;
            }
            elseif($novelty = NoveltyModel::getByCategory($product_info['fk']))
            {
                $result = $novelty;
            }
            elseif($novelty = NoveltyModel::getForAll())
            {
                $result = $novelty;
            }

            return $result;
        }

        public static function setProductFavorite($product_info)
        {
            $result = FALSE;
            if($favorite = FavoriteModel::getByProduct($product_info['id']))
            {
                $result = $favorite;
            }
            elseif($favorite = FavoriteModel::getByBrand($product_info['fk_producer']))
            {
                $result = $favorite;
            }
            elseif($favorite = FavoriteModel::getByCategory($product_info['fk']))
            {
                $result = $favorite;
            }
            elseif($favorite = FavoriteModel::getForAll())
            {
                $result = $favorite;
            }

            return $result;
        }

        public static function getProductsFilter($category_id)
        {
            $category = CatModel::getById($category_id);

            if (!count($category))
                throw new Exception('Категории с таким id не существует');

            if ($category['params_cfg_name'])
            {
                $product_filter = FilterSettingsModel::getSettings($category['id']);

                foreach ($product_filter as $field_name =>  &$filter_item)
                {
                    if (!isset($filter_item['filter_type_id'])) continue;
                    if ($filter_item['filter_type_id'] == FilterTypesModel::DROPDOWN_LIST)
                    {

                        $filter_item['values'] = FilterValuesModel::getFieldValues($category['id'], $field_name);
                    }
                    if ($filter_item['filter_type_id'] == FilterTypesModel::FROM_TO_WITH_SLIDER)
                    {
                        $filter_item['from'] = ProductFeaturesModel::getMinFieldValue($category['params_cfg_name'], $field_name);
                        $filter_item['to'] = ProductFeaturesModel::getMaxFieldValue($category['params_cfg_name'], $field_name);
                    }
                }
            } else {
                $product_filter = FALSE;
            }

            return $product_filter;
        }
    }