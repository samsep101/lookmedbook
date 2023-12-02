<?php
    /**
     * @var View $this
     * @var ProductCategoryModel $product_category
     * @var ProductCategoryModel $parent_product_category
     * @var ProductCategoryModel[] $product_categories
     * @var ProductModel[] $products
     */
?>

<script type="text/javascript">
    $(document).ready(function() {
        $(document).ready(function() {
            var productSearchController = new ProductSearchController();
            productSearchController.product_category = <?php echo isset($product_category) ? $product_category->getId() : 0; ?>;
            productSearchController.is_leader = <?php echo isset($is_leader) ? $is_leader : 0; ?>;
            productSearchController.by_page = <?php echo isset($by_page) ? $by_page : 10; ?>;
            productSearchController.pattern = <?php echo isset($pattern) ? "'$pattern'" : "'*'"; ?>;
            productSearchController.product_itself = '';
            productSearchController.init();

            var liveSearchController = new ProductLiveSearchController(<?php echo isset($pattern) ? $pattern : ''; ?>);
            liveSearchController.init();
        });
    });
</script>

<div class="magazine inner-2 flo">
    <?php $this->block('shop/blocks/live_search'); ?>

    <?php $this->product_category = $product_category; ?>
    <?php $this->parent_product_category = $parent_product_category; ?>
    <?php $this->block('shop/blocks/breadcrumbs'); ?>

    <div class="block_left" style="width: auto; float: none;">
        <?php if(isset($parent_product_category) || $product_categories): ?>
            <div class="bg_gradient catalog_more_h flo">
        <?php endif; ?>
            <h3>
                <?php if(isset($parent_product_category) && $parent_product_category): ?>
                    <?php echo $parent_product_category->name; ?>
                <?php elseif((isset($product_categories) && $product_categories)): ?>
                    <?php echo $product_category->name; ?>
                <?php endif; ?>

            </h3>
                <?php if(isset($product_category) && $product_category && !$product_categories && !$parent_product_category): ?>
                <h4>
                    <a class="back" href="/shop/catalog">к списку лекарств</a>
                </h4>
                <ul class="catalog_more_ul">
                    <?php foreach($product_categories as $item): ?>
                        <li><a href="<?php echo ProductCategoryLinkViewHelper::getLink($item); ?>"><?php echo $item->name; ?> (<?php echo $item->products_count; ?>)</a></li>
                    <?php endforeach; ?>
                </ul>
            <?php endif; ?>
            <?php if(isset($parent_product_category) && $parent_product_category && $product_category): ?>
                <h4>
                    <a class="back" href="<?php echo ProductCategoryLinkViewHelper::getLink($parent_product_category); ?>">
                        к списку категорий
                    </a>
                </h4>
                <ul class="catalog_more_ul">
                    <?php foreach($parent_product_category->children as $item): ?>
                        <li <?php echo ($item->getId() == $product_category->getId()) ? 'class="active"' : ''; ?>>
                            <a href="<?php echo ProductCategoryLinkViewHelper::getLink($item); ?>"><?php echo $item->name; ?> (<?php echo $item->products_count; ?>)</a>
                        </li>
                    <?php endforeach; ?>
                </ul>
            <?php endif; ?>
        <?php if(isset($parent_product_category) || $product_categories): ?>
            </div>
        <?php endif; ?>

        <?php if(isset($product_category) && $product_category): ?>
            <h4><?php echo $product_category->name; ?>
                <?php if(isset($product_category) && $product_category && !$product_categories): ?>
                    <a class="back" href="/shop/catalog">к списку лекарств</a>
                <?php endif; ?>
            </h4>
        <?php endif; ?>

        <a class="load-next-page view-more" href="javascript:void(0);" data-page="1"><i class="icon-loader"></i></a>
        <?php if ($product_category->description): ?>
            <div class="bg_gradient about_good">
                <p class="h-txt"><?php echo $product_category->name; ?></p>
                <p class="txt"><?php echo $product_category->description; ?></p>
            </div>
        <?php endif; ?>
    </div>

    <?php /*
        <div class="block_right">
            <?php $this->block('shop/blocks/basket_info'); ?>
            <?php $this->block('shop/blocks/orders_phone'); ?>
            <?php $this->block('shop/blocks/orders'); ?>
            <?php $this->block('shop/blocks/payments'); ?>
            <?php $this->block('shop/blocks/choice'); ?>
        </div>
    */?>

</div>