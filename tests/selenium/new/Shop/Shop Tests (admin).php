<?php
class ShopTests extends BaseSeleniumTest {

    public function test_1_active_only_categories()
    {
        $this->shopLogin();
        $this->open("/admin/product_category");
        $this->click("id=filter[is_active]");
        $this->click("css=input.submit");
        $this->waitForPageToLoad("30000");
        $this->assertTextNotPresent("нет");
    }

    public function test_3_filtering_by_name()
    {
        $this->shopLogin();
        $this->open("/admin/product_category");
        $this->type("id=filter[name]", "лекарства");
        $this->click("css=input.submit");
        $this->waitForPageToLoad("30000");
        $count_tr = $this->getXpathCount("/html/body/table/tbody/tr/td[2]/div/form/table/tbody/tr");
        echo($count_tr);
        /*for ($count = 1; $count < $count_tr; $count++){
            $this->assertText("/html/body/table/tbody/tr/td[2]/div/form/table/tbody/tr/td[2]", "лекарства");
        }*/

    }

    public function test_4_reset_button()
    {
        $this->shopLogin();
        $this->open("/admin/product_category");
        $this->click("id=filter[is_active]");
        $this->type("id=filter[name]", "лекарства");
        $this->click("css=input.submit");
        $this->waitForPageToLoad("30000");
        $this->click("css=input.cancel");
        $this->waitForPageToLoad("30000");
        $this->assertTrue($this->isElementPresent("link=5"));
    }

    public function test_5_editing_category()
    {
        $this->shopLogin();
        $this->open("/admin/product_category/edit/?id=1414&destination=");
        $this->click("css=h4");
        $this->type("css=input[name=\"form[name]\"]", "Средства от аллергии11111111");
        $this->click("css=#submit_action");
        $this->waitForPageToLoad("30000");
        $this->open("/shop/catalog/sredstva-ot-allergii");
        $this->waitForPageToLoad();
        $this->assertEquals("Средства от аллергии11111111 к списку лекарств", $this->getText("css=h4"));
        $this->open("/admin/product_category/edit/?id=1414&destination=");
        $this->click("css=h4");
        $this->type("css=input[name=\"form[name]\"]", "Средства от аллергии");
        $this->click("css=#submit_action");
        $this->waitForPageToLoad("30000");
    }

    public function test_6_adding_new_category()
    {
        $this->shopLogin();
        $this->open("/admin/product_category");
        $this->click("link=Добавление категории товаров");
        $this->waitForPageToLoad("30000");
        $this->type("css=input[name=\"form[name]\"]", "Test Category");
        $this->select("css=select[name=\"form[parent_id]\"]", "label=Лекарства и БАДы");
        $this->click("css=#submit_action");
        $this->waitForPageToLoad("30000");
    }

    public function test_7_delete_category()
    {
        $this->shopLogin();
        $this->open("/admin/product_category");
        $this->waitForPageToLoad();
        $this->click("link=Добавление категории товаров");
        $this->waitForPageToLoad("30000");
        $this->type("css=input[name=\"form[name]\"]", "Test Category #2");
        $this->select("css=select[name=\"form[parent_id]\"]", "label=Лекарства и БАДы");
        $this->click("css=#submit_action");
        $this->waitForPageToLoad("30000");
        $this->type("id=filter[name]", "Test Category #2");
        $this->click("css=input.submit");
        $this->waitForPageToLoad("30000");
        $this->click("css=img[title=\"Удалить\"]");
        $this->assertTrue((bool)preg_match('/^Вы действительно хотите удалить эту запись[\s\S]$/',$this->getConfirmation()));
        $this->waitForPageToLoad();
    }

    public function test_8_delete_many()
    {
        $this->shopLogin();
        for ($i = 1; $i < 5; $i++){
            $this->open("/admin/product_category/add/?destination=&");
            $this->waitForPageToLoad("30000");
            $this->type("css=input[name=\"form[name]\"]", "Test Category #$i");
            $this->select("css=select[name=\"form[parent_id]\"]", "label=Лекарства и БАДы");
            $this->click("css=#submit_action");
            $this->waitForPageToLoad("30000");
        }
        $this->open("/admin/product_category");
        $this->waitForPageToLoad();
        $this->type("id=filter[name]", "Test Category");
        $this->click("css=input.submit");
        $this->waitForPageToLoad("30000");
        $this->click("css=input.status_check");
        $this->click("css=#submit_action");
        $this->assertTrue((bool)preg_match('/^Вы действительно хотите удалить эти записи[\s\S]$/',$this->getConfirmation()));
        $this->waitForPageToLoad();
    }

    public function test_10_manufacturer_by_name()
    {
        $this->shopLogin();
        $this->open("/admin/manufacturer");
        $this->type("id=filter[name]", "AVENTIS");
        $this->click("css=input.submit");
        $this->waitForPageToLoad("30000");
        $this->assertEquals("AVENTIS", $this->getText("//form/table/tbody/tr/td[2]"));
    }

    public function test_11_adding_manufacturer()
    {
        $this->shopLogin();
        $this->open("/admin/manufacturer");
        $this->click("link=Добавление производителя товаров");
        $this->waitForPageToLoad("30000");
        $this->type("css=input[name=\"form[name]\"]", "Manufacturer No.1");
        $this->click("css=#submit_action");
        $this->waitForPageToLoad("30000");
        $this->type("id=filter[name]", "Manufacturer No.1");
        $this->click("css=input.submit");
        $this->waitForPageToLoad("30000");
        $this->assertEquals("Manufacturer No.1", $this->getText("//form/table/tbody/tr/td[2]"));
        $this->click("css=img[title=\"Удалить\"]");
        $this->assertTrue((bool)preg_match('/^Вы действительно хотите удалить эту запись[\s\S]$/',$this->getConfirmation()));
        $this->waitForPageToLoad();
    }

    public function test_12_edit_manufacturer()
    {
        $this->shopLogin();
        $this->open("/admin/manufacturer");
        $this->click("link=Добавление производителя товаров");
        $this->waitForPageToLoad("30000");
        $this->type("css=input[name=\"form[name]\"]", "Manufacturer No.2");
        $this->clickAndWait("css=#submit_action");
        $this->type("id=filter[name]", "Manufacturer No.2");
        $this->clickAndWait("css=input.submit");
        $this->clickAndWait("css=img[title=\"Редактировать\"]");
        $this->type("css=input[name=\"form[name]\"]", "Manufacturer No.3");
        $this->clickAndWait("css=#submit_action");
        $this->type("id=filter[name]", "Manufacturer No.3");
        $this->clickAndWait("css=input.submit");
        $this->click("css=img[title=\"Удалить\"]");
        $this->assertTrue((bool)preg_match('/^Вы действительно хотите удалить эту запись[\s\S]$/',$this->getConfirmation()));
        $this->waitForPageToLoad();
    }

    public function test_13_delete_many()
    {
        $this->shopLogin();
        for ($i = 1; $i < 5; $i++){
            $this->open("/admin/manufacturer/add/?destination=&");
            $this->waitForPageToLoad("30000");
            $this->type("css=input[name=\"form[name]\"]", "Test Manufacturer No.$i");
            $this->click("css=#submit_action");
            $this->waitForPageToLoad("30000");
        }
        $this->open("/admin/manufacturer");
        $this->waitForPageToLoad();
        $this->type("id=filter[name]", "Test Manufacturer");
        $this->click("css=input.submit");
        $this->waitForPageToLoad("30000");
        $this->click("css=input.status_check");
        $this->click("css=#submit_action");
        $this->assertTrue((bool)preg_match('/^Вы действительно хотите удалить эти записи[\s\S]$/',$this->getConfirmation()));
        $this->waitForPageToLoad();
    }

    public function test_14_delete_manufacturer()
    {
        $this->shopLogin();
        $this->open("/admin/manufacturer/add/?destination=&");
        $this->type("css=input[name=\"form[name]\"]", "Test Manufacturer #2");
        $this->click("css=#submit_action");
        $this->waitForPageToLoad("30000");
        $this->type("id=filter[name]", "Test Manufacturer #2");
        $this->click("css=input.submit");
        $this->waitForPageToLoad("30000");
        $this->click("css=img[title=\"Удалить\"]");
        $this->assertTrue((bool)preg_match('/^Вы действительно хотите удалить эту запись[\s\S]$/',$this->getConfirmation()));
        $this->waitForPageToLoad();
    }

    public function test_17()
    {
        $this->shopLogin();
        $this->click("css=a.product.nl");
        $this->waitForPageToLoad("30000");
        $this->type("id=filter[full_name]", "Абактал таблетки");
        $this->click("id=filter[is_active]");
        $this->click("css=input.submit");
        $this->waitForPageToLoad("30000");
        $this->assertEquals("Абактал таблетки 400 мг, 10 шт.", $this->getText("//td[4]"));
    }

    public function test_18_filtering_by_category()
    {
        $this->shopLogin();
        $this->open("/admin/product");
        $this->select("id=filter[product_category_id]", "label=Автомобильная аптечка");
        $this->click("css=input.submit");
        $this->waitForPageToLoad("30000");
        $this->assertEquals("Аптечка автомобильная пластиковая коробка, 1 шт.", $this->getText("//tr[@id='key[12177][]']/td[4]/span"));
    }

    public function test_19()
    {
        $this->shopLogin();
        $this->open("/admin/product");
        $this->select("id=filter[manufacturer_id]", "label=Abbott/Hospira");
        $this->click("css=input.submit");
        $this->waitForPageToLoad("30000");
        $this->assertEquals("Abbott/Hospira", $this->getText("//tr[@id='key[14929][]']/td[5]/span"));
    }

    public function test_20()
    {
        $this->shopLogin();
        $this->open("/admin/product");
        $this->type("id=filter[full_name]", "ЗЕМПЛАР");
        $this->select("id=filter[product_category_id]", "label=Корректоры метаболизма костной ткани");
        $this->select("id=filter[manufacturer_id]", "label=Abbott/Hospira");
        $this->click("css=input.submit");
        $this->waitForPageToLoad("30000");
        $this->click("css=input.cancel");
        $this->waitForPageToLoad("30000");
        $this->assertEquals("5-НОК таблетки 50 мг, 50 шт.", $this->getText("//tr[@id='key[13028][]']/td[4]/span"));
    }

    public function test_21_image_upload()
    {
        $this->shopLogin();
        $this->open("/admin/product/edit/?id=7888&destination=");
        $this->type("css=input[name=\"uploaded_image_id\"]", "C:\\Users\\Public\\Pictures\\Sample Pictures\\Chrysanthemum.jpg");
        $this->click("css=#submit_action");
        $this->waitForPageToLoad("30000");
        $this->open("/admin/product/edit/?id=7888&destination=");
        $this->click("css=span.deleteImageButton");
        $this->click("css=#submit_action");
        $this->waitForPageToLoad("30000");
    }


    public function test_24_accounts_filtering()
    {
        $this->shopLogin();
        $this->open("admin/account");
        $this->waitForPageToLoad("30000");
        $this->type("id=filter[email]", "79152222320@user.ru");
        $this->type("id=filter[first_name]", "Николай");
        $this->click("css=input.submit");
        $this->waitForPageToLoad("30000");
        $this->assertEquals("Николай", $this->getText("//form/table/tbody/tr/td[2]"));
    }

    public function test_25_no_filters_account()
    {
        $this->shopLogin();
        $this->open("admin/account");
        $this->waitForPageToLoad("30000");
        $quantity = $this->getXpathCount("/html/body/table/tbody/tr/td[2]/div/div[3]/a");
        $this->assertTrue($quantity > 5);
    }

    public function test_26_reset_button()
    {
        $this->shopLogin();
        $this->open("admin/account");
        $this->waitForPageToLoad("30000");
        $this->type("id=filter[email]", "79152222320@user.ru");
        $this->type("id=filter[first_name]", "Николай");
        $this->click("css=input.submit");
        $this->waitForPageToLoad("30000");
        $this->assertEquals("Николай", $this->getText("//form/table/tbody/tr/td[2]"));
        $this->click("css=input.cancel");
        $this->waitForPageToLoad("30000");
        $quantity = $this->getXpathCount("/html/body/table/tbody/tr/td[2]/div/div[3]/a");
        $this->assertTrue($quantity > 5);
    }

    public function test_32_order_status_changing()
    {
        $this->shopLogin();
        $this->open("/admin/order/edit/?id=79&destination=");
        $this->waitForPageToLoad();
        $this->assertEquals("Заказ не отправлен piluli.ru", $this->getText("//tr[@id='key[57][]']/td[2]/span"));
        $this->assertEquals("В очереди", $this->getText("//tr[@id='key[61][]']/td[2]/span"));
    }


    public function test_27_30_orders_filtering()
    {
        $this->shopLogin();
        $this->open("/admin/order");
        $this->waitForPageToLoad();
        $this->type("id=filter[id]", "78");
        $this->type("id=filter[phone_number]", "74685498651");
        $this->click("id=filter[dt_from]");
        $this->click("id=filter[dt_to]");
        $this->click("css=input.submit");
        $this->waitForPageToLoad("30000");
        $this->assertEquals("78", $this->getText("//form/table/tbody/tr/td[2]"));
    }

    public function test_31_delete_order()
    {
        $this->myLogin();
        $this->open("/shop/catalog/bolezni-krovi");
        $this->waitForPageToLoad();
        $this->click("//li[2]/div/input");
        $this->click("//li[3]/div/input");
        $this->click("//li[2]/div/span/ul/li[3]");
        $this->click("css=input.btn-appoint.order_btn");
        $this->waitForPageToLoad("30000");
        $this->assertEquals("578", $this->getText("css=span.order_all_p"));
        $this->click("css=textarea[name=\"address\"]");
        $this->type("css=textarea[name=\"address\"]", "Москва");
        $this->click("css=input.btn-appoint.further");
        $this->click("css=input.btn-appoint.order_ready");
        $this->assertEquals("Заказ успешно оформлен!", $this->getText("//div[3]/p"));
        $this->shopLogin();
        $this->open("/admin/order");
        $this->waitForPageToLoad();
        $this->type("id=filter[phone_number]", "74685498651");
        $this->click("css=input.submit");
        $this->waitForPageToLoad("30000");
        $this->click("css=img[title=\"Удалить\"]");
        $this->assertTrue((bool)preg_match('/^Вы действительно хотите удалить эту запись[\s\S]$/',$this->getConfirmation()));
        $this->open("/admin/order");
        $this->waitForPageToLoad();
        $this->type("id=filter[phone_number]", "74685498651");
        $this->click("css=input.submit");
        $this->waitForPageToLoad("30000");
        $this->assertEquals("Пока нет данных.", $this->getText("//p[2]"));
    }

    public function test_33()
    {
        $this->adminLogin();
        $this->open("/admin/product/edit/?id=21077&destination=");
        $this->waitForPageToLoad();
        $this->click("css=#vidal-find-button");
        sleep(2);
        $this->type("css=input[type=\"text\"]", "L-Тироксин 75");
        $this->click("css=button");
        $this->click("css=div.fancybox-inner > form > p > #submit_action");
        $this->click("css=#tab-1 > h4");
        $this->click("css=#tab-1 > h4");
        $this->click("css=#submit_action");
        $this->waitForPageToLoad("30000");
        $this->selectWindow("name=25631");
        $this->click("css=input[name=\"products_query\"]");
        $this->type("css=input[name=\"products_query\"]", "l-тироксин 75");
        $this->click("css=input.btn-1");
        $this->waitForPageToLoad("30000");
        $this->click("//li/a/img");
        $this->waitForPageToLoad("30000");
        $this->click("link=Показания");
        $this->click("link=Способ применения дозы");
        $this->assertEquals("Суточная доза определяется индивидуально в зависимости от показаний.", $this->getText("//div[2]/div/p"));
    }


}