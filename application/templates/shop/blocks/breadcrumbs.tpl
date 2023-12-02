<?php
    /**
     * @var ProductCategoryModel $product_category
     * @var ProductCategoryModel $parent_product_category
     * @var ProductModel $product
     */
?>

<li><a href="/shop/catalog">Лекарства</a></li>
<?php if(isset($parent_product_category) && $parent_product_category): ?>
    <li>
        <a href="<?php echo ProductCategoryLinkViewHelper::getLink($parent_product_category); ?>">
            <?php echo $parent_product_category->name; ?>
        </a>
    </li>
<?php endif; ?>
<?php if(isset($product_category) && $product_category): ?>
    <li>
        <a href="<?php echo ProductCategoryLinkViewHelper::getLink($product_category); ?>">
            <?php echo $product_category->name; ?>
        </a>
    </li>
<?php endif; ?>
<?php if(isset($product) && $product): ?>
    <li>
        <a href="javascript:void(0)">
            <?php echo $product->full_name; ?>
        </a>
    </li>
<?php endif; ?>