<?php

class sprints2201 extends BaseSeleniumTest
{
    public function test_1()
    {
        $this->open("/doctor");
        $this->type("css=input[name=\"doctor_name\"]", "петровский");
        $this->click("css=input.btn-1.btn-doctor");
        sleep(2);
        $this->assertEquals("Андролог", $this->getText("css=span.post"));
        $this->assertEquals("Петровский Александр Валерьевич", $this->getText("css=p.doctorname_clear"));
    }

    public function test_2()
    {
        $this->open("/doctor");
        $this->waitForPageToLoad();
        $this->click("css=a.chzn-single > span");
        sleep(5);
        $this->click("css=li#specialties_to_search_doctor_chzn_o_5");
        $this->type("css=input[name=\"doctor_name\"]", "петровский");
        $this->click("css=input.btn-1.btn-doctor");
        sleep(2);
        $this->assertEquals("Андролог", $this->getText("css=span.post"));
        $this->assertEquals("Петровский Александр Валерьевич", $this->getText("css=p.doctorname_clear"));
    }

    public function test_3()
    {
        $this->open("/doctor");
        $this->waitForPageToLoad();
        $this->click("css=a.chzn-single > span");
        sleep(5);
        $this->click("css=li#specialties_to_search_doctor_chzn_o_5");
        $this->type("css=input[name=\"doctor_name\"]", "петровский");
        $this->click("css=input.btn-1.btn-doctor");
        sleep(2);
        $this->assertEquals("Андролог", $this->getText("css=span.post"));
        $this->assertEquals("Петровский Александр Валерьевич", $this->getText("css=p.doctorname_clear"));
        $this->type("css=input[name=\"doctor_name\"]", "");
        $this->click("css=input.btn-1.btn-doctor");
        sleep(2);
        $this->assertTrue(substr_count($this->getText("css=div.content"), 'терапевт')>9);
    }

    public function test_4()
    {
            $this->open("/doctor");
            $count = $this->getXpathCount('//a[contains(@class, "btn-appoint")]'); //получаем число элементов, с классом btn-appoint
            $this->assertGreaterThan(0, $count);
            $test_item = rand(1, $count);
            $this->click('xpath=(//a[contains(@class, "btn-appoint")])[position()='.$test_item.']'); //клик по рандомной кнопке
            $this->waitForElementPresent('css=.record-to-the-doctor-popup');//ожидание нашего попапа
            $slots_count = $this->getXpathCount('//li[contains(@class, "time-li")]'); //количество слотов на попапе
            $slot_item = rand(1, $slots_count);//выбераем рандомный слот
            $name = $this->getText("css=p.name > a > span");
            $this->click('xpath=(//li[contains(@class, "time-li")])[position()='.$slot_item.']');//кликаем по слоту
            $this->click('css=.record-to-the-doctor-popup .resume-btn');//кликаем на кнопку "Записаться"
            $this->type("css=input[name=\"surname\"]", "Редько Сергей Викторович");
            $this->type("css=input[name=\"phone\"]", "+7-495-641-26-26");
            $this->type("css=input[name=\"email\"]", "sergey.redko@actsystems.ru");
            $this->click('css=.record-to-the-doctor-popup .god-mode');
            $this->click('css=.record-to-the-doctor-popup .send-button');
            sleep(3);
            $this->type("css=input[name=\"password\"]", "2280104");
            $this->click('css=.record-to-the-doctor-popup .send-button');
            sleep(5);
            $this->assertEquals("Поздравляем, вы успешно записались на прием. Ждите подтверждения!", $this->getText("css=div.fancybox-inner div"));
            $this->adminLogin();
            $this->open("/admin/visit");
            $this->waitForPageToLoad("30000");
            $this->click("css=img.edit-image");
            $this->waitForPageToLoad("30000");
            $this->click("//div[@id='tab-0']/div/table/tbody/tr[14]/td[2]/input[6]");
            $this->type("css=#visit_start_time", "04.02.2014 11:29");
            $this->click("css=#submit_action");
            $this->waitForPageToLoad();
            $this->open("/doctor");
            $this->waitForPageToLoad();
            $this->type("css=input[name=\"doctor_name\"]", $name);
            $this->click("css=input.btn-1.btn-doctor");
            sleep(2);

    }







}