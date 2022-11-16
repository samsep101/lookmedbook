<?php
    class DiseasePageTest extends BaseSeleniumTest
    {
        public function testPage()
        {
            $this->login();

            $disease_manager = new DiseaseManager();
            $diseases = $disease_manager->getActiveList();
            $this->disease = $diseases[rand(0, count($diseases) - 1)];

            $this->bookmarkButton();
            $this->understandButton();
            $this->notUnderstandButton();
        }

        public function testUnauthPage()
        {
            $this->logout();
        }

        public function testUnauthUnderstandButton()
        {
            $disease_manager = new DiseaseManager();
            $diseases = $disease_manager->getActiveList();
            $this->disease = $diseases[rand(0, count($diseases) - 1)];

            $this->deleteCookie();

            $this->open("/disease/get?id=".$this->disease->getId());
            $this->click("//a[contains(text(), 'Да')]");
            $this->waitForText('css=.section-help .info-buttons', 'Голос учтен');

            $this->open('/disease/get?id='.$this->disease->getId());
            $this->assertEquals('Голос учтен', $this->getText('css=.section-help .info-buttons'));
        }

        public function testUnauthNotUnderstandButton()
        {
            $disease_manager = new DiseaseManager();
            $diseases = $disease_manager->getActiveList();
            $this->disease = $diseases[rand(0, count($diseases) - 1)];

            $this->deleteCookie();

            $this->open("/disease/get?id=".$this->disease->getId());
            $this->click("//a[contains(text(), 'Нет')]");
            $this->waitForText('css=.section-help .info-buttons', 'Голос учтен');

            $this->open('/disease/get?id='.$this->disease->getId());
            $this->assertEquals('Голос учтен', $this->getText('css=.section-help .info-buttons'));
        }

        private function bookmarkButton()
        {
            $my_disease_manager = new MyDiseaseManager();
            $entry = $my_disease_manager->getOneByDiseaseIdAndAccountId($this->disease->getId(), $this->test_account->getId());
            if ($entry)
                $my_disease_manager->delete($entry);

            $this->open("/disease/get?id=" . $this->disease->getId());
            $this->click("css=div.about-ilness-content.flo");
            $this->click("css=span.txt");

            $this->waitForText("css=span.txt.txt-added", 'В закладках');
            $test_entry = $my_disease_manager->getOneByDiseaseIdAndAccountId($this->disease->getId(), $this->test_account->getId());

            $this->assertTrue((bool)$test_entry);
            $this->click("css=span.txt.txt-added");

            $this->waitForText("css=span.txt", "Добавить в закладки");
            $test_entry = $my_disease_manager->getOneByDiseaseIdAndAccountId($this->disease->getId(), $this->test_account->getId());

            $this->assertNull($test_entry);
        }

        private function understandButton()
        {
            $disease_understand_manager = new DiseaseUnderstandManager();

            $entry = $disease_understand_manager->getOneByAccountIdAndDiseaseId($this->test_account->getId(), $this->disease->getId());

            if ($entry)
                $disease_understand_manager->delete($entry);

            $this->open("/disease/get?id=".$this->disease->getId());

            $this->click("//a[contains(text(), 'Да')]");
            $this->waitForText('css=.section-help .info-buttons', 'Голос учтен');

            $entry = $disease_understand_manager->getOneByAccountIdAndDiseaseId($this->test_account->getId(), $this->disease->getId());

            $this->assertNotNull($entry);
            $this->assertEquals(1, $entry->understand_flag);
        }



        private function notUnderstandButton()
        {
            $disease_understand_manager = new DiseaseUnderstandManager();

            $entry = $disease_understand_manager->getOneByAccountIdAndDiseaseId($this->test_account->getId(), $this->disease->getId());

            if ($entry)
                $disease_understand_manager->delete($entry);

            $this->open("/disease/get?id=".$this->disease->getId());
            $this->click("//a[contains(text(), 'Нет')]");
            $this->waitForText('css=.section-help .info-buttons', 'Голос учтен');

            $entry = $disease_understand_manager->getOneByAccountIdAndDiseaseId($this->test_account->getId(), $this->disease->getId());

            $this->assertNotNull($entry);
            $this->assertEquals(0, $entry->understand_flag);
        }
    }