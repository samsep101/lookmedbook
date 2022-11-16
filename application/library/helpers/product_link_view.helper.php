<?php
class ProductLinkViewHelper
{
    public static function getLink(ProductModel $model)
    {
        if($model->alias)
        {
            return '/shop/product/'.$model->alias;
        }
        else
        {
            return '/shop/product/'.$model->getId();
        }
    }

    public static function getLinkById($id)
    {
        /**
         * @var ProductManager $product_manager
         * @var ProductModel $product
         */

        $product_manager = ModelManagerFactory::getByName('product');
        $product = $product_manager->getOneById($id);

        if($product)
        {
            if($product->alias)
            {
                return '/shop/product/'.$product->alias;
            }
            else
            {
                return '/shop/product/'.$product->getId();
            }
        }
        else
        {
            return false;
        }
    }
}