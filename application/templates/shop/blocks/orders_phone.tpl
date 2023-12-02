<?php $cache_id = 'orders_phone_block'; ?>
<?php if (!$cache->start($cache_id,  'shop_info_blocks')): ?>
    <?php if($data = PageViewHelper::getDescription('contacts')): ?>
        <div class="bg_gradient mb_15">
            <?php echo $data;?>
        </div>
    <?php endif; ?>
    <?php $cache->end(); ?>
<?php endif; ?>