<?php
	/**
	 * @var View $this
	 * @var ProductBasket $product_basket
	 */
?>
	<div class="magazine inner-2 flo">
	<?php if($product_basket->getTotalCount() == 0): ?>
		<div class="info-message">
			Товаров в корзине нет. Перейдите в <a href="/shop/catalog">каталог</a> для добавления товаров в корзину.
		</div>
	<?php else: ?>
		<div class="info-message">
			Доставка товаров на сумму менее 500 р. не осуществляется.
		</div>
	<?php endif; ?>
    </div>