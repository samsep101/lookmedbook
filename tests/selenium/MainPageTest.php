<?php
    class MainPageTest extends BaseSeleniumTest
    {
        public function testMainPage()
        {
            $this->login();
            $this->click("css=img");

            $this->checkMyDoctorsLinkPresent();

            $this->switchMyDoctorMyClinicBlock();


            if ($this->test_account->last_uncommented_visit)
            {
                $this->hideVisitUncommentedBlock();
            } else {
                $this->notPresentNotificationBlocks();
            }


            if ($this->test_account->favorite_doctors)
            {
                $this->myDoctorPresent();
            } else {
                $this->myDoctorNotPresent();
            }

            $this->links();
            $this->switchDoctorClinicSearchForm();
        }

        private function hideVisitUncommentedBlock()
        {
            $this->open('/account');
            $this->waitForPageToLoad("30000");
            $this->assertTrue($this->isElementPresent("//html/body/div/div/div/div/div[@class=\"search-form\"]"));
            $this->assertTrue($this->isElementPresent("//html/body/div/div/div/div[2]/div[@class=\"info-block info-block-red info-block-uncommented\"]"));
            $this->click("css=span.close");
            $this->assertFalse($this->isVisible("//html/body/div/div/div/div[2]/div[@class=\"info-block info-block-red info-block-uncommented\"]"));
        }

        private function switchMyDoctorMyClinicBlock()
        {
            $this->open("/account");
            $this->click("//div[2]/div/ul/li[2]/span");
            $this->assertTrue($this->isVisible("//div[@class=\"side-column\"]/div[@class=\"search-form\"]/div[@class=\"box\"]/div[2]"));
            $this->assertFalse($this->isVisible("//div[@class=\"side-column\"]/div[@class=\"search-form\"]/div[@class=\"box\"]/div[1]"));
            $this->click("//html/body/div/div/div/div[2]/div/ul/li/span");
            $this->assertFalse($this->isVisible("//div[@class=\"side-column\"]/div[@class=\"search-form\"]/div[@class=\"box\"]/div[2]"));
            $this->assertTrue($this->isVisible("//div[@class=\"side-column\"]/div[@class=\"search-form\"]/div[@class=\"box\"]/div[1]"));
        }

        private function notPresentNotificationBlocks()
        {
            $this->assertFalse($this->isElementPresent("css=.info-block-uncommented"));
        }

        private function myDoctorPresent()
        {
            $this->open("/account");
            $this->assertTrue($this->isElementPresent("//div[@class=\"doctor-item flo\"]"));
        }

        private function myDoctorNotPresent()
        {
            $this->open("/account");
            $this->assertFalse($this->isElementPresent("//div[@class=\"doctor-item flo\"]"));
        }

        private function links()
        {
            $this->doctorSearchForm();
            //$this->clinicSearchForm();
        }

        private function doctorSearchForm()
        {
            $this->open('/account');
            $this->clickAndWait('css=div#doctor-search-form div.btn-box input.btn-1');
            $this->waitForPageToLoad(30000);
            $this->assertTrue($this->isElementPresent('css=#doctor-search-form'));
        }

        private function clinicSearchForm()
        {
            $this->open('/account');
            $this->click('css=.find-clinic input.btn-1');
            $this->waitForPageToLoad(30000);
            $this->assertEquals('Клиники', $this->getText('css=nav a.active'));
        }

        private function switchDoctorClinicSearchForm()
        {
            $this->open('/account');
            $this->click('//ul[@class="tabs flo"]/li[2]/span');
            $this->assertTrue($this->isVisible('css=div.section.find-clinic'));
            $this->assertFalse($this->isVisible('css=#doctor-search-form'));
            $this->click('//ul[@class="tabs flo"]/li[1]/span');
            $this->assertTrue($this->isVisible('css=#doctor-search-form'));
            $this->assertFalse($this->isVisible('css=div.section.find-clinic'));
        }

        private  function checkMyDoctorsLinkPresent()
        {

        }
    }