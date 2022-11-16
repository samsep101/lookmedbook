<?php
    /**
     * @var ProductModel[] $products
     * @var ProductModel $product
     */
?>

<?php foreach($products as $product): ?>
    <li style="font-size: 18px; text-align: left">
        <?php $destination = ($product->alias) ? $product->alias : $product->getId(); ?>
        <a href="javascript:void(0)" data-id="<?php echo $destination; ?>">
            <div class="search-product-name"><?php echo $product->full_name; ?></div>
            <?php /* <div class="search-product-price"><?php echo ((int)($product->price)); ?> р.</div> */ ?>
            <div class="search-product-manufacturer"><?php echo $product->manufacturer->name; ?></div>
        </a>
    </li>
<?php endforeach; ?>