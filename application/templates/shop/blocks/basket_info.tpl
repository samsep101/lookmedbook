<script type="text/javascript">
	$(document).ready(function(){
		var basket_block_controller = new BasketBlockController(product_basket);
		basket_block_controller.container = '.good_basket';
		basket_block_controller.init();
	});
</script>

<div class="bg_gradient mb_15 flo good_basket">
    <p class="h-txt h-bask"><a href="/shop/basket">Корзина</a></p>
    <p class="txt">
        <span class="basket_n"></span> <small><span class="product_word">товаров</span>:&nbsp;</small><span class="basket_p"></span> р.
    </p>
    <p class="txt"><a href="/shop/basket">перейти в корзину</a></p>
    <input class="btn-appoint order_btn" type="button" value="Оформить заказ" onclick="window.location='/shop/basket/order'"/>
    <div class="basket-limit">Минимальный заказ от 500 р.</div>
</div>