<?php
class ShopTests extends BaseSeleniumTest{

    public function test_1_add_to_cart()
    {
        $this->open("/shop/catalog/vitaminy-i-antioksidanty");
        $this->click("css=input.btn-w");
        $this->click("//li[2]/div/input");
        $this->click("xpath=(//a[contains(text(),'В корзине на сумму:')])[5]");
        $this->waitForPageToLoad("30000");
        $this->assertEquals("КорзинаВернуться к покупкам", $this->getText("css=h2.h-basket"));
        $this->assertEquals("товаров на сумму", $this->getText("css=span.all_count_word"));
    }

    public function test_2_add_to_cart_2()
    {
        $this->open("/shop/catalog/vitaminy-i-antioksidanty");
        $this->click("css=input.btn-w");
        $this->click("//li[2]/div/input");
        $this->click("//li[3]/div/input");
        $this->click("//ul[2]/li[3]/div/input");
        $this->click("//ul[2]/li[2]/div/input");
        $this->click("//ul[2]/li/div/input");
        $this->click("xpath=(//a[contains(text(),'В корзине на сумму:')])[5]");
        $this->waitForPageToLoad("30000");
        $this->assertEquals("КорзинаВернуться к покупкам", $this->getText("css=h2.h-basket"));
        $this->assertEquals("товаров на сумму", $this->getText("css=span.all_count_word"));
    }

    public function test_3_()
    {
        $this->open("/shop/catalog/vitaminy-i-antioksidanty/vitaminy-premium-klassa-solgar");
        $this->click("css=input.btn-w");
        $this->click("//li[2]/div/input");
        $this->click("css=p.inbasket > a");
        $this->waitForPageToLoad("30000");
        $this->assertEquals("Всего: 2 товара на сумму", $this->getText("css=span.count"));
        $this->open("/shop/catalog/vitaminy-i-antioksidanty/vitaminy-premium-klassa-solgar");
        $this->click("css=span.delete-from-basket");
        $this->click("//li[2]/div/span/span");
        $this->open("/shop/basket");
        $this->assertEquals("Корзина пуста", $this->getText("css=div.info-message"));
    }

    public function test_4()
    {
        $this->open("/shop/catalog/vitaminy-i-antioksidanty");
        $this->click("css=input.btn-w");
        $this->click("css=li.increment");
        $this->click("css=li.increment");
        sleep(1);
        $this->click("css=p.inbasket > a");
        $this->waitForPageToLoad("30000");
        $this->assertEquals("681", $this->getText("css=span.total_price"));
        $this->click("css=li.decrement");
        sleep(1);
        $this->assertEquals("454", $this->getText("css=span.total_price"));
        $this->click("css=li.decrement");
        sleep(1);
        $this->assertEquals("227", $this->getText("css=span.total_price"));
        $this->click("css=li.decrement");
        sleep(1);
        $this->assertEquals("0", $this->getText("css=span.like_i > span.count_all_good"));
    }

    public function test_5_delete_items_from_cart()
    {
        $this->open("/shop/catalog/vitaminy-i-antioksidanty/vitaminy-premium-klassa-solgar");
        $this->waitForPageToLoad();
        $this->click("//li[3]/div/input");
        $this->click("//li[2]/div/input");
        sleep(1);
        $this->click("xpath=(//a[contains(text(),'В корзине на сумму:')])[2]");
        $this->waitForPageToLoad("30000");
        $this->assertEquals("1534", $this->getText("css=span.basket_price.price_all_good"));
        $this->click("css=span.del_good");
        sleep(2);
        $this->assertEquals("846", $this->getText("css=span.basket_price > span.price_all_good"));
    }

    public function test_6_empty_cart()
    {
        $this->open("/shop/catalog");
        $this->click("link=Витамины и антиоксиданты");
        $this->waitForPageToLoad("30000");
        $this->click("//body/div/div/div");
        $this->click("//li[2]/div/input");
        $this->click("//li[3]/div/input");
        $this->click("//li[4]/div/input");
        $this->click("//ul[2]/li[2]/div/input");
        $this->click("//ul[2]/li[3]/div/input");
        $this->click("//ul[2]/li[4]/div/input");
        $this->click("xpath=(//a[contains(text(),'В корзине на сумму:')])[8]");
        $this->waitForPageToLoad("30000");
        $this->assertEquals("Всего: 6 товаров на сумму", $this->getText("css=span.count"));
        $this->assertEquals("935", $this->getText("css=span.basket_price.price_all_good"));
        $this->click("css=a.basket_clear");
        $this->assertEquals("Всего: 0 товаров на сумму", $this->getText("css=span.count"));
        $this->assertEquals("0", $this->getText("css=span.basket_price.price_all_good"));
    }

    public function test_7_order()
    {
        $this->myLogin();
        $this->open("shop/catalog");
        $this->click("link=Антибиотики");
        $this->waitForPageToLoad("30000");
        $this->click("css=input.btn-w");
        $this->click("//li[2]/div/input");
        $this->click("//li[3]/div/input");
        $price1 = $this->getText("css=b.gprice");
        $price2 = $this->getText("//li[2]/div/b");
        $price3 = $this->getText("//li[3]/div/b");
        sleep(1);
        $price_1 = (int)$price1;
        $price_2 = (int)$price2;
        $price_3 = (int)$price3;
        sleep(1);
        $this->click("css=input.btn-appoint.order_btn");
        $this->waitForPageToLoad("30000");
        $this->type("css=textarea[name=\"address\"]", "г. Минск");
        $this->assertEquals("3 товара на", $this->getText("css=span.like_i"));
        $this->assertEquals($price_1 + $price_2 + $price_3, $this->getText("css=span.order_all_p"));
        $this->click("css=input.btn-appoint.further");
        $this->click("css=input.btn-appoint.order_ready");
        $this->assertEquals("Заказ успешно оформлен!", $this->getText("//div[3]/p"));
    }

    public function test_8_order()
    {
        $this->myLogin();
        $this->open("shop/catalog");
        $this->click("link=Антибиотики");
        $this->waitForPageToLoad("30000");
        $this->click("css=input.btn-w");
        $this->click("//li[2]/div/input");
        $this->click("//li[3]/div/input");
        sleep(1);
        $this->click("css=input.btn-appoint.order_btn");
        $this->waitForPageToLoad("30000");
        $this->assertEquals("1549", $this->getText("css=span.order_all_p"));
        $this->type("css=textarea[name=\"address\"]", "Москва");
        $this->click("css=input.btn-appoint.further");
        $this->click("css=input.btn-appoint.order_ready");
        $this->assertEquals("Заказ успешно оформлен!", $this->getText("//div[3]/p"));
    }

    public function test_9_warning_message()
    {
        $this->open("/shop/catalog/sredstva-ot-allergii");
        $this->click("//li[2]/div/input");
        $this->click("//li[2]/div/span/ul/li[3]");
        sleep(1);
        $this->click("css=input.btn-appoint.order_btn");
        $this->waitForPageToLoad("30000");
        $this->click("css=input.btn-appoint.further");
        $this->assertEquals("Адрес доставки должен быть указан обязательно!", $this->getText("css=label.error"));
    }

    public function test_10_empty_cart()
    {
        $this->open("/shop/catalog/sredstva-ot-allergii");
        $this->click("css=input.btn-w");
        sleep(1);
        $this->click("css=p.txt > a");
        $this->waitForPageToLoad("30000");
        $this->click("css=a.basket_clear");
        $this->click("css=p.total_line > input.btn-appoint");
        $this->assertEquals("ВСЕГО: 0 товаров на0 р. Минимальный заказ от 500 р.", $this->getText("css=div.basket_total"));
    }

    public function test_11_sidebar_cart_block()
    {
        $this->open("/shop/catalog/antiseptiki-i-dezinficiruyuschie-sredstva");
        $this->click("css=input.btn-w");
        $this->click("css=li.increment");
        $this->click("//li[2]/div/input");
        $this->click("//li[2]/div/span/ul/li[3]");
        sleep(2);
        $this->assertEquals("118", $this->getText("css=span.basket_p"));
        $this->click("//li[2]/div/span/span");
        sleep(2);
        $this->assertEquals("86", $this->getText("css=span.basket_p"));
        $this->click("//li[3]/div/input");
        $this->click("//li[3]/div/span/ul/li[3]");
        $this->click("//li[3]/div/span/ul/li[3]");
        sleep(2);
        $this->assertEquals("134", $this->getText("css=span.basket_p"));
        $this->click("css=span.delete-from-basket");
        sleep(2);
        $this->assertEquals("48", $this->getText("css=span.basket_p"));
    }

    public function test_12()
    {
        $this->open("/shop/catalog/antiseptiki-i-dezinficiruyuschie-sredstva");
        $this->waitForPageToLoad();
        $this->click("//li[2]/div/input");
        $this->click("//li[3]/div/input");
        $this->click("//li[4]/div/input");
        $this->click("//li[2]/div/span/span");
        $this->click("//li[3]/div/span/span");
        $this->click("//li[4]/div/span/span");
        sleep(2);
        $this->assertFalse($this->isVisible("css=p.h-txt.h-bask"));
    }

    public function test_13_cart()
    {
        $this->open("/shop/catalog/antiseptiki-i-dezinficiruyuschie-sredstva");
        $this->click("//li[3]/div/input");
        $this->click("//li[4]/div/input");
        $this->click("//ul[2]/li[4]/div/input");
        $this->click("xpath=(//a[contains(text(),'В корзине на сумму:')])[4]");
        $this->waitForPageToLoad("30000");
        $this->assertEquals("Аммиак флаконы 10% , 40 мл", $this->getText("css=p.goodname > a"));
        $this->assertEquals("Ацербин флаконы , 80 мл", $this->getText("link=Ацербин флаконы , 80 мл"));
        $this->assertEquals("Бетадин флаконы 10% , 1000 мл", $this->getText("link=Бетадин флаконы 10% , 1000 мл"));
    }

    public function test_14_emptying_cart()
    {
        $this->open("/shop/catalog/antiseptiki-i-dezinficiruyuschie-sredstva");
        $this->click("css=input.btn-w");
        $this->click("//li[2]/div/input");
        $this->click("//li[3]/div/input");
        $this->click("//li[4]/div/input");
        $this->click("//li[4]/div/span/ul/li[3]");
        $this->click("css=p.txt > a");
        $this->waitForPageToLoad("30000");
        $this->click("css=a.basket_clear");
        $this->click("css=a.back");
        $this->waitForPageToLoad("30000");
        $this->click("link=Антисептики и дезинфицирующие средства");
        $this->waitForPageToLoad();
        try {
            $this->assertEquals("Купить", $this->getValue("css=input.btn-w"));
        } catch (PHPUnit_Framework_AssertionFailedError $e) {
            array_push($this->verificationErrors, $e->toString());
        }
        try {
            $this->assertEquals("Купить", $this->getValue("//li[2]/div/input"));
        } catch (PHPUnit_Framework_AssertionFailedError $e) {
            array_push($this->verificationErrors, $e->toString());
        }
        try {
            $this->assertEquals("Купить", $this->getValue("//li[3]/div/input"));
        } catch (PHPUnit_Framework_AssertionFailedError $e) {
            array_push($this->verificationErrors, $e->toString());
        }
        try {
            $this->assertEquals("Купить", $this->getValue("//li[4]/div/input"));
        } catch (PHPUnit_Framework_AssertionFailedError $e) {
            array_push($this->verificationErrors, $e->toString());
        }
    }

    public function test_15_step_2_order()
    {
        $this->myLogin();
        $this->click("css=a..shop-link");
        $this->waitForPageToLoad("30000");
        $this->click("//body/div/div/div");
        $this->click("css=ul.catalog_list > li > a");
        $this->waitForPageToLoad("30000");
        $this->click("css=input.btn-w");
        $this->click("//li[2]/div/input");
        $this->click("css=input.btn-appoint.order_btn");
        $this->waitForPageToLoad("30000");
        $this->type("css=textarea[name=\"address\"]", "Минск");
        $this->click("css=input.btn-appoint.further");
        try {
            $this->assertEquals("Лапыш Сергей", $this->getValue("css=input[name=\"name\"]"));
        } catch (PHPUnit_Framework_AssertionFailedError $e) {
            array_push($this->verificationErrors, $e->toString());
        }
        try {
            $this->assertEquals("+7-468-549-86-51", $this->getValue("css=input[name=\"phone_number\"]"));
        } catch (PHPUnit_Framework_AssertionFailedError $e) {
            array_push($this->verificationErrors, $e->toString());
        }
        try {
            $this->assertEquals("arialover08@gmail.com", $this->getValue("css=input[name=\"email\"]"));
        } catch (PHPUnit_Framework_AssertionFailedError $e) {
            array_push($this->verificationErrors, $e->toString());
        }
        $this->click("css=input.btn-appoint.order_ready");
        $this->assertEquals("Заказ успешно оформлен!", $this->getText("//div[3]/p"));
    }

    public function test_16_unauthorized_user()
    {
        $this->open("/shop/catalog/antiseptiki-i-dezinficiruyuschie-sredstva");
        $this->click("//li[4]/div/input");
        $this->click("//li[4]/div/span/ul/li[3]");
        sleep(1);
        $this->click("css=input.btn-appoint.order_btn");
        $this->waitForPageToLoad("30000");
        $this->type("css=textarea[name=\"address\"]", "Минск");
        $this->click("css=input.btn-appoint.further");
        $this->type("css=input[name=\"phone_number\"]", "1234567890");
        $this->type("css=input[name=\"name\"]", "Лапыш Сергей");
        $this->type("css=input[name=\"email\"]", "test@te.st");
        $this->click("css=input.btn-appoint.order_ready");
        $this->assertEquals("Заказ успешно оформлен!", $this->getText("//div[3]/p"));
    }

    public  function test_20_delivery_1()
    {
        $this->open("/shop/catalog/antiseptiki-i-dezinficiruyuschie-sredstva");
        $this->click("//li[2]/div/input");
        $this->click("css=input.btn-appoint.order_btn");
        $this->waitForPageToLoad("30000");
        $this->assertEquals("Доставка товаров на сумму менее 500 р. не осуществляется.", $this->getText("css=div.info-message"));
    }

    public function test_21_delivery_2()
    {
        $this->myLogin();
        $this->open("/shop/catalog/antiseptiki-i-dezinficiruyuschie-sredstva");
        $this->click("//li[4]/div/input");
        $this->click("//li[4]/div/span/ul/li[3]");
        $this->click("css=input.btn-appoint.order_btn");
        $this->waitForPageToLoad("30000");
        $this->assertEquals("662", $this->getText("css=span.order_all_p"));
        $this->click("css=textarea[name=\"address\"]");
        $this->type("css=textarea[name=\"address\"]", "Минск");
        $this->click("css=input.btn-appoint.further");
        $this->click("css=input.btn-appoint.order_ready");
        $this->assertEquals("Заказ успешно оформлен!", $this->getText("//div[3]/p"));
    }

    public function test_22_delivery_3()
    {
        $this->myLogin();
        $this->open("/shop/catalog/antiseptiki-i-dezinficiruyuschie-sredstva");
        $this->click("//li[4]/div/input");
        $this->click("//li[4]/div/span/ul/li[3]");
        $this->click("//li[4]/div/span/ul/li[3]");
        $this->click("//ul[2]/li[3]/div/input");
        $this->click("css=li.increment.disabled");
        $this->click("css=input.btn-appoint.order_btn");
        $this->waitForPageToLoad("30000");
        $this->click("css=textarea[name=\"address\"]");
        $this->type("css=textarea[name=\"address\"]", "Минск");
        $this->click("css=input.btn-appoint.further");
        $this->assertEquals("985", $this->getText("css=span.order_all_p"));
        $this->click("css=input.btn-appoint.order_ready");
    }

    public function test_26()
    {
        $this->open("/shop/product/15247");
        $this->click("css=li.increment");
        $this->click("css=li.increment");
        $this->click("css=li.increment");
        $this->click("css=input.btn-appoint");
        sleep(1);
        $this->click("css=p.txt > a");
        $this->waitForPageToLoad("30000");
        $this->assertEquals("Валента", $this->getText("css=p.goodprod"));
    }

    public function test_27()
    {
        $this->myLogin();
        $this->open("/shop/product/15247");
        $this->click("css=li.increment");
        $this->click("css=li.increment");
        $this->click("css=li.increment");
        $this->click("css=input.btn-appoint");
        sleep(1);
        $this->click("css=p.txt > a");
        $this->waitForPageToLoad("30000");
        $this->click("css=p.total_line > input.btn-appoint");
        $this->waitForPageToLoad("30000");
        $this->click("css=textarea[name=\"address\"]");
        $this->click("//div/div/div/div/div/div/div/p[2]/span");
        $this->click("css=input.btn-appoint.further");
        $this->click("css=input.btn-appoint.order_ready");
        $this->assertEquals("Заказ успешно оформлен!", $this->getText("//div[3]/p"));
        sleep(1);
        $this->click("css=a.active.shop-link");
        $this->waitForPageToLoad("30000");
        $this->click("link=Болезни крови");
        $this->waitForPageToLoad("30000");
        $this->click("//li[2]/div/input");
        $this->click("//li[2]/div/span/ul/li[3]");
        $this->click("//li[2]/div/span/ul/li[3]");
        $this->click("css=input.btn-appoint.order_btn");
        $this->waitForPageToLoad("30000");
        $this->assertEquals("300 р.", $this->getText("css=li.shipping > span.pricegood > span.order_p"));
        $this->assertEquals("Минск", $this->getText("css=textarea[name=\"address\"]"));
        $this->click("css=input.btn-appoint.further");
        $this->click("css=input.btn-appoint.order_ready");
        $this->assertEquals("Заказ успешно оформлен!", $this->getText("//div[3]/p"));
    }


}
