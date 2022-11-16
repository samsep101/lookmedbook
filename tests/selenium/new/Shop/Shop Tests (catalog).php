<?php

class ShopTests extends BaseSeleniumTest
{
    public function test_1_title ()
    {
        $this->open("/shop/catalog");
        $this->assertEquals("Лекарства", $this->getTitle());
    }

    public function test_1_title_2 ()
    {
        $this->open("/shop/product/L-karnitin-s-vitaminom-v2");
        $this->assertEquals("Лекарства. Похудение. Обмен веществ. Средства для похудения. L-карнитин с витамином В2", $this->getTitle());
    }

    public function test_2_SEARCH()
    {
        $this->open("/shop/catalog");
        $this->type("css=input[name=\"products_query\"]", "крем");
        $this->click("css=input.btn-1");
        $this->waitForPageToLoad("30000");
        $this->assertEquals("Результаты поиска по запросу \"крем\"", $this->getText("css=li.search_pattern"));
        $this->assertEquals("Акридерм ГентА крем, 30 г", $this->getText("//a[contains(text(),'Акридерм ГентА крем, 30 г')]"));
    }

    public function test_3_search_2()
    {
        $this->open("/shop/catalog");
        $this->type("css=input[name=\"products_query\"]", "");
        $this->click("css=input.btn-1");
        sleep(2);
        $this->assertFalse($this->isElementPresent("css=li.search_pattern"));

    }

    public function test_4_subcategories ()
    {
        $this->open("/shop/catalog");
        $this->click("//a[contains(text(),'Болезни крови')]");
        $this->waitForPageToLoad("30000");
        $this->assertEquals("Антианемические средства (54)", $this->getText("css=ul.catalog_more_ul > li > a"));
        $this->assertEquals("Гемостатические средства (46)", $this->getText("//a[contains(text(),'Гемостатические средства (46)')]"));
    }

    public function test_5_summary_cost()
    {
        $this->open("/shop/catalog");
        $this->click("//li[4]/div/input");
        $this->click("//li[4]/div/span/ul/li[3]");
        $this->click("//li[4]/div/span/ul/li[3]");
        sleep(2);
        $price = $this->getText("//li[4]/div/b");
        $summary = $this->getText("//li[4]/p[2]/span");
        $price_int = (int)$price;
        $summary_int = (int)$summary;
        $this->assertEquals($summary_int, $price_int * 3);
    }

    public function test_6_subcategories_level_up()
    {
        $this->open("/shop/catalog");
        $this->click("link=Разные");
        $this->waitForPageToLoad("30000");
        $this->click("link=Рентгеноконтрастные средства (37)");
        $this->waitForPageToLoad("30000");
        $this->assertEquals("Разные к списку категорий", $this->getText("css=h3"));
        $this->assertEquals("Разные", $this->getText("link=Разные"));
    }

    public function test_7_categories_saving()
    {
        $this->shopLogin();
        $this->clickAndWait("css=a.product_category.nl > span");
        $this->clickAndWait("xpath=(//img[@title='Редактировать'])[5]");
        $this->type("css=input[name=\"form[name]\"]", "Test Category");
        $this->select("css=select[name=\"form[parent_id]\"]", "label=Лекарства и БАДы");
        if ($this->isElementPresent("css=#is_active_checkbox on") == 1) return;
        else ($this->click("css=#is_active_checkbox"));
        $this->click("css=#submit_action");
        $this->waitForPageToLoad("30000");
        $this->open("/shop/catalog");
        $this->waitForPageToLoad();
        $this->isTextPresent("Test Category");
    }

    public function test_8_reload_page()
    {
        $this->open("/shop/catalog");
        $this->click("xpath=(//input[@value='Купить'])[1]");
        $this->click("xpath=(//input[@value='Купить'])[2]");
        $this->click("xpath=(//input[@value='Купить'])[3]");
        $this->open("/shop/catalog");
        $this->assertEquals("В корзине на сумму:", $this->getText("xpath=(//a[contains(text(),'В корзине на сумму:')])[1]"));
        $this->assertEquals("В корзине на сумму:", $this->getText("xpath=(//a[contains(text(),'В корзине на сумму:')])[2]"));
        $this->assertEquals("В корзине на сумму:", $this->getText("xpath=(//a[contains(text(),'В корзине на сумму:')])[3]"));
    }

    public function test_9_to_top ()
    {
        $this->shopLogin();
        $this->open("/admin/product");
        $this->click("xpath=(//img[@title='Редактировать'])[6]");
        $this->waitForPageToLoad("30000");
        if ($this->isElementPresent("css=#is_leader_checkbox off") == 1) return;
        else ($this->click("css=#is_leader_checkbox"));
        $title = $this->getText("//div[@id='tab-0']/div/table/tbody/tr[3]/td[2]/span");
        $this->click("css=#submit_action");
        $this->waitForPageToLoad();
        $this->open("/shop/catalog");
        $this->waitForPageToLoad();
        try {
            $this->assertTrue($this->isElementPresent("xpath=(//a[contains(text(),'$title')])"));
        } catch (PHPUnit_Framework_AssertionFailedError $e) {
            array_push($this->verificationErrors, $e->toString());
        }
    }

    public function test_10_editing()
    {
        $this->open("/shop/product/aevit-kapsuly-10-sht");
        $this->shopLogin();
        $this->open("/admin/product/edit/?id=7983&destination=");
        $this->type("css=input[name=\"uploaded_image_id\"]", "C:\\Users\\Public\\Pictures\\Sample Pictures\\Chrysanthemum.jpg");
        $this->clickAndWait("id=submit_action");
        $this->waitForPageToLoad();
        $this->open("/shop/product/aevit-kapsuly-10-sht");
        $this->assertEquals("Аевит капсулы, 10 шт.", $this->getText("css=h3"));
        $this->open("/admin/product/edit/?id=7983&destination=");
        $this->clickAndWait("id=submit_action");
    }


    public function test_12_others()
    {
        $this->open("/shop/product/9-mesyacev-omegamama-kapsuly-30-sht");
        $this->waitForPageToLoad();
        $name = $this->getText("link=Витамины для беременных и кормящих");
        $this->assertEquals("$name. Другие товары:", $this->getText("css=h2"));
    }

    public function test_13_jump_to_cart()
    {
        $this->open("/shop/catalog");
        $this->click("xpath=(//input[@value='Купить'])[2]");
        $this->click("//li[2]/div/span/ul/li[3]");
        $this->click("//li[2]/div/span/ul/li[3]");
        $this->click("//li[2]/div/span/ul/li[3]");
        $this->click("//li[2]/div/span/ul/li[3]");
        $this->click("xpath=(//a[contains(text(),'В корзине на сумму:')])[2]");
        $this->waitForPageToLoad("30000");
        $this->click("//div/div/div");
        $this->assertTrue($this->isElementPresent("css=h2.h-basket"));
    }

    public function test_15_manufaturer_name_changing ()
    {
            $this->open("/shop/product/9-mesyacev-omegamama-kapsuly-30-sht");
        $this->waitForPageToLoad();
        $this->assertEquals("Валента", $this->getText("css=p.name"));
        $this->shopLogin();
        $this->open("/admin/manufacturer/edit/?id=12&destination=");
        $this->waitForPageToLoad();
        $this->type("name=form[name]", "Валента11111");
        $this->clickAndWait("id=submit_action");
        $this->open("/shop/product/9-mesyacev-omegamama-kapsuly-30-sht");
        $this->waitForPageToLoad();
        $this->assertEquals("Валента11111", $this->getText("css=p.name"));
        $this->open("/admin/manufacturer/edit/?id=12&destination=");
        $this->waitForPageToLoad();
        $this->type("name=form[name]", "Валента");
        $this->clickAndWait("id=submit_action");
    }

    public function test_16_link_to_main_page ()
    {
        $this->open("/shop/product/Gmannit-15-r-r-dinf-200ml-but-h1-b-m");
        $this->waitForPageToLoad();
        $this->click("css=a.back");
        $this->waitForPageToLoad("30000");
        $this->assertEquals("Диуретики", $this->getText("css=h4"));
    }


    public function test_18_back_to_categories_list()
    {
        $this->open("/shop/catalog");
        $this->click("//a[contains(text(),'Антибиотики')]");
        $this->waitForPageToLoad("30000");
        $this->click("link=к списку лекарств");
        $this->waitForPageToLoad("30000");
        $this->assertEquals("Каталог товаров", $this->getText("css=h2"));

    }

    public function test_21()
    {
        $this->open("/shop/product/a-cerumen-flakony-2-ml-5-sht");
        $this->waitForPageToLoad();
        $this->click("css=input.btn-appoint");
        $this->click("css=li.increment");
        $this->click("css=li.increment");
        $this->assertEquals("618", $this->getText("css=span.basket_p"));
    }

    public function test_23_full_path_bradcrumbs()
    {
        $this->open("shop/product/amikacin-ampuly-500-mg-2-ml-10-sht");
        $this->waitForPageToLoad();
        $this->assertEquals("Лекарства Лекарства и БАДы Антибиотики Амикацин ампулы 500 мг , 2 мл , 10 шт.", $this->getText("css=ul.way_line"));
        $this->click("link=Антибиотики");
        $this->waitForPageToLoad("30000");
        $this->assertEquals("Антибиотики", $this->getText("css=h4"));
        $this->open("shop/product/amikacin-ampuly-500-mg-2-ml-10-sht");
        $this->waitForPageToLoad();
        $this->click("link=Лекарства и БАДы");
        $this->waitForPageToLoad("30000");
        $this->assertEquals("Лекарства и БАДы", $this->getText("css=h4"));
    }

    public function test_25_total_price()
    {
        $this->open("/shop/catalog/antibiotiki");
        $this->click("//ul[2]/li[3]/div/input");
        for ($i = 1; $i < 3; $i++){
            $this->click("//ul[2]/li[3]/div/span/ul/li[3]");
        }
        $summary = (int)$this->getText("//ul[2]/li[3]/p[2]/span");
        $price = (int)$this->getText("//ul[2]/li[3]/div/b");
        $this->assertEquals($price*3, $summary);
    }

    public function test_30()
    {
        $this->open("/shop/catalog/akusherstvo-ginekologiya/lechenie-zhenskih-zabolevaniy");
        $this->click("css=a.category");
        $this->waitForPageToLoad("30000");
        $this->assertEquals("Лекарства. Акушерство. Гинекология.", $this->getTitle());
        $this->assertEquals("Акушерство. Гинекология.", $this->getText("css=h4 > a.category"));
    }








}
