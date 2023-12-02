<?php
/**
 * @var View $this
 * @var ProductBasket $product_basket
 * @var AccountModel $current_account
 * @var OrderModel $last_order
 */
?>
	<script type="text/javascript">
		$(document).ready(function(){
			var form_controller = new OrderFormController(product_basket);
            form_controller.shipping_type = <?php echo (isset($last_order) && $last_order) ? $last_order->shipping_type_id : 0;  ?>;
			form_controller.init();
        });
	</script>

<div class="magazine inner-2 flo" xmlns="http://www.w3.org/1999/html" xmlns="http://www.w3.org/1999/html">
    <h2 class="h-basket">Оформление заказа<a class="back" href="/shop/basket">Вернуться в корзину</a></h2>
    <div class="order_tabs">
        <ul>
            <li class="active"><p><b>Шаг 1</b></p><p>Способ доставки</p></li>
            <li><p><b>Шаг 2</b></p><p>Контактные данные</p></li>
        </ul>
        <div class="box">
            <div class="section visible">
                <p class="h-txt">Выберете способ доставки и оплаты:</p>
                <p class="for_radio linetop"><input id="rb1" class="styled" name="name1" type="radio" data-id="1"/><label for="rb1">Доставка курьером по Москве</label></p>
                <div class="radio_hide">
                    <p class="for_radio_child"><input id="rb2" class="styled" name="shipping_type_id" value="1" type="radio" data-id="1"/>
						<label for="rb2">Доставка по Москве в пределах МКАД + С.<br/>Бутово:
							<b>
								<?php if ($product_basket->getTotalPrice() >= 900): ?>
									бесплатно
								<?php else: ?>
									150р.
								<?php endif; ?>
							</b>
						</label>
					</p>
                    <p class="for_radio_child"><input id="rb3" class="styled" name="shipping_type_id" value="2" type="radio" data-id="2"/><label for="rb3">Доставка в отдаленные районы Москвы: <b> 300р.</b><br/><i>Жулебино, Митино, Южное Бутово, Солнцево, Новокосино и др.</i></label></p>
                </div>

                <p class="for_radio linetop">
                    <input id="rb4" class="styled" name="name1" type="radio" data-id="4" <?php echo ($product_basket->getTotalPrice() < 900) ? 'disabled' : ''; ?>/>
                    <label for="rb4" <?php echo ($product_basket->getTotalPrice() < 900) ? 'class="disabled"' : ''; ?>>Доставка курьером по Московской области</label>
                    <?php if($product_basket->getTotalPrice() < 900): ?>
                        <span>Минимальный заказ 900 р.</span>
                    <?php endif; ?>
                </p>
                <div class="radio_hide">
                    <p class="for_radio_child">
                        <input id="rb5" class="styled" name="shipping_type_id" value="4" type="radio" data-id="4"/>
                        <label for="rb5">Доставка в Подмосковье (до 10 км от МКАД): <b> 250р.</b></label>
                    </p>
                    <p class="for_radio_child">
                        <input id="rb6" class="styled" name="shipping_type_id" value="5" type="radio" data-id="5"/>
                        <label for="rb6">Доставка в Подмосковье (более 10 км от МКАД): <b>от 400р.</b></label>
                    </p>
                </div>

                <p class="for_radio linetop">
                    <input id="rb7" class="styled" name="name1" value="5" type="radio" data-id="6" <?php echo ($product_basket->getTotalPrice() < 2000) ? 'disabled' : ''; ?>/>
                    <label for="rb7" <?php echo ($product_basket->getTotalPrice() < 2000) ? 'class="disabled"' : ''; ?>>Доставка по России (почтой EMS)</label>
                    <?php if($product_basket->getTotalPrice() < 2000): ?>
                        <span>Минимальный заказ 2000 р.</span>
                    <?php endif; ?>
                </p>
                <div class="radio_hide">
                    <p class="for_radio_child">
                        <input id="rb8" class="styled" name="shipping_type_id" value="10" type="radio" data-id="6"/>
                        <label for="rb8">Доставка до 1 кг в прочие населенные пункты РФ: <b>649 р.</b></label>
                    </p>
                    <p class="for_radio_child">
                        <input id="rb9" class="styled" name="shipping_type_id"  value="11" type="radio" data-id="6"/>
                        <label for="rb9">Доставка свыше 1 кг определяется в соответствии с <a class="tarif_link" href="javascript:void(0)">тарифами и весом</a>.</label>
                    </p>
                </div>

                <?php if(isset($last_order) && $last_order): ?>
                    <?php $address = $last_order->address ?>
                <?php else: ?>
                    <?php $address = ''; ?>
                <?php endif; ?>
                <p class="h-txt linetop">Адрес доставки:*</p>
                <textarea placeholder="г. Москва ул. Большая-Никитская дом 5 кв.23" name="address"><?php echo($address); ?></textarea>
                <input class="btn-appoint further" type="button" value="Далее">
            </div>
            <div class="section">
                <p class="h-txt">Введите контактные данные:</p>
                <lable class="pre_inp">Имя:*</lable>
                <input class="w100" type="text" name="name" placeholder="Введите имя"
                        <?php if(isset($last_order) && $last_order): ?>
                            value="<?php echo $last_order->name; ?>"
						<?php elseif($current_account): ?>
							value="<?php echo $current_account->full_name; ?>"
						<?php endif; ?>
						/>
                <lable class="pre_inp">Телефон:*</lable>
                <input class="w100 mask placeholder" type="text" name="phone_number" placeholder="+7-___-___-__-__"
						<?php if(isset($last_order) && $last_order): ?>
                            value="<?php echo $last_order->phone_number; ?>"
                        <?php elseif($current_account && $current_account->phones): ?>
							value="<?php echo $current_account->phones[0]->phone; ?>"
						<?php endif; ?>
						>
                <lable class="pre_inp">Хотите получить данные о заказе на e-mail?:</lable>
                <input class="w100" type="text" name="email" placeholder="mail@example.ru"
                        <?php if(isset($last_order) && $last_order): ?>
                            value="<?php echo $last_order->email; ?>"
						<?php elseif($current_account): ?>
							value="<?php echo $current_account->email; ?>"
						<?php endif; ?>
						>
                <lable class="pre_inp">Комментарий к заказу:</lable>
                <textarea name="comment" placeholder="Здесь вы можете оставить комментарий к заказу"></textarea>
                <input class="btn-appoint order_ready" type="button" value="Готово">
            </div>
            <div class="section step_fin">
                <p class="h-txt">Заказ успешно оформлен!</p>
                <p class="h-txt">Номер заказа: <span class="order-number"></span>.</p>
                <p class="txt">Курьер свяжется с вами в ближайшее время.</p>
                <p class="txt">Данные о заказе отправлены на Вашу электронную почту.</p>
                <p class="txt"><br/>Следить за статусом исполнения заказа вы можете в личном кабинете.</p>
                <input class="btn-appoint" type="button" value="Личный кабинет" onclick="window.location='/account/orders';">
            </div>
        </div>
    </div>
    <div class="fr">
        <div class="basket_total order_total">
            <div class="shadow_check"></div>
            <ul class="good_inbasket">
			<?php if ($product_basket->getProductList()):?>
				<?php foreach ($product_basket->getProductList() as $product_info):?>
				<li><span class="namegood"><?php echo $product_info['name'];?></span> <span class="pricegood"><span class="order_n"><?php echo $product_info['count'];?></span> шт. = <span class="order_p"><?php echo $product_info['count']*$product_info['price'];?></span> р.</span></li>
				<?php endforeach;?>
			<?php endif;?>
            </ul>
			<p class="total_line">
				<span class="count">ВСЕГО: <span class="like_i"><span class="order_all_n"><?php echo $product_basket->getTotalCount();?></span> <?php echo ProductsAmountHelper::wordForm($product_basket->getTotalCount(), 'товар'); ?>   на</span></span><span class="order_all_p"><?php echo $product_basket->getTotalPrice(); ?></span> р.
			</p>

        </div>
        <div class="bg_gradient ontheside forstep1">
            <p>Дополнительно оплачивается доставка крупногаборитных и посылок весом:<br/>от 3 кг - 150 р.<br/>от 5 кг - 250 р.</p>
        </div>
        <div class="forstep_fin"></div>
    </div>
</div>