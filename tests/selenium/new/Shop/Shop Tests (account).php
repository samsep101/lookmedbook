<?php
class Account extends BaseSeleniumTest
{
    public function test_2_orders_view()
    {
        $this->myLogin();
        $this->open("/account/orders");
        $this->assertTrue($this->isElementPresent("css=img[alt=\"Изображение\"]"));
        $this->assertTrue($this->isElementPresent("css=p.hb"));
        $this->assertTrue($this->isElementPresent("css=div.goodprice > p.hb"));
        $text = $this->getText("css=div.content");
        $quantity = substr_count($text, 'Заказ №');
        $this->assertTrue($quantity >= 3);
    }

    public function test_3_year_label()
    {
        $this->myLogin();
        $this->open("/account/orders");
        /*if ($this->isElementPresent("css=a.load-next-page.view-more")){
            $this->click("css=a.load-next-page.view-more");
            sleep(1);
        }
        $quantity = $this->getXpathCount("/html/body/div/div/div[2]/div");
        $this->assertTrue($quantity, "1");*/
        $this->assertTrue($this->isElementPresent("css=div.bg_gradient.sort_year"));
    }

    public function test_4_page_title()
    {
        $this->myLogin();
        $this->open("/account/orders");
        $this->waitForPageToLoad();
        $this->assertEquals("Личный кабинет", $this->getTitle());
    }










}