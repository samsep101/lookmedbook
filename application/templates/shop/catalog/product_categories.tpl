<?php
    /**
     * @var View $this
     * @var ProductCategoryModel $product_category
     * @var ProductCategoryModel[] $product_categories
     */
?>
<ul class="catalog_list">
    <?php if($product_category && $product_category->children): ?>
        <?php foreach($product_categories as $child_product_category): ?>
            <li>
                <div class="category-image"></div>
                <a href="<?php echo ProductCategoryLinkViewHelper::getLink($child_product_category); ?>"><?php echo $child_product_category->name; ?></a></li>
        <?php endforeach; ?>
    <?php endif; ?>
</ul>