<?php
    /**
     * @var View $this
     * @var OrderModel[] $orders
     * @var ProductModel[] $products
     * @var ProductToOrderModel[] $products_info
     * @var int $status_id
     * @var int $year
     * @var int $offset
     */
?>

<?php foreach($orders as $order): ?>
    <?php $order_year = DateViewHelper::date($order->dt_order, 'only_year'); ?>
    <?php if($order_year != $year): ?>
        <?php $year = $order_year; ?>
        <div class="bg_gradient sort_year"><?php echo $order_year; ?></div>
    <?php endif; ?>

    <div class="bg_gradient mb flo">
        <div class="orders_claster <?php echo ($offset == 0) ? 'active' : ''; ?>">
            <b class="n_order">Заказ № <?php echo $order->id; ?></b>
            <span class="process">в обработке с <b><?php echo DateViewHelper::date($order->dt_order); ?></b>
            </span>
            <span class="showhide"></span>
        </div>
        <div class="showhide_block" <?php echo ($offset == 0) ? 'style="display:block"' : ''; ?>>
            <?php $offset = 1; ?>
            <?php $products = $order->products; ?>
            <?php $products_info = $order->products_info; ?>
            <?php $counter = 0; ?>
            <?php $total_amount = 0; ?>
            <?php foreach($products as $product): ?>
                <?php $this->product = $product; ?>
                <?php $this->product_info = $products_info[$counter]; ?>
                <?php $this->block('shop/blocks/order_item'); ?>
                <?php $total_amount += (int)$products_info[$counter]->amount; ?>
                <?php $counter++; ?>
            <?php endforeach; ?>

            <div class="basket_head person_basket_head">
                <span class="count">Всего: <b><?php echo $total_amount; ?></b>
                    <?php echo ' ' .ProductsAmountHelper::wordForm($total_amount, 'товар'); ?></span>
                <span class="basket_price">на сумму - <b><?php echo $order->total_cost; ?></b> <b>р.</b></span>
            </div>
        </div>
    </div>
<?php endforeach; ?>