<?php

class Sprints extends BaseSeleniumTest {
  /*  public function test_9()
    {
        $this->open("/");
        $this->click("css=a.a-dashed.popup_city");
        sleep(1);
        $this->click("css=a.fancybox-item.fancybox-close");
        $this->assertEquals("Москва", $this->getText("css=a.a-dashed.popup_city"));
    }

    public function test_10()
    {
        $this->open("/");
        $this->click("css=a.a-dashed.popup_city");
        sleep(2);
        $this->type("css=input[name=\"city_query\"]", "Челябинск");
        $this->click("css=#save-city-button");
        $this->waitForPageToLoad("30000");
        $this->assertEquals("Челябинск", $this->getText("css=a.a-dashed.popup_city"));
        $this->isTextPresent("Челябинск");
    }

    public function test_11()
    {
        $this->open("/");
        $this->click("css=a.a-dashed.popup_city");
        sleep(2);
        $this->assertEquals("Москва", $this->getText("css=ul.not-empty-cities.flo > li > a"));
        $this->assertEquals("Екатеринбург", $this->getText("link=Екатеринбург"));
        $this->assertEquals("Самара", $this->getText("link=Самара"));
        $this->assertEquals("Санкт-Петербург", $this->getText("link=Санкт-Петербург"));
        $this->assertEquals("Нижний Новгород", $this->getText("link=Нижний Новгород"));
        $this->assertEquals("Омск", $this->getText("link=Омск"));
        $this->assertEquals("Новосибирск", $this->getText("link=Новосибирск"));
        $this->assertEquals("Казань", $this->getText("link=Казань"));
        $this->assertEquals("Челябинск", $this->getText("link=Челябинск"));
    }

    public function test_12()
    {
        $this->open("/");
        $this->click("css=a.a-dashed.popup_city");
        sleep(2);
        $this->click("css=a.show_all");
        $this->assertEquals("Волжский", $this->getText("link=Волжский"));
        $this->assertEquals("Геленджик", $this->getText("link=Геленджик"));
        $this->assertEquals("Иваново", $this->getText("link=Иваново"));
    }

    public function test_14()
    {
        $this->open("/doctor");
        $this->waitForPageToLoad();
        $this->click("css=nobr > a");
        sleep(1);
        $this->click("css=a.fancybox-item.fancybox-close");
        $this->assertEquals("Москва", $this->getText("css=nobr > a"));
    }

    public function test_15()
    {
        $this->open("/doctor");
        $this->click("css=nobr > a");
        $this->type("css=input[name=\"city_query\"]", "Челябинск");
        $this->click("css=#save-city-button");
        $this->waitForPageToLoad("30000");
        $this->assertEquals("Челябинск", $this->getText("css=nobr > a"));
    }

    public function test_16()
    {
        $this->open("/doctor");
        $this->click("css=nobr > a");
        sleep(2);
        $this->assertEquals("Москва", $this->getText("css=ul.not-empty-cities.flo > li > a"));
        $this->assertEquals("Екатеринбург", $this->getText("link=Екатеринбург"));
        $this->assertEquals("Самара", $this->getText("link=Самара"));
        $this->assertEquals("Санкт-Петербург", $this->getText("link=Санкт-Петербург"));
        $this->assertEquals("Нижний Новгород", $this->getText("link=Нижний Новгород"));
        $this->assertEquals("Омск", $this->getText("link=Омск"));
        $this->assertEquals("Новосибирск", $this->getText("link=Новосибирск"));
        $this->assertEquals("Казань", $this->getText("link=Казань"));
        $this->assertEquals("Челябинск", $this->getText("link=Челябинск"));
    }

    public function test_17()
    {
        $this->open("/doctor");
        $this->click("css=nobr > a");
        sleep(2);
        $this->click("css=a.show_all");
        $this->assertEquals("Волжский", $this->getText("link=Волжский"));
        $this->assertEquals("Ростов-на-Дону", $this->getText("link=Ростов-на-Дону"));
    }*/

    public function test_19()
    {
        $this->open("/clinic");
        $this->click("css=nobr > a");
        sleep(1);
        $this->click("css=a.fancybox-item.fancybox-close");
        $this->assertEquals("Москва", $this->getText("css=nobr > a"));
    }

    public function test_20()
    {
        $this->open("/clinic");
        $this->click("css=nobr > a");
        $this->type("css=input[name=\"city_query\"]", "Челябинск");
        $this->click("css=#save-city-button");
        $this->waitForPageToLoad("30000");
        $this->assertEquals("Челябинск", $this->getText("css=nobr > a"));
        $this->isTextPresent("Челябинск");
    }

    public function test_21()
    {
        $this->open("/clinic");
        $this->click("css=nobr > a");
        sleep(2);
        $this->assertEquals("Москва", $this->getText("css=ul.not-empty-cities.flo > li > a"));
        $this->assertEquals("Екатеринбург", $this->getText("link=Екатеринбург"));
        $this->assertEquals("Самара", $this->getText("link=Самара"));
        $this->assertEquals("Санкт-Петербург", $this->getText("link=Санкт-Петербург"));
        $this->assertEquals("Нижний Новгород", $this->getText("link=Нижний Новгород"));
        $this->assertEquals("Омск", $this->getText("link=Омск"));
        $this->assertEquals("Новосибирск", $this->getText("link=Новосибирск"));
        $this->assertEquals("Казань", $this->getText("link=Казань"));
        $this->assertEquals("Челябинск", $this->getText("link=Челябинск"));
    }

    public function test_22()
    {
        $this->open("/clinic");
        $this->click("css=nobr > a");
        sleep(2);
        $this->click("css=a.show_all");
        $this->assertEquals("Волжский", $this->getText("link=Волжский"));
    }

    public function test_24()
    {
        $this->open("/analysis");
        $this->click("css=nobr > a");
        sleep(2);
        $this->click("css=a.fancybox-item.fancybox-close");
        $this->assertEquals("Москва", $this->getText("css=nobr > a"));
    }

    public function test_25()
    {
        $this->open("/analysis");
        $this->click("css=nobr > a");
        sleep(1);
        $this->type("css=input[name=\"city_query\"]", "Челябинск");
        $this->click("css=#save-city-button");
        $this->waitForPageToLoad("30000");
        $this->assertEquals("Челябинск", $this->getText("css=nobr > a"));
        $this->isTextPresent("Челябинск");
    }

    public function test_26()
    {
        $this->open("/analysis");
        $this->click("css=nobr > a");
        sleep(2);
        $this->assertEquals("Москва", $this->getText("css=ul.not-empty-cities.flo > li > a"));
        $this->assertEquals("Екатеринбург", $this->getText("link=Екатеринбург"));
        $this->assertEquals("Самара", $this->getText("link=Самара"));
        $this->assertEquals("Санкт-Петербург", $this->getText("link=Санкт-Петербург"));
        $this->assertEquals("Нижний Новгород", $this->getText("link=Нижний Новгород"));
        $this->assertEquals("Омск", $this->getText("link=Омск"));
        $this->assertEquals("Новосибирск", $this->getText("link=Новосибирск"));
        $this->assertEquals("Казань", $this->getText("link=Казань"));
        $this->assertEquals("Челябинск", $this->getText("link=Челябинск"));
    }

    public function test_27()
    {
        $this->open("/analysis");
        $this->click("css=nobr > a");
        sleep(2);
        $this->click("css=a.show_all");
        $this->assertEquals("Волжский", $this->getText("link=Волжский"));
        $this->assertEquals("Геленджик", $this->getText("link=Геленджик"));
        $this->assertEquals("Иваново", $this->getText("link=Иваново"));
    }

    public function test_29()
    {
        $this->open("/");
        $this->click("css=small.order-call");
        $this->click("css=input[name=\"phone_number\"]");
        $this->type("css=input[name=\"phone_number\"]", "+7-495-285-12-80");
        $this->type("css=input[name=\"first_name\"]", "Sergey");
        $this->click("css=input.btn-call");
        $this->assertEquals("Заказан звонок", $this->getText("css=div.form-call.form-call-step-2 > p.h-txt"));
    }

    public function test_32()
    {
        $this->myLogin();
        $this->open("/doctor/terapevt");
        {
            $count = $this->getXpathCount('//a[contains(@class, "btn-appoint")]'); //получаем число элементов, с классом btn-appoint
            $this->assertGreaterThan(0, $count);
            $test_item = rand(1, $count);
            $this->click('xpath=(//a[contains(@class, "btn-appoint")])[position()='.$test_item.']'); //клик по рандомной кнопке
            $this->waitForElementPresent('css=.record-to-the-doctor-popup');//ожидание нашего попапа
            $slots_count = $this->getXpathCount('//li[contains(@class, "time-li")]'); //количество слотов на попапе
            $slot_item = rand(1, $slots_count);//выбераем рандомный слот
            $this->click('xpath=(//li[contains(@class, "time-li")])[position()='.$slot_item.']');//кликаем по слоту
            $this->click('css=.record-to-the-doctor-popup .resume-btn');//кликаем на кнопку "Записаться"
            $this->click('css=.record-to-the-doctor-popup .god-mode');
            $this->click('css=.record-to-the-doctor-popup .send-button');
            sleep(5);
            $this->assertEquals("Поздравляем, вы успешно записались на прием. Ждите подтверждения!", $this->getText("css=div.fancybox-inner div"));
        }
        $this->adminLogin();
        $this->open("/admin/visit");
        $this->assertEquals("Москва", $this->getText("link=Москва"));
    }

    public function test_34()
    {
        $this->adminLogin();
        $this->open("/admin/visit");
        $this->waitForPageToLoad();
        $this->assertEquals("Записан на", $this->getText("//form[@id='visitform']/table/thead/tr/th[6]"));
    }

    public function test_35()
    {
        $this->adminLogin();
        $this->open("/admin/visit");
        $this->waitForPageToLoad();
        $this->click("//tr[@id='key[1678][]']/td[9]/a/img");
        $this->waitForPageToLoad("30000");
        $this->type("css=#visit_start_time_picker", "23.01.2014 10:52");
        $this->click("css=#submit_action");
        $this->waitForPageToLoad("30000");
        $this->assertEquals("23.01.2014 10:52", $this->getText("//tr[@id='key[1678][]']/td[6]"));
        $this->click("//tr[@id='key[1678][]']/td[9]/a/img");
        $this->waitForPageToLoad("30000");
        $this->type("css=#visit_start_time", "");
        $this->click("css=#submit_action");
        $this->waitForPageToLoad("30000");
        $this->assertEquals("", $this->getText("//tr[@id='key[1678][]']/td[6]"));
    }

    public function test_36()
    {
        $this->open("http://admin:21506@ekaterinburg.dev.lookmedbook.ru/");
       /* $this->waitForPageToLoad();
        $_SERVER['REQUEST_URI'];
        echo($_SERVER);*/
    }










}
