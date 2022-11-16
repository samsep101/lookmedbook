<?php

class Sprints extends BaseSeleniumTest
{
    public function test_2()
    {
        $this->open("/wtewtwetwetwe");
        $this->waitForPageToLoad();
        $this->assertTrue($this->isElementPresent("css=#logo-404"));
    }

    public function test_3()
    {
        $this->open("http://admin:21506@omsk.dev.lookmedbook.ru/wesrdtfvghnj");
        $this->waitForPageToLoad();
        $this->assertTrue($this->isElementPresent("css=#logo-404"));
    }

    public function test_4 ()
    {
        $this->open("http://admin:21506@aaa.dev.lookmedbook.ru");
        $this->waitForPageToLoad();
        $this->assertEquals("", $this->getText("id=logo-404"));
        $this->open("http://admin:21506@aa344343da.dev.lookmedbook.ru");
        $this->waitForPageToLoad();
        $this->assertEquals("", $this->getText("id=logo-404"));
        $this->open("http://admin:21506@a-frfaa.dev.lookmedbook.ru");
        $this->waitForPageToLoad();
        $this->assertEquals("", $this->getText("id=logo-404"));
        $this->open("http://admin:21506@aaвуцаукуакуa.dev.lookmedbook.ru");
        $this->waitForPageToLoad();
        $this->assertEquals("", $this->getText("id=logo-404"));
        $this->open("http://admin:21506@dev.lookmedbook.ru");
        $this->waitForPageToLoad();
        $this->assertEquals("Lookmedbook — поиск врача, запись на прием, информация о заболеваниях, медицинский справочник", $this->getTitle());
    }

    public function test_5_6_7()
    {
        $this->open("/doctor/kolosovaoyu");
        $this->waitForPageToLoad();
        $this->assertTrue($this->isElementPresent("css=span.post"));
        $this->assertTrue($this->isElementPresent("css=p.name"));
    }

    public function test_8()
    {
        $this->open("/clinic");
        $this->waitForPageToLoad();
        $this->click("css=a.chzn-single.chzn-single-with-drop > span");
        $this->click("css=#specialties_to_search_clinic_chzn_o_4");
        $this->click("css=input.btn-1.btn-clinic");
    }

    public function test_9()
    {
        $this->open("/clinic?specialty_id=7");
        $this->waitForPageToLoad();
        $this->assertLocation("http://dev.lookmedbook.ru/clinic/endokrinolog");
    }

    public function test_10_11_12()
    {
        $this->open("/");
        $this->click("css=a.btn-appoint\n.");
        sleep(2);
        $this->assertTrue($this->isElementPresent("css=p.name > a > span"));
        $this->open("/doctor");
        $this->waitForPageToLoad();
        $this->click("css=a.btn-appoint\n.");
        sleep(2);
        $this->assertTrue($this->isElementPresent("css=p.name > a > span"));
        $this->open("/clinic");
        $this->click("css=a.btn-appoint");
        $this->waitForPageToLoad("30000");
        $this->click("css=a.btn-appoint\n.");
        $this->assertTrue($this->isElementPresent("css=p.name > a > span"));
    }

    public function test_16()
    {
        $this->open("/robots.txt");
        sleep(1);
        $this->assertTrue((bool)preg_match('/^exact:User-agent: Yandex
    Disallow: \/[\s\S]*search[\s\S]*
    Disallow: \/[\s\S]*utm_
    Disallow: \/[\s\S]*_openstat
    Disallow: \/[\s\S]*[\s\S]
    Host: lookmedbook\.ru

    User-agent:[\s\S]*
    Disallow: \/[\s\S]*search[\s\S]*
    Disallow: \/[\s\S]*utm_
    Disallow: \/[\s\S]*_openstat
    Disallow: \/[\s\S]*[\s\S]
    Sitemap: http:\/\/lookmedbook\.ru\/sitemap\.xml$/',$this->getText("css=pre")));
    }

    public function test_18()
    {
        $this->myLogin();
        $this->open("/system");
        $this->waitForPageToLoad();
        $this->click("link=Посещения клиник");
        sleep(10);
        $this->assertEquals("Комментарий call-центра", $this->getText("//div[@id='clinic-visits']/table[2]/thead/tr/td[3]"));
    }

    public function test_21()
    {
        $this->myLogin();
        $this->open("/system");
        $this->waitForPageToLoad();
        $this->click("link=Посещения клиник");
        sleep(10);
        $this->assertEquals("Обращения", $this->getText("css=table.visits-info > thead > tr > td"));
    }

    public function test_22()
    {
        $this->myLogin();
        $this->open("/system");
        $this->waitForPageToLoad();
        $this->click("link=Посещения клиник");
        sleep(9);
        $count = (int)$this->getText("css=#appeal");
        $this->open("/");
        $this->click("css=a.create-appeal.btn-1");
        sleep(2);
        $this->click("css=input[name=\"phone_number\"]");
        sleep(1);
        $this->type("css=input[name=\"phone_number\"]", "4952851280");
        $this->type("css=input[name=\"last_name\"]", "Лапыш");
        $this->type("css=input[name=\"first_name\"]", "Сергей");
        $this->type("css=input[name=\"middle_name\"]", "Викторович");
        $this->click("css=input[name=\"phone_number\"]");
        $this->click("css=input[name=\"with_visit\"]");
        $this->open("/system");
        $this->waitForPageToLoad();
        $this->click("link=Посещения клиник");
        sleep(9);
        $count_after = (int)$this->getText("css=#appeal");
        $this->assertEquals($count + 1, $count_after);

    }

    public function test_23()
    {
        $this->open("/");
        $this->waitForPageToLoad();
        $this->myLogin();
        $this->open("/system");
        $this->waitForPageToLoad();
        $this->click("link=Посещения клиник");
        sleep(10);
        $count = (int)$this->getText("css=#appeal");
        $this->adminLogin();
        $this->open("/admin/appeal");
        $this->waitForPageToLoad();
        $this->click("css=img[title=\"Удалить\"]");
        $this->assertTrue((bool)preg_match('/^Вы действительно хотите удалить эту запись[\s\S]$/',$this->getConfirmation()));
        $this->open("/system");
        $this->waitForPageToLoad();
        $this->click("link=Посещения клиник");
        sleep(10);
        $this->assertEquals((int)$this->getText("css=#appeal"), $count - 1);
    }

    public function test_25()
    {
        $this->myLogin();
        $this->open("/system");
        $this->waitForPageToLoad();
        $this->click("link=Посещения клиник");
        sleep(15);
        $count0 = (int)$this->getText("css=#all");
        $this->open("/doctor");
        $this->waitForPageToLoad();
        $count = $this->getXpathCount('//a[contains(@class, "btn-appoint")]');
        $this->assertGreaterThan(0, $count);
        $test_item = rand(1, $count);
        $this->click('xpath=(//a[contains(@class, "btn-appoint")])[position()='.$test_item.']');
        $this->waitForElementPresent('css=.record-to-the-doctor-popup');
        $slots_count = $this->getXpathCount('//li[contains(@class, "time-li")]');
        $slot_item = rand(1, $slots_count);
        $this->click('xpath=(//li[contains(@class, "time-li")])[position()='.$slot_item.']');
        $this->click('css=.record-to-the-doctor-popup .resume-btn');
        $this->click('css=.record-to-the-doctor-popup .god-mode');
        $this->click('css=.record-to-the-doctor-popup .send-button');
        sleep(3);
        $this->assertEquals("Поздравляем, вы успешно записались на прием. Ждите подтверждения!", $this->getText("css=div.fancybox-inner div"));
        $this->open("/system");
        $this->waitForPageToLoad();
        $this->click("link=Посещения клиник");
        sleep(10);
        $count_after = (int)$this->getText("css=#all");
        $this->assertEquals($count0 + 1, $count_after);
    }

    public function test_32()
    {
        $this->myLogin();
        $this->open("/doctor");
        $this->waitForPageToLoad();
        $count = $this->getXpathCount('//a[contains(@class, "btn-appoint")]');
        $this->assertGreaterThan(0, $count);
        $test_item = rand(1, $count);
        $this->click('xpath=(//a[contains(@class, "btn-appoint")])[position()='.$test_item.']');
        $this->waitForElementPresent('css=.record-to-the-doctor-popup');
        $slots_count = $this->getXpathCount('//li[contains(@class, "time-li")]');
        $slot_item = rand(1, $slots_count);
        $this->click('xpath=(//li[contains(@class, "time-li")])[position()='.$slot_item.']');
        $this->click('css=.record-to-the-doctor-popup .resume-btn');
        $this->click('css=.record-to-the-doctor-popup .god-mode');
        $this->click('css=.record-to-the-doctor-popup .send-button');
        sleep(3);
        $this->assertEquals("Поздравляем, вы успешно записались на прием. Ждите подтверждения!", $this->getText("css=div.fancybox-inner div"));
        $cd = date("d-m-Y");
        $this->type("name=filter[dt_create_from]", $cd);
        $this->type("name=filter[dt_create_to]", $cd);
        $this->click("css=input.submit");
    }

    public function test_35()
    {
        $this->myLogin();
        $this->click("css=a.create-appeal.btn-1");
        sleep(2);
        $this->click("css=input[name=\"phone_number\"]");
        sleep(1);
        $this->type("css=input[name=\"phone_number\"]", "4952851280");
        $this->type("css=input[name=\"last_name\"]", "Лапыш");
        $this->type("css=input[name=\"first_name\"]", "Сергей");
        $this->type("css=input[name=\"middle_name\"]", "Викторович");
        $this->click("css=input[name=\"phone_number\"]");
        $this->click("css=input[name=\"with_visit\"]");
        sleep(2);
        $this->adminLogin();
        $this->open("/admin/appeal");
        $this->waitForPageToLoad();
        $cd = date("d-m-Y");
        $this->type("name=filter[dt_create_from]", $cd);
        $this->type("name=filter[dt_create_to]", $cd);
        $this->click("css=input.submit");
        sleep(2);
        $this->click("css=img.edit-image");
        sleep(1);
        try {
            $this->assertEquals("on", $this->getValue("css=#is_with_visit_checkbox"));
        } catch (PHPUnit_Framework_AssertionFailedError $e) {
            array_push($this->verificationErrors, $e->toString());
        }


    }

    public function test_36()
    {
        $this->myLogin();
        $this->click("css=a.create-appeal.btn-1");
        sleep(2);
        $this->click("css=input[name=\"phone_number\"]");
        sleep(1);
        $this->type("css=input[name=\"phone_number\"]", "4952851280");
        $this->type("css=input[name=\"last_name\"]", "Лапыш");
        $this->type("css=input[name=\"first_name\"]", "Сергей");
        $this->type("css=input[name=\"middle_name\"]", "Викторович");
        $this->click("css=input[name=\"without_visit\"]");
        sleep(10);
        $this->adminLogin();
        $this->open("/admin/appeal/edit/?id=91&destination=");
        $this->waitForPageToLoad();
        try {
            $this->assertEquals("off", $this->getValue("css=#is_with_visit_checkbox"));
        } catch (PHPUnit_Framework_AssertionFailedError $e) {
            array_push($this->verificationErrors, $e->toString());
        }
    }

    public function test_37()
    {
        $this->adminLogin();
        $this->open("/admin/account");
        $this->waitForPageToLoad();
        $this->open("/admin/account/edit/?id=509&destination=");
        $this->type("css=input[name=\"form[email]\"]", "");
        $this->click("css=#submit_action");
        $this->waitForPageToLoad("30000");
        try {
            $this->assertEquals("", $this->getTable("css=table.list..1.4"));
        } catch (PHPUnit_Framework_AssertionFailedError $e) {
            array_push($this->verificationErrors, $e->toString());
        }
        $this->clickAndWait("css=img.edit-image");
        $this->type("css=input[name=\"form[email]\"]", "testtest@test.stets");
        $this->click("css=#submit_action");
        $this->waitForPageToLoad("30000");
    }

    public function test_38()
    {
        $this->adminLogin();
        $this->open("/admin/account/edit/?id=509&destination=");
        $this->waitForPageToLoad();
        $this->type("css=input[name=\"form[email]\"]", "qwqeqweqwe@wewrqwrq.qwrq");
        $this->click("css=#submit_action");
        $this->waitForPageToLoad("30000");
        $this->open("/admin/account/edit/?id=509&destination=");
        $this->waitForPageToLoad();
        $this->type("css=input[name=\"form[email]\"]", "");
    }






}