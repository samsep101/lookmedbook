<?php

class SprintsTest extends BaseSeleniumTest {

    public function test_5()
    {
        $this->open("/clinic/medanna");
        $this->waitForPageToLoad();
        $this->assertTrue($this->isElementPresent("css=#ui-id-1"));
        $this->assertTrue($this->isElementPresent("css=#ui-id-2"));
    }

    public function test_6()
    {
        $this->open("/clinic/medicina-ask");
        $this->waitForPageToLoad();
        $this->assertTrue($this->isElementPresent("css=#ui-id-2"));
        $this->assertNotVisible("css=#ui-id-1");
    }

    public function test7()
    {
        $this->adminLogin();
        $this->open("/admin/clinic/edit/?id=93&destination=");
        $this->waitForPageToLoad();
        $this->click("css=h4");
        $this->type("css=input[name=\"image_id\"]", "C:\\Users\\Public\\Pictures\\Sample Pictures\\Tulips.jpg");
        $this->click("css=#submit_action");
        $this->waitForPageToLoad("30000");
        $this->open("/clinic/medicina-ask");
        $this->waitForPageToLoad();
        $this->assertTrue($this->isElementPresent("css=#ui-id-2"));
        $this->assertVisible("css=#ui-id-1");
        $this->adminLogin();
        $this->open("/admin/clinic/edit/?id=93&destination=");
        $this->waitForPageToLoad();
        $this->click("css=h4");
        $this->click("css=span.deleteImageButton");
        $this->click("css=#submit_action");
    }

    public function test_8()
    {
        $this->mainManagerLogin();
        $this->open("/registry/clinic/information?clinic_id=4");
        $this->type("css=input[name=\"form[postcode]\"]", "123123");
        $this->click("css=input[name=\"publish\"]");
        sleep(10);
        $this->click("css=#kladr-button");
        try {
            $this->assertEquals("127083", $this->getValue("css=input[name=\"form[postcode]\"]"));
        } catch (PHPUnit_Framework_AssertionFailedError $e) {
            array_push($this->verificationErrors, $e->toString());
        }
        $this->click("css=input[name=\"publish\"]");
        sleep(3);
    }

    public function test_9()
    {
        $this->mainManagerLogin();
        $this->open("/registry/clinic/information?clinic_id=3");
        $this->waitForPageToLoad();
        $this->type("css=input[name=\"form[postcode]\"]", "");
        $this->type("css=input[name=\"form[address]\"]", "ул. Снежная, 9");
        $this->click("css=#kladr-button");
        try {
            $this->assertEquals("129323", $this->getValue("css=input[name=\"form[postcode]\"]"));
        } catch (PHPUnit_Framework_AssertionFailedError $e) {
            array_push($this->verificationErrors, $e->toString());
        }
        $this->click("css=input[name=\"publish\"]");
        sleep(3);
    }

    public function test_10()
    {
        $this->mainManagerLogin();
        $this->open("/registry/clinic/information?clinic_id=3");
        $this->waitForPageToLoad();
        $this->type("css=input[name=\"form[postcode]\"]", "");
        $this->type("css=input[name=\"form[address]\"]", "улица Снежная, д. 9");
        $this->click("css=#kladr-button");
        try {
            $this->assertEquals("129323", $this->getValue("css=input[name=\"form[postcode]\"]"));
        } catch (PHPUnit_Framework_AssertionFailedError $e) {
            array_push($this->verificationErrors, $e->toString());
        }
        try {
            $this->assertEquals("Снежная, 9", $this->getValue("css=input[name=\"form[address]\"]"));
        } catch (PHPUnit_Framework_AssertionFailedError $e) {
            array_push($this->verificationErrors, $e->toString());
        }
        $this->click("css=input[name=\"publish\"]");
        sleep(3);
    }

    public function test_11()
    {
        $this->mainManagerLogin();
        $this->open("/registry/clinic/information?clinic_id=3");
        $this->waitForPageToLoad();
        $this->type("css=input[name=\"form[postcode]\"]", "");
        $this->type("css=input[name=\"form[address]\"]", "проспект Ломоносовский, дом 19");
        $this->click("css=#kladr-button");
        try {
            $this->assertEquals("129323", $this->getValue("css=input[name=\"form[postcode]\"]"));
        } catch (PHPUnit_Framework_AssertionFailedError $e) {
            array_push($this->verificationErrors, $e->toString());
        }
        try {
            $this->assertEquals("Ломоносовский, 19", $this->getValue("css=input[name=\"form[address]\"]"));
        } catch (PHPUnit_Framework_AssertionFailedError $e) {
            array_push($this->verificationErrors, $e->toString());
        }
        $this->click("css=input[name=\"publish\"]");
        sleep(3);
    }

    public function test_12()
    {
        $this->mainManagerLogin();
        $this->open("/registry/clinic/information?clinic_id=3");
        $this->waitForPageToLoad();
        $this->type("css=input[name=\"form[postcode]\"]", "");
        $this->type("css=input[name=\"form[address]\"]", "ул. Космонавта Волкова, 17к2с1");
        $this->click("css=#kladr-button");
        try {
            $this->assertEquals("127299", $this->getValue("css=input[name=\"form[postcode]\"]"));
        } catch (PHPUnit_Framework_AssertionFailedError $e) {
            array_push($this->verificationErrors, $e->toString());
        }
        $this->click("css=input[name=\"publish\"]");
        sleep(3);
    }

    public function test_13()
    {
        $this->mainManagerLogin();
        $this->open("/registry/clinic/information?clinic_id=3");
        $this->waitForPageToLoad();
        $this->type("css=input[name=\"form[postcode]\"]", "");
        $this->type("css=input[name=\"form[address]\"]", "ул. Орджоникидзе, 43а");
        $this->click("css=#kladr-button");
        try {
            $this->assertEquals("129323", $this->getValue("css=input[name=\"form[postcode]\"]"));
        } catch (PHPUnit_Framework_AssertionFailedError $e) {
            array_push($this->verificationErrors, $e->toString());
        }
        $this->click("css=input[name=\"publish\"]");
        sleep(3);
    }

    public function test_14()
    {
        $this->call_centerLogin();
        $this->click("css=a.create-appeal.btn-1");
        $this->click("css=div.content");
        $this->assertTrue($this->isElementPresent("css=div.popup-handling"));
    }

    public function test_15()
    {
            $this->call_centerLogin();
            $this->click("css=a.create-appeal.btn-1");
            $this->click("css=input[name=\"cancel\"]");
            $this->assertFalse($this->isElementPresent("css=div.popup-handling"));
    }

    public function test_16()
    {
        $this->open("/");
        $this->click("css=a..analysis-link");
        $this->waitForPageToLoad("30000");
        $this->assertEquals("Лаборатории сдачи анализов, с указанием времени работы и сдачи биоматериалов.", $this->getText("css=p.text"));
    }

    public function test_19()
    {
        $this->mainManagerLogin();
        $this->click("css=input.manage-add");
        $this->waitForPageToLoad("30000");
        $i = rand(2, 65536);
        $lo = rand(1111, 9999);
        $la = rand(1111, 9999);
        $this->type("css=input[name=\"clinic_name\"]", "Qwerty");
        $this->type("css=input[name=\"address\"]", "улица Уличная, $i");
        $this->type("css=input[name=\"longitude\"]", "37.$lo");
        $this->type("css=input[name=\"latitude\"]", "55.$la");
        $this->click("css=input[name=\"save\"]");
    }

    public function test_20()
    {
        $this->mainManagerLogin();
        $this->click("css=input.manage-add");
        $this->waitForPageToLoad("30000");
        $this->type("css=input[name=\"clinic_name\"]", "Qwwwweeeeeee");
        $this->type("css=input[name=\"address\"]", "улица Уличная, 1");
        $this->type("css=input[name=\"longitude\"]", "37.2356");
        $this->type("css=input[name=\"latitude\"]", "55.4587");
        $this->click("css=input[name=\"save\"]");
        $this->assertEquals("Клиника с данным адресом уже существует", $this->getText("css=label.error"));
    }

    public function test_21()
    {
        $this->mainManagerLogin();
        $this->click("css=input.manage-add");
        $this->waitForPageToLoad("30000");
        $i = rand(2, 65536);
        $this->type("css=input[name=\"clinic_name\"]", "Qwerty");
        $this->type("css=input[name=\"address\"]", "улица Уличная, $i");
        $this->type("css=input[name=\"longitude\"]", "37.6565");
        $this->type("css=input[name=\"latitude\"]", "55.7345");
        $this->click("css=input[name=\"save\"]");
        sleep(1);
        $this->assertEquals("Клиника с данными координатами уже существует", $this->getText("css=label.error"));
    }

    public function test_22()
    {
        $this->mainManagerLogin();
        $this->click("css=input.manage-add");
        $this->waitForPageToLoad("30000");
        $this->type("css=input[name=\"clinic_name\"]", "Qwerty");
        $this->type("css=input[name=\"address\"]", "улица Уличная, 1");
        $this->type("css=input[name=\"longitude\"]", "37.6565");
        $this->type("css=input[name=\"latitude\"]", "55.7345");
        $this->click("css=input[name=\"save\"]");
        sleep(1);
        $this->assertEquals("Клиника с данным адресом уже существует", $this->getText("css=label.error"));
    }

    public function test_25_26()
    {
        $this->mainManagerLogin();
        $this->open("/registry/clinic/information?clinic_id=4");
        $this->waitForPageToLoad();
        $this->select("css=select[name=\"form[metro_station_id]\"]", "label=Динамо (Замоскворецкая, Москва)");
        $this->click("css=span.txt");
        $this->assertEquals("Динамо (Замоскворецкая, Москва) удалить", $this->getText("css=div.metro-station-name"));
        $this->click("css=input[name=\"publish\"]");
        $this->waitForPageToLoad("30000");
        $this->assertEquals("Динамо (Замоскворецкая, Москва) удалить", $this->getText("css=div.metro-station-name"));
        $this->click("css=span.remove-metro");
        $this->click("css=input[name=\"publish\"]");
        $this->waitForPageToLoad("30000");
    }

    public function test_27()
    {
        $this->mainManagerLogin();
        $this->open("/registry/clinic/information?clinic_id=7");
        $this->waitForPageToLoad();
        $this->select("css=select[name=\"form[metro_station_id]\"]", "label=Александровский сад (Филевская, Москва)");
        $this->click("css=span.txt");
        $this->assertEquals("Это метро уже есть в списке", $this->getText("css=label.error"));
    }

    public function test_29()
    {
        $this->open("/doctor/vrach-funkcionalnoy-diagnostiki");
        $this->click("css=a.load-next-page.view-more");
        sleep(2);
        $this->click("css=a.load-next-page.view-more");
        sleep(2);
        $this->click("//div[@id='doctor-big-card-111431']/div[2]/div[2]/div/div/div/div[3]/ul");
        $this->click("css=div.content");
        $this->assertEquals("Врач функциональной диагностики", $this->getText("css=#doctor-big-card-111431 > div.descr > div.fixed_title > div.name > span.post"));
    }

    public function test_31()
    {
        $this->mainManagerLogin();
        $this->click("xpath=(//a[contains(text(),'Показать все')])[2]");
        $this->waitForPageToLoad("30000");
        $this->click("link=«Сердолик»");
        $this->waitForPageToLoad("30000");
        $this->click("link=Врачи клиники");
        $this->waitForPageToLoad();
        $this->type("css=input.fio-field", "Гончарова");
        $this->click("css=input.find-doctor");
        $this->click("css=a.bound-doctor.unbound-doctor");
        sleep(1);
        $this->type("css=input.fio-field", "Гончарова");
        $this->click("css=input.find-doctor");
        sleep(1);
        $this->assertEquals("По вашему запросу врачей не найдено", $this->getText("css=div.doctor-list > p"));
        $this->open("/registry/doctor/clinics?id=1314&clinic_id=12");
        $this->waitForPageToLoad();
        $this->select("css=select[name=\"clinic_id\"]", "label=«Сердолик»");
        $this->click("css=input[name=\"publish\"]");
        $this->waitForPageToLoad();
    }

    public function test_32()
    {
        $this->mainManagerLogin();
        $this->open("/registry/clinic/doctors?clinic_id=12");
        $this->waitForPageToLoad();
        $this->click("css=a.btn-appoint");
        $this->waitForPageToLoad("30000");
        $this->type("css=input.doctor-filter", "Каптильный");
        $this->select("css=select[name=\"form[full_name]\"]", "label=Каптильный Виталий Александрович");
        sleep(2);
        $this->type("name=form[about]","Test info");
        $this->click("css=input[name=\"save\"]");
        $this->waitForPageToLoad();
    }

    public function test_33()
    {
        $this->mainManagerLogin();
        $this->open("/registry/clinic/doctors?clinic_id=12");
        $this->waitForPageToLoad();
        $this->click("css=a.btn-appoint");
        $this->waitForPageToLoad("30000");
        $this->open("/registry/doctor/add?clinic_id=12");
        $this->waitForPageToLoad();
        $this->type("css=input[name=\"form[last_name]\"]", "Каптильный");
        $this->type("css=input[name=\"form[first_name]\"]", "Виталий");
        $this->type("css=input[name=\"form[second_name]\"]", "Александрович");
        $this->click("css=input[name=\"save\"]");
        sleep(1);
        try {
            $this->assertEquals("Создать нового врача", $this->getValue("css=div.create_block > input[name=\"cancel\"]"));
        } catch (PHPUnit_Framework_AssertionFailedError $e) {
            array_push($this->verificationErrors, $e->toString());
        }
    }



}
