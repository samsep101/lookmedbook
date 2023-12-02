<?php
    /**
     * @var ProductModel $product
     * @var ProductToOrderModel $product_info
     */
?>
<div class="basket_good flo">
    <a class="basket_good_image" href="<?php echo ProductLinkViewHelper::getLink($product)?>">
        <img src="<?php echo ProductAvatarViewHelper::getView($product->getId(), 160, 137); ?>" alt="Изображение"/>
    </a>
    <div class="about">
        <p class="goodname">
            <a href="<?php echo ProductLinkViewHelper::getLink($product)?>"><?php echo $product->clean_name; ?></a>
        </p>
        <p class="goodprod"><?php echo $product->manufacturer->name; ?></p>
    </div>
    <div class="goodcount">
        <p class="hb">Количество</p>
        <span class="big"><?php echo $product_info->amount; ?></span>
    </div>
    <div class="goodprice">
        <p class="hb">Стоимость</p>
        <span class="product_price">
            <span class="total_price"><?php echo (int)$product_info->amount * (int)$product_info->price; ?></span>
            <span>р.</span>
        </span>
    </div>
</div>