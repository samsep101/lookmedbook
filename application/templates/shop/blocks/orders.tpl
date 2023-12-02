<?php if($_SERVER['REQUEST_URI'] != '/shop/catalog/delivery'): ?>
    <script>
        $(document).ready(function() {
            $(".bg_gradient .txt .delivery-js-link").click(function() {
                window.location = '/shop/catalog/' + $(this).attr('data-href');
            });
        });
    </script>
<?php endif; ?>

<?php $cache_id = 'delivery_block'; ?>
<?php if (!$cache->start($cache_id,  'shop_info_blocks')): ?>
    <?php if($data = PageViewHelper::getDescription('shipping')): ?>
        <div class="bg_gradient mb_15">
            <?php echo $data;?>
            <p class="txt"><a class="delivery-js-link" href="javascript:void(0)" data-href="delivery">Полные условия доставки</a></p>
        </div>
    <?php endif; ?>
    <?php $cache->end(); ?>
<?php endif; ?>