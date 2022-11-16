<?php
    require_once 'PHPUnit/Extensions/SeleniumTestCase.php';

    class AdminLoginTest extends PHPUnit_Extensions_SeleniumTestCase
    {
        protected function setUp()
        {
            $this->setBrowser("*firefox");
            $this->setBrowserUrl("http://lookmedbook.loc/");
        }

        public function testUnsuccessLogin()
        {
            $this->open("/admin/security/logout");
            $this->type("name=login", "denis");
            $this->type("name=password", "denis1");
            $this->clickAndWait("id=signin");
            $this->waitForPageToLoad("30000");
            $this->assertTrue($this->isVisible('css=.errormsg'));
        }

        public function testSuccessLogin()
        {
            $this->open("/admin/security/logout");
            $this->type("name=login", "denis");
            $this->type("name=password", "denis");
            $this->click("id=signin");
            $this->waitForPageToLoad("30000");
            $this->assertEquals("Выйти", $this->getText("//html/body/p/a/b"));
        }

        public function accessDenied()
        {
            $this->open("/admin/security/logout");
            $this->type("name=login", "test_registry");
            $this->type("name=password", "123456");
            $this->clickAndWait("id=signin");
            $this->waitForPageToLoad("30000");
            $this->open("/admin/account");
            $this->assertTrue($this->isElementPresent('name=login'));
            $this->assertTrue($this->isElementPresent('name=password'));
        }
    }
