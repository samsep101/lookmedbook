<?php

class release extends BaseSeleniumTest
{
    public function test_1()
    {
        $this->open("/shop/product/akatinol-memantin-tabletki-10-mg-30-sht");
        $this->waitForPageToLoad();
        $this->assertEquals("Фармакокинектика", $this->getText("link=Фармакокинектика"));
        $this->assertEquals("Беременность и лактация", $this->getText("link=Беременность и лактация"));
        $this->assertEquals("Особые указания", $this->getText("link=Особые указания"));
        $this->assertEquals("Лекарственное взаимодействие", $this->getText("link=Лекарственное взаимодействие"));
        $this->assertEquals("Условия отпуска из аптек", $this->getText("link=Условия отпуска из аптек"));
    }

    public function test_3()
    {
        $this->shopLogin();
        $this->open("/admin/product_category");
        $this->waitForPageToLoad();
        $this->type("name=filter[name]", "разное");
        $this->click("css=input.submit");
        $this->waitForPageToLoad("30000");
        $this->click("css=img.edit-image");
        $this->waitForPageToLoad("30000");
        try {
            $this->assertEquals("off", $this->getValue("css=#is_active_checkbox"));
        } catch (PHPUnit_Framework_AssertionFailedError $e) {
            array_push($this->verificationErrors, $e->toString());
        }
        $this->myLogin();
        $this->open("/shop/catalog/raznoe");
        $this->assertEquals("Разное", $this->getText("css=a.category"));
        $this->assertEquals("Лекарства. Разное", $this->getTitle());
    }

    public function test_4()
    {
        $this->open("/");
        $this->click("css=a.btn-enter.reg-linking");
        $this->type("css=input[name=\"email\"]", "70000022525@user.ru");
        $this->type("css=input[name=\"password\"]", "70000022525@user.ru");
        $this->click("css=input.btn-1.submit");
        $this->waitForPageToLoad("30000");
        $this->open("/shop/catalog/raznoe");
        $this->assertEquals("LookMedBook", $this->getTitle());
        $this->assertTrue($this->isElementPresent("css=#logo-404"));
        $this->assertEquals("Мы не нашли страницу, которую Вы искали...", $this->getText("css=p"));
    }

    public function test_10()
    {
        $this->open("/analysis");
        $this->waitForPageToLoad();
        $this->assertEquals("На карте представлены лаборатории Вашего города с указанием времени работы и приема биоматериала для анализа", $this->getText("css=p.text"));
    }

    public function test_15()
    {
        $this->open("/clinic");
        $this->waitForPageToLoad();
        $count = $this->getXpathCount('//span[contains(@class, "post")]');
        $this->assertGreaterThan(0, $count);
        $test_item = rand(1, $count);
        $this->click('xpath=(//span[contains(@class, "post")])[position()='.$test_item.']');
        $this->waitForPageToLoad();
        $this->assertEquals("Сервис LookMedBook поможет записаться на прием в клинику online.", $this->getText("css=div.heading-line > p > span"));
        $this->assertTrue($this->isElementPresent("css=a.show-all"));
    }

    public function test_19()
    {
        $this->open("/clinic");
        $this->waitForPageToLoad();
        $count = $this->getXpathCount('//span[contains(@class, "post")]');
        $this->assertGreaterThan(0, $count);
        $test_item = rand(1, $count);
        $this->click('xpath=(//span[contains(@class, "post")])[position()='.$test_item.']');
        $this->waitForPageToLoad();
        $this->assertTrue($this->isElementPresent("css=a.show-all"));
    }

    public function test_20()
    {
        $this->open("/doctor/kovalevaeg");
        $this->waitForPageToLoad();
        $this->click("css=a.show-all");
        $this->click("css=div.equal-elements-container > ul > li > a");
        $this->waitForPageToLoad("30000");
        $this->assertEquals("Терапевт, врач ультразвуковой диагностики", $this->getText("css=span.post"));
    }

    public function test_21_22()
    {
        $this->adminLogin();
        $this->open("http://dev.lookmedbook.ru/admin/product/edit/?id=16182&destination=%2Fadmin%2Fproduct%23key%5B16182%5D%5B%5D");
        $this->select("css=select[name=\"form[fill_information_status_id]\"]", "label=По имени и форме выпуска");
        $this->click("css=#submit_action");
        $this->waitForPageToLoad("30000");
        $this->open("/shop/product/kandekor-tabletki-8-mg-28-sht");
        $this->waitForPageToLoad();
        $this->open("http://dev.lookmedbook.ru/shop/product/kandekor-tabletki-8-mg-28-sht");
        $this->assertFalse($this->isElementPresent("css=p.instruction"));
        $this->assertFalse($this->isElementPresent("css=li.active > a"));
        $this->open("http://dev.lookmedbook.ru/admin/product/edit/?id=16182&destination=%2Fadmin%2Fproduct%23key%5B16182%5D%5B%5D");
        $this->select("css=select[name=\"form[fill_information_status_id]\"]", "label=Готово");
        $this->click("css=#submit_action");
        $this->waitForPageToLoad("30000");
        $this->open("http://dev.lookmedbook.ru/shop/product/kandekor-tabletki-8-mg-28-sht");
        $this->waitForPageToLoad();
        $this->assertTrue($this->isElementPresent("css=p.instruction"));
        $this->assertTrue($this->isElementPresent("css=li.active > a"));
    }

    public function test_23()
    {
        $this->adminLogin();
        $this->open("/admin/product/edit/?id=16199&destination=%2Fadmin%2Fproduct%23key[16199][]");
        $this->waitForPageToLoad();
        $this->assertEquals("Нет в базе Видаль", $this->getText("css=option[value=\"7\"]"));
    }

    public function test_62()
    {
        $this->open("/");
        $this->waitForPageToLoad();
        $this->click("css=a.btn-enter.reg-linking");
        sleep(3);
        $this->click("css=#registration-popup-link");
        $random = (rand(1, 10000));
        $this->type("css=input[name=\"email\"]", "$random.aaaaaaaa@aaaa.aaa");
        $this->type("css=#password", "@#$%^&*()");
        $this->type("css=#repeat_registration_password", "@#$%^&*()");
        $this->click("css=input.btn-1.submit_registration");
        sleep(5);
        $this->assertEquals("Главная страница", $this->getTitle());
        $this->assertEquals("Найти врача", $this->getText("css=#find_doctor_tab"));
        $this->assertEquals("Найти клинику", $this->getText("css=#find_clinic_tab"));
    }

    public function test_55()
    {
        $this->open("/shop/catalog");
        $this->waitForPageToLoad();
        $this->assertTrue($this->isElementPresent("css=p.h-txt.h-delivery > a"));
        $this->assertTrue($this->isElementPresent("link=Полные условия доставки"));
        $this->assertTrue($this->isElementPresent("css=p.h-txt.h-pay > a"));
        $this->assertTrue($this->isElementPresent("link=Полные условия оплаты"));
    }

    public function test_50()
    {
        $this->open("/shop/catalog/search?products_query=анти");
        $this->waitForPageToLoad();
        $this->assertEquals("вернуться в каталог", $this->getText("css=a.back"));
    }

    public function test_51()
    {
        $this->open("/shop/catalog/search?products_query=648644444848946532");
        $this->waitForPageToLoad();
        $this->assertEquals("По данному запросу не найдено лекарств", $this->getText("css=div.error-plate"));
        $this->assertEquals("Лидеры продаж", $this->getText("css=h2"));
    }

    public function test_44()
    {
        $this->open("/shop/catalog/lechenie-zabolevaniy-zhkt-i-pecheni");
        $this->waitForPageToLoad();
        $this->click("link=Лечение заболеваний ЖКТ и печени");
        $this->assertEquals("Лечение заболеваний ЖКТ и печени", $this->getText("css=h4 > a.category"));
    }

    public function test_45()
    {
        $this->open("/shop/product/almagel-suspenziya-170-ml");
        $this->waitForPageToLoad();
        $this->assertTrue($this->isElementPresent("link=Алмагель суспензия, 170 мл"));
        $this->click("link=Алмагель суспензия, 170 мл");
        $this->assertEquals("Препараты для желудочно-кишечного тракта. Другие лекарства:", $this->getText("css=h2"));
    }

    public function test_46()
    {
        $this->open("/shop/product/almagel-suspenziya-170-ml");
        $this->waitForPageToLoad();
        $this->assertEquals("Алмагель, суспензия, 170 мл", $this->getTitle());
    }

    public function test_36()
    {
        $this->open("/shop/catalog");
        $this->waitForPageToLoad();
        $this->click("css=a.more-link");
        $this->click("link=Антибиотики");
        $this->waitForPageToLoad();
        $this->click("css=a.back");
        $this->waitForPageToLoad("30000");
        $this->assertEquals("Скрыть", $this->getText("css=a.more-link.expanded"));
    }

    public function test_38()
    {
        $this->open("/shop/catalog");
        $this->waitForPageToLoad();
        $this->assertEquals("Каталог лекарств", $this->getText("css=h2"));
    }

    public function test_42()
    {
        $this->open("/shop/product/5-nok-tabletki-50-mg-50-sht");
        $this->waitForPageToLoad();
        $this->assertEquals("Описание товара предоставлено \"Видаль\"", $this->getText("css=p.info-from-vidal"));
    }

    public function test_43()
    {
        $this->open("/shop/product/baktrim-sirop-sirop-240-mg5-ml-100-ml");
        $this->waitForPageToLoad();
        $this->assertEquals("Описание товара предоставлено \"Видаль\"", $this->getText("css=p.info-from-vidal"));
    }

    public function test_33()
    {
        $this->myLogin();
        $this->open("http://dev.lookmedbook.ru/shop/catalog/akusherstvo-ginekologiya");
        $this->waitForPageToLoad();
        $this->click("css=input.btn-w");
        sleep(2);
        $this->click("css=input.btn-appoint.order_btn");
        $this->waitForPageToLoad("30000");
        $this->type("css=textarea[name=\"address\"]", "Минск");
        $this->click("css=input.btn-appoint.further");
        $this->click("css=input.btn-appoint.order_ready");
        $this->assertEquals("Заказ успешно оформлен!", $this->getText("//body/div/div/div/div/div/div[3]/p"));
    }

    public function test_29()
    {
        $this->open("http://dev.lookmedbook.ru/shop/product/911-klimafit-kapsuly-30-sht");
        $this->waitForPageToLoad();
        $this->click("css=li.increment");
        $this->click("css=li.increment");
        $this->click("css=input.btn-appoint");
        $this->assertEquals("612", $this->getText("css=span.total_price"));
        sleep(2);
        $this->click("css=input.btn-appoint.order_btn");
        $this->waitForPageToLoad("30000");
        $this->assertEquals("Доставка курьером по Московской области", $this->getText("css=label.disabled"));
        $this->assertEquals("Минимальный заказ 900 р.", $this->getText("//span[2]"));
        $this->assertEquals("Доставка по России (почтой EMS)", $this->getText("//p[4]/label"));
        $this->assertEquals("Минимальный заказ 2000 р.", $this->getText("//p[4]/span[2]"));
    }

    public function test_30()
    {
        $this->open("/shop/product/alflutop-ampuly-10-mgml-1-ml-10-sht");
        $this->waitForPageToLoad();
        $this->click("css=input.btn-appoint");
        $this->assertEquals("1494", $this->getText("css=span.total_price"));
        sleep(2);
        $this->click("css=input.btn-appoint.order_btn");
        $this->waitForPageToLoad("30000");
        $this->assertEquals("Доставка курьером по Московской области", $this->getText("//p[3]/label"));
        $this->assertEquals("Доставка по России (почтой EMS)", $this->getText("//p[4]/label"));
        $this->assertEquals("Минимальный заказ 2000 р.", $this->getText("//p[4]/span[2]"));
    }

    public function test_31()
    {
        $this->open("/shop/product/alflutop-ampuly-10-mgml-1-ml-10-sht");
        $this->waitForPageToLoad();
        $this->click("css=input.btn-appoint");
        $this->click("css=li.increment");
        $this->assertEquals("2988", $this->getText("css=span.total_price"));
        sleep(2);
        $this->click("css=input.btn-appoint.order_btn");
        $this->waitForPageToLoad("30000");
        $this->assertEquals("Доставка курьером по Московской области", $this->getText("//p[3]/label"));
        $this->assertEquals("Доставка по России (почтой EMS)", $this->getText("//p[4]/label"));
    }

}