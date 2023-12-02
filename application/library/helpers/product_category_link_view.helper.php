<?php
    class ProductCategoryLinkViewHelper
    {
        public static function getLink(ProductCategoryModel $model)
        {
            if($model->alias)
            {
                $str = '';
                if($model->parent && $model->parent->name != 'Лекарства и БАДы')
                {
                    $str = $model->parent->alias.'/';
                }
                $str .= $model->alias;

                $str = '/shop/catalog/'.$str;
                return $str;
            }
            else
            {
                return '/shop/catalog/'.$model->getId();
            }
        }
    }