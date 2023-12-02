<?php
    /**
     * @var View $this
     * @var ProductModel[] $products
     * @var bool $not_found
     */
?>

<?php if(isset($not_found) && $not_found): ?>
    <div class="error-plate">По данному запросу не найдено лекарств</div>
    <?php if($products): ?>
        <h2>Популярные лекарства</h2>
    <?php endif; ?>
<?php endif; ?>

<?php if($products): ?>
    <?php $counter = 1; ?>
    <div class="product_list">
    <?php foreach($products as $product): ?>
        <?php if($counter % 5 == 1): ?>
            <ul class="goods_blocks">
        <?php endif; ?>

        <?php $this->product = $product; ?>
        <?php $this->block('shop/catalog/product_card'); ?>

        <?php if($counter % 5 == 0): ?>
            </ul>
        <?php endif; ?>

        <?php $counter++; ?>
    <?php endforeach; ?>
    </div>
<?php endif; ?>