<?php
    /**
     * @var View $this
     * @var string $image;
     * @var ProductModel $product;
     */
?>

<script type="text/javascript">
    $(document).ready(function(){
        <?php $container = '.product-card-' .$product->getId(); ?>
        var block_controller = new ProductCardController(window.product_basket, "<?php echo $container; ?>");
        block_controller.product_id = <?php echo $product->getId(); ?>;
		block_controller.price = <?php echo (float)$product->price; ?>;
		block_controller.max_count = <?php echo (int)$product->quantity; ?>;
        block_controller.init();
    })
</script>

<?php $cache_id = 'product_card_' .$product->getId(); ?>
<?php if (!$cache->start($cache_id,  'product_cards')): ?>
    <li class="bg_gradient buy product-card product-card-<?php echo $product->getId(); ?>" data-id="<?php echo $product->getId(); ?>">
        <a href="<?php echo ProductLinkViewHelper::getLink($product)?>">
            <img src="<?php echo ProductAvatarViewHelper::getView($product->getId(), 130, 130) ?>" />
        </a>
        <p class="good_name"><a href="<?php echo ProductLinkViewHelper::getLink($product)?>"><?php echo $product->full_name; ?></a>
            <?php if($product->manufacturer): ?>
                <?php echo $product->manufacturer->name; ?>
            <?php endif; ?>
        </p>
        <?php /*
            <p class="inbasket"><a href="/shop/basket">В корзине на сумму:</a> <span class="total_price"><?php echo (float)$product->price; ?></span>&nbsp;р.</p>

            <div class="price-btn">
                <b class="gprice"><?php echo (float)$product->price; ?></b>&nbsp;<b>р.</b>
                <input class="btn-w" type="button" value="Купить"/>
                <span class="counter">
                    <span class="delete-from-basket">x</span>
                    <ul class="numeric">
                        <li class="decrement">-</li>
                        <li class="count">1</li>
                        <li class="increment">+</li>
                    </ul>
                </span>
            </div>
            <div class="product-card-message">Перед применением, проконсультируйтесь с врачом</div>
        */?>
    </li>
    <?php $cache->end(); ?>
<?php endif; ?>