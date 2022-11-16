<?php
class sprints1712 extends BaseSeleniumTest
{
    public function test_6()
    {
        $this->mainManagerLogin();
        $this->open("/registry/doctor/information?id=1636");
        $this->waitForPageToLoad();
        $this->assertTrue($this->isElementPresent("css=#cke_8_label"));
    }

    public function test_13()
    {
        $this->open("/");
        $this->waitForPageToLoad();
        $this->click("css=small.order-call");
        $this->type("css=input[name=\"phone_number\"]", "2345678756");
        $this->click("css=input.btn-call");
        $this->assertEquals("Мы свяжемся с Вами\n в течение 5 минут", $this->getText("css=p.txt"));
    }

    public function test_10()
    {
        $this->open("/");
        $this->waitForPageToLoad();
        $this->assertEquals("LookMedBook - это online сервис записи к врачу и в клинику", $this->getText("css=h1"));
    }

    public function test_12()
    {
        $this->mainManagerLogin();
        $this->open('/registry/doctor/information?id=1641');
        $this->waitForPageToLoad();
        $this->runScript("CKEDITOR.instances['Rich text editor'].setData('<p>testContent</p>');");
        sleep(3);
        $this->click("css=input[name=\"publish\"]");
        sleep(5);
    }








}