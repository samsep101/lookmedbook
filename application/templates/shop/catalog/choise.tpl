<script type="text/javascript">
    $(document).ready(function() {
        var productSearchController = new ProductSearchController();
        productSearchController.product_category = 0;
        productSearchController.is_leader = 0;
        productSearchController.by_page = 8;
        productSearchController.pattern = '*';
        productSearchController.product_itself = '';
        productSearchController.init();
    });
</script>

<div class="magazine inner-2 flo">
    <?php $this->pattern = isset($pattern) ? $pattern : "'*'"; ?>
    <?php $this->block('shop/blocks/live_search'); ?>

    <h2>Почему выбирают нас</h2>
    <div class="block_right">

        <?php $this->block('shop/blocks/basket_info'); ?>
        <?php $this->block('shop/blocks/orders_phone'); ?>
        <?php $this->block('shop/blocks/payments'); ?>
        <?php $this->block('shop/blocks/orders'); ?>
        <?php $this->block('shop/blocks/choice'); ?>
    </div>
    <div class="block_left">
        <div class="bg_gradient payments">
            <?php echo PageViewHelper::getContent('why_we');?>
        </div>
    </div>
</div>