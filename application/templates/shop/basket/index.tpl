<?php
/**
 * @var View $this
 * @var ProductBasket $product_basket
 */
?>
<script type="text/javascript">
	$(document).ready(function(){
		var page_controller = new BasketPageController(product_basket);
		page_controller.init();
	});
</script>
<div class="magazine inner-2 flo">
    <h2 class="h-basket">Корзина<a class="back" href="/shop/catalog">Вернуться к покупкам</a></h2>
	<?php if($product_basket->getTotalCount() == 0): ?>
		<div class="info-message">
			Корзина пуста
		</div>
	<?php else: ?>
		<div class="bg_gradient basket flo">
			<div class="basket_head">
				<span class="count">Всего: <span class="count_all_good"></span><span class="all_count_word"></span></span><span class="basket_price price_all_good"></span>&nbsp;<span>р.</span>
				<input class="btn-appoint" type="button" value="Оформить заказ" onclick="window.location='/shop/basket/order'">
				<a class="basket_clear" href="javascript:void(0)">Очистить заказ</a>
                <div class="basket-limit">Минимальный заказ - 500 р.</div>
			</div>

			<?php if ($product_basket->getProductList()):?>
				<?php foreach ($product_basket->getProductList() as $product):?>
					<script type="text/javascript">
						$(document).ready(function(){
							<?php $container = '.basket-good-' .$product['id']; ?>
							var block_controller = new BasketItemController(window.product_basket);
							block_controller.container = '<?php echo $container; ?>';
							block_controller.product_id = <?php echo $product['id']; ?>;
							block_controller.max_count = <?php echo isset($product['quantity']) ? (int)$product['quantity'] : ProductHelper::getQuantityByProductId($product['id']); ?>;
							block_controller.price = <?php echo isset($product['price']) ? (int)$product['price'] : 0; ?>;
							block_controller.repair_block_container = '.basket_repair_<?php echo $product['id']; ?>';
							block_controller.init();
						});
					</script>

					<div class="basket_good basket_repair_<?php echo $product['id']; ?> flo" style="display: none;">
						<p class="deleted"><?php echo $product['name']; ?> <a class="repair" href="javascript:void(0)">Восстановить</a></p>
					</div>
					<div class="basket_good basket-good-<?php echo $product['id']?> buy flo" data-id="<?php echo $product['id']; ?>">

						<a class="product-avatar" href="<?php echo ProductLinkViewHelper::getLinkById($product['id'])?>"><img src="<?php echo ProductAvatarViewHelper::getView($product['id']); ?>"/></a>
						<div class="about">
							<p class="goodname"><a href="<?php echo ProductLinkViewHelper::getLinkById($product['id'])?>"><?php echo $product['name']?></a></p>
							<p class="goodprod"><?php echo (isset($product['manufacturer'])) ? $product['manufacturer'] : ''; ?></p>
						</div>
						<div class="goodcount">
							<p class="hb">Количество</p>
							<ul class="numeric">
								<li class="decrement">-</li>
								<li class="count"><?php echo $product['count']?></li>
								<li class="increment">+</li>
							</ul>
						</div>
						<div class="goodprice">
							<p class="hb">Стоимость</p>
							<span class="product_price"><b class="gprice"><?php echo $product['price']?></b><span class="total_price"><?php echo $product['count']*$product['price']?></span>&nbsp;<span>р.</span></span>
						</div>
						<span class="del_good"></span>
					</div>
				<?php endforeach;?>
			<?php endif;?>



			<div class="basket_total">
				<div class="shadow_check"></div>
				<p class="total_line">
					<span class="count">ВСЕГО: <span class="like_i"><span class="count_all_good"></span> <span class="product_word">товаров</span> на</span></span><span class="basket_price"><span class="price_all_good">0</span>&nbsp;р.</span>
					<input class="btn-appoint" type="button" value="Оформить заказ" onclick="window.location='/shop/basket/order'; return false;">
				</p>
			</div>
		</div>
	<?php endif; ?>
</div>