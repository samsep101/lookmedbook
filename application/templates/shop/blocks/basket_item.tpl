<div class="basket_good buy flo">
    <img src="http://brugs.ru/images/i/polza-i-vred-lekarstv/mogushhestvennoe-oruzhie-medicziny/mikroby-delayut-lekarstva/shutterstock_9826267.jpg">
    <div class="about">
        <p class="goodname"><?php echo $product['name']?></p>
        <p class="goodprod"><?php echo $product['manufacturer']?></p>
    </div>
    <div class="goodcount">
        <p class="hb">Количество</p>
        <ul class="numeric">
            <li onclick="spin(1, this)">-</li>
            <li class="count"><?php echo $product['count']?></li>
            <li onclick="spin(-1, this)">+</li>
        </ul>
        <span>шт.</span>
    </div>
    <div class="goodprice">
        <p class="hb">Стоимость</p>
        <span class="product_price"><b class="gprice"><?php echo $product['price']?></b><b class="total_price"><?php echo $product['count']*$product['price']?></b><b>р.</b></span>
    </div>
    <span class="del_good"></span>
</div>