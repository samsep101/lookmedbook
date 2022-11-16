<?php if($_SERVER['REQUEST_URI'] != '/shop/catalog/payments'): ?>
    <script>
        $(document).ready(function() {
            $(".bg_gradient .txt .payments-js-link").click(function() {
                window.location = '/shop/catalog/' + $(this).attr('data-href');
            });
        });
    </script>
<?php endif; ?>

<?php $cache_id = 'payments_block'; ?>
<?php if (!$cache->start($cache_id,  'shop_info_blocks')): ?>
    <?php if($data = PageViewHelper::getDescription('payment')): ?>
        <div class="bg_gradient mb_15">
            <?php echo($data); ?>
            <p class="txt"><a class="payments-js-link" href="javascript:void(0)" data-href="payments">Полные условия оплаты</a></p>
        </div>
    <?php endif; ?>
    <?php $cache->end(); ?>
<?php endif; ?>