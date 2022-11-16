<?php
    class HeaderTest extends BaseSeleniumTest
    {
        public function testLinks()
        {
            $this->login();

            $this->assertTrue($this->isElementPresent("//html/body/div/div/div/div/div[@class=\"soon-services flo\"]"));
            $this->click("link=Врачи");
            $this->waitForPageToLoad("30000");
            $this->assertEquals("Врачи", $this->getText("//nav/a[@class=\"active\"]"));
            $this->click("link=Клиники");
            $this->waitForPageToLoad("30000");
            $this->assertEquals("Клиники", $this->getText("//nav/a[@class=\"active\"]"));
            $this->click("link=Заболевания");
            $this->waitForPageToLoad("30000");
            $this->assertEquals("Заболевания", $this->getText("//nav/a[@class=\"active\"]"));
            $this->click("css=img");
            $this->waitForPageToLoad("30000");
        }

        public function diseaseBlock()
        {

        }
    }